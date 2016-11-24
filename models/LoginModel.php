<?php

namespace app\models;

use yii\base\Model;

class LoginModel extends Model
{

    public $email;
    public $password;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
        ];
    }

}
