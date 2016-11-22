<?php

namespace app\controllers;

class AjaxController extends \jsonrpc\Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * This is JSON-RPC 2.0 controller action
     */
    public function rpcEcho($param1, $param2)
    {
        return ['recievedData' => ['param1' => $param1, 'param2' => $param2]];
    }

}
