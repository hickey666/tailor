<?php

/**
 * 发送短信类
 * 用于阿里短信的接口
 */
namespace common\components;

use Yii;
use Flc\Dysms\Client;
use Flc\Dysms\Request\SendSms;

class Sms
{
    public $SignName = '韩创科技';

    public $TemplateCode = 'SMS_113456444 ';      // 随机密码模板

    public $OutId = '123';

    /**
     * @param $phone  发送的号码
     * @param $code   发送的数据
     * @return string OK表示发送成功
     */
    public function send($phone, $code)
    {
        $config = Yii::$app->params['dysms'];

        $client  = new Client($config);
        $sendSms = new SendSms;
        $sendSms->setPhoneNumbers($phone);
        $sendSms->setSignName($this->SignName);
        $sendSms->setTemplateCode($this->TemplateCode);
        $sendSms->setTemplateParam(["code"=>$code, "product"=>"韩创科技"]);
        $sendSms->setOutId($this->OutId);

        // print_r($client->execute($sendSms));
        return $client->execute($sendSms)->Message;
    }
}