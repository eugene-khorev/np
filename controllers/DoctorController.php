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
    public function rpcGetSchedule(ReservationModel $data)
    {
        return [
            'reservation_time' => Doctor::GetReservationTime(),
            'reservation_from' => Doctor::GetReservationFrom(),
            'reservation_till' => Doctor::GetReservationTill(),
            'reserved' => Doctor::GetScheduledTime($data->doctorId, $data->reservationTime),
            ];
    }

    /**
     * Reserves time in doctor schedule
     * 
     * @param ReservationModel $data
     * @return Schedule
     * @throws \Exception
     */
    public function rpcUpdateSchedule(ReservationModel $data)
    {
        // Find specified doctor
        $doctor = Doctor::findOne($data->doctorId);
        if (empty($doctor)) {
            throw new \Exception('Doctor not found');
        }

        // Init Schedule entity
        $schedule = new Schedule;
        $schedule->doctor_id = $data->doctorId;
        $schedule->user_id = \Yii::$app->user->getId();
        $schedule->reserved_from = $data->reservationTime;
        $schedule->reserved_till = date('Y-m-d H:i:s',
                strtotime($data->reservationTime) + Doctor::GetReservationTime()
                );

        ScheduleDaily::reserveTimeInterval($schedule);

        $schedule->save();

        return $schedule;
    }

}
