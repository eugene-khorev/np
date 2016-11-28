<?php

namespace app\models;

use yii\base\Model;

class ReservationModel extends Model
{

    public $doctorId;
    public $reservationTime;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doctorId', 'reservationTime'], 'required'],
            ['doctorId', 'integer'],
            ['reservationTime', 'datetime', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }

}
