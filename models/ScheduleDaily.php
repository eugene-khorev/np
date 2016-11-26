<?php

namespace app\models;

class ScheduleDaily extends \yii\db\ActiveRecord
{

    private $visitList = [];
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doctor_id', 'visit_date', 'visit_list'], 'required'],
        ];
    }

    public static function reserveTimeInterval(Schedule $schedule)
    {
        $model = static::findAndLock($schedule);
        $model->addVisit($schedule);
        $model->updateScheduleVisits($schedule);
    }

    private static function findAndLock(Schedule $schedule)
    {
        // Init ScheduleDaily entity
        $model = new static;
        
        // Find and lock visit list for specified doctor and date
        $command = \Yii::$app->getDb()->createCommand(
                'SELECT * FROM `schedule_daily` WHERE `doctor_id` = :doctor_id AND `visit_date` = DATE(:reservation_time) FOR UPDATE',
                [
                    'doctor_id' => $schedule->doctor_id,
                    'reservation_time' => $schedule->reserved_from,
                ]);
        $result = $command->queryOne();

        $model->setIsNewRecord(empty($result));
        
        // Use existing record or setup a new one
        if (!$model->getIsNewRecord()) {
            $model->setAttributes($result);
        } else {
            $model->doctor_id = $schedule->doctor_id;
            $model->visit_date = date('Y-m-d');
            $model->visitList = [];
        }
        
        return $model;
    }
    
    private function updateScheduleVisits(Schedule $schedule)
    {
        if ($this->getIsNewRecord()) {
            $result = $this->save();
        } else {
            $command = \Yii::$app->getDb()->createCommand(
                    'UPDATE `schedule_daily` SET `visit_list` = :visit_list WHERE `doctor_id` = :doctor_id AND `visit_date` = DATE(:reservation_time)',
                    [
                        'visit_list' => $this->visit_list,
                        'doctor_id' => $schedule->doctor_id,
                        'reservation_time' => $schedule->reserved_from
                    ]);
            $result = $command->execute();
        }
        

        if (empty($result)) {
            throw new \Exception('Error updateing schedule');
        }

    }
    
    private function addVisit(Schedule $schedule)
    {
        // !!! CHECK IF INTERVALS OVERLAP
        
        $this->setVisitList(array_merge(
                $this->getVisitList(),
                [[
                    'reserved_from' => $schedule->reserved_from,
                    'reserved_till' => $schedule->reserved_till,
                ]]));
    }
    
    private function getVisitList()
    {
        if (empty($this->visitList) && !empty($this->visit_list)) {
            $this->visitList = \yii\helpers\Json::decode($this->visit_list);
        }
        return $this->visitList;
    }

    private function setVisitList($value)
    {
        $this->visitList = $value;
        $this->visit_list = \yii\helpers\Json::encode($value);
    }

}
