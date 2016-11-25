<?php

namespace app\controllers;

use app\models\Doctor;
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

}
