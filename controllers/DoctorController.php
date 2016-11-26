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
        
        // Init Schedule entity
        $schedule = new Schedule;
        $schedule->doctor_id = $data->doctorId;
        $schedule->user_id = \Yii::$app->user->getId();
        $schedule->reserved_from = $data->reservationTime;
        $schedule->reserved_till = date('Y-m-d H:i:s', 
                strtotime($data->reservationTime) + $doctor->GetReservationTime()
                );
        
        ScheduleDaily::reserveTimeInterval($schedule);
        
        $schedule->save();
        
        return $schedule;
    }
    
}
