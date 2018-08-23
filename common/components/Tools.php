<?php
namespace common\components;

use Yii;
use yii\base\Model;

Class Tools extends Model
{
    // 获取真实ip
    public static function getIp(){
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        }
        elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        }
        elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        }
        elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');

        }
        elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        }
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public static function ip2Int($ip)
    {
        return intval(bindec(decbin(ip2long($ip))));
    }

    /**
     * $action 当前操作的方法
     * $params 请求参数
     * $uid 操作人id
     * @param $msg 日志内容
     * @return mixed
     */
    public static function Logs($msg=''){
        $ip = self::ip2Int(self::getIp());
        $action = Yii::$app->controller->action->id;
        $controller = Yii::$app->controller->id;
        if (Yii::$app->request->isPost) {
            $post['method'] = 'POST';
            $post['params'] = Yii::$app->request->post();
            $params = json_encode($post);
        }else{
            $get['method'] = 'GET';
            $get['params'] = Yii::$app->request->queryParams;
            $params = json_encode($get);
        }
        $uid = Yii::$app->user->id;
        $created = time();
        $data['ip'] = $ip;
        $data['action'] = $action;
        $data['controller'] = $controller;
        $data['params'] = $params;
        $data['uid'] = $uid;
        $data['created'] = $created;
        $data['msg'] = $msg;
        $res = (new \yii\db\Query())->createCommand()->insert('hc_logs', $data)->execute();
        return $res;
    }
}