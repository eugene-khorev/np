<?php

namespace app\models;

class Schedule extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'doctor_id', 'reserved_from', 'reserved_till'], 'required'],
        ];
    }

}
