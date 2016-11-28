<?php

namespace app\models;

use yii\base\Model;

class RegistrationModel extends Model
{

    public $email;
    public $password;
    public $password_repeat;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password', 'password_repeat'], 'required'],
            ['email', 'email'],
            ['password', 'compare', 'compareAttribute' => 'password_repeat'],
        ];
    }

}
