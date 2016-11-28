<?php

namespace app\models;

use yii\base\Model;

class LoginModel extends Model
{

    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
        ];
    }

}
