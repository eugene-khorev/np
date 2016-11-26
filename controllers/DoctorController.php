<?php

namespace app\controllers;

use app\models\Doctor;
use app\models\Schedule;
use app\models\ScheduleDaily;
use app\models\ReservationModel;

class DoctorController extends \jsonrpc\Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'bearerAuth' => [
                'class' => \jsonrpc\HttpBearerAuth::className(),
            ],
        ]);
    }

    /**
     * Returns list of doctors
     * 
     * @return array
     */
    public function rpcGetList()
    {
        return Doctor::GetList();
    }

    /**
     * Returns reserved time of a doctor
     * 
     * @param type $doctorId
     * @return type
     */
    public function rpcGetSchedule($doctorId)
    {
        return Doctor::GetScheduledTime($doctorId);
    }

    public function rpcUpdateSchedule(ReservationModel $data)
    {
        // Find specified doctor
        $doctor = Doctor::findOne($data->doctorId);
        if (empty($doctor)) {
            throw new \Exception('Doctor not found');
        }
        
        // Init ScheduleDaily entity
        $scheduleDaily = new ScheduleDaily;
        
        // Find and lock visit list for specified doctor and date
        $connection = \Yii::$app->getDb();
        $command = $connection->createCommand(
                'SELECT * FROM `schedule_daily` WHERE `doctor_id` = :doctor_id AND `visit_date` = DATE(:reservation_time) FOR UPDATE',
                [
                    'doctor_id' => $data->doctorId, 
                    'reservation_time' => $data->reservationTime
                ]);
        $result = $command->queryOne();

        // Init Schedule entity
        $schedule = new Schedule;
        $schedule->doctor_id = $data->doctorId;
        $schedule->user_id = \Yii::$app->user->getId();
        $schedule->reserved_from = $data->reservationTime;
        $schedule->reserved_till = date('Y-m-d H:i:s', 
                strtotime($data->reservationTime) + $doctor->GetReservationTime()
                );

        // If no data found setup a new record
        if (empty($result)) {
            $scheduleDaily->doctor_id = $data->doctorId;
            $scheduleDaily->visit_date = date('Y-m-d');
            $scheduleDaily->visitArray = [];
        } else {
            $scheduleDaily->setAttributes($result);
        }

        $scheduleDaily->setVisitArray(array_merge(
                $scheduleDaily->visitArray,
                [[
                    'reserved_from' => $schedule->reserved_from,
                    'reserved_till' => $schedule->reserved_till,
                ]]));

        if (!$scheduleDaily->validate()) {
            throw new \Exception('Interval is already reserved');
        }
        
        $command = $connection->createCommand(
                'UPDATE `schedule_daily` SET `visit_list` = :visit_list WHERE `doctor_id` = :doctor_id AND `visit_date` = DATE(:reservation_time)',
                [
                    'visit_list' => $scheduleDaily->visit_list,
                    'doctor_id' => $data->doctorId,
                    'reservation_time' => $data->reservationTime
                ]);
        $result = $command->execute();

        if (empty($result)) {
            throw new \Exception('Error updateing schedule');
        }
        
        return $result;
    }
    
}
