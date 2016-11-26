<?php

namespace app\models;

class ScheduleDaily extends \yii\db\ActiveRecord
{
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doctor_id', 'visit_date', 'visit_list'], 'required'],
            ['visit_list', 'validateVisitList'],
        ];
    }
    
    public function validateVisitList($attribute, $params)
    {
        $this->addError($attribute, 'Time rage ovelap');
    }
    
    public function getVisitArray()
    {
        return \yii\helpers\Json::decode($this->visit_list);
    }
    
    public function setVisitArray($value)
    {
        $this->visit_list = \yii\helpers\Json::encode($value);
    }
    
}
