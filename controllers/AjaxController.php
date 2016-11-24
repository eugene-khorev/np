<?php

namespace app\controllers;

class AjaxController extends \jsonrpc\Controller
{

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'bearerAuth' => [
                'class' => \jsonrpc\HttpBearerAuth::className(),
            ],
        ]);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function rpcEcho($param1, $param2)
    {
        return ['recievedData' => ['param1' => $param1, 'param2' => $param2]];
    }

}
