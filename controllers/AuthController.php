<?php

namespace app\controllers;

use app\models\RegistrationModel;
use app\models\LoginModel;
use app\models\User;

class AuthController extends \jsonrpc\Controller
{

    /**
     * Registers a new user
     * 
     * @param RegistrationModel $data
     * @return array New user token
     */
    public function rpcRegister(RegistrationModel $data)
    {
        $user = new User;
        $user->setAttribute('username', $data->email);
        $user->setAttribute('password', $data->password);
        $user->save();
        
        \Yii::$app->user->login($user);
        
        return [ 'token' => $user->access_token ];
    }

    /**
     * Authenticates a user
     * 
     * @param LoginModel $data
     * @return array New user token
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function rpcLogin(LoginModel $data)
    {
        $user = User::findByUsername($data->email);
        
        if (empty($user)) {
            throw new \yii\web\UnauthorizedHttpException('Invalid credentials');
        }
        
        if (!$user->validatePassword($data->password)) {
            throw new \yii\web\UnauthorizedHttpException('Invalid credentials');
        }
        
        $user->ResetAccessToken();
        
        \Yii::$app->user->login($user);
        
        return [ 'token' => $user->access_token ];
    }

}
