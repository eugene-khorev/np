<?php

namespace app\models;

use app\models\Schedule;

class Doctor extends \yii\db\ActiveRecord
{

    /**
     * Interval to reserve in seconds
     */
    const RESERVATION_TIME = 30*60;

    /**
     * Working hours
     */
    const RESERVATION_FROM = 9;
    const RESERVATION_TILL = 18;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
        ];
    }

    /**
     * Returns list of doctors
     *
     * @return array List of doctors
     */
    public static function GetList()
    {
        return static::find()->all();
    }

    /**
     * Returns reserved time intervals for specified doctor
     *
     * @param int $doctorId
     * @return array List of reserved intervals
     */
    public static function GetScheduledTime($doctorId, $reservationTime)
    {
        return static::findOne($doctorId)
                        ->getSchedule()
                        ->select('reserved_from, reserved_till')
                        ->where([ '>', 'reserved_from', date('Y-m-d', strtotime($reservationTime))])
                        ->orderBy('reserved_from')
                        ->all();
    }

    /**
     * Relationship mapping for schedule table
     *
     * @return ActiveQueryInterface Relational query object.
     */
    public function getSchedule()
    {
        return $this->hasMany(Schedule::className(), [ 'doctor_id' => 'id' ]);

    }

    /**
     * Returns Interval to reserve
     *
     * @return int Seconds to reserve in schedule
     */
    public static function GetReservationTime()
    {
        return static::RESERVATION_TIME;
    }

    /**
     * Returns Interval to reserve
     *
     * @return int Seconds to reserve in schedule
     */
    public static function GetReservationFrom()
    {
        return static::RESERVATION_FROM;
    }

    /**
     * Returns Interval to reserve
     *
     * @return int Seconds to reserve in schedule
     */
    public static function GetReservationTill()
    {
        return static::RESERVATION_TILL;
    }

}
