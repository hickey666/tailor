<?php

namespace common\components;

use Yii;
use yii\web\Controller;

class Email extends Controller
{

    /**
     * 发送邮件
     * @param array  $data     模板数据
     * @param string $template 模板
     * @param string $receiver 收件人
     * @param string $subject  邮件标题
     * @param array  $attaches 附件列表
     * @return bool
     */
    public static function send($data, $template, $receiver, $subject, $attaches = [])
    {
        $mail= Yii::$app->mailer->compose($template,['data' => $data]);
        $mail->setTo($receiver); //要发送给那个人的邮箱
        $mail->setSubject($subject); //邮件主题

        // 添加附件
        foreach ($attaches as $path) {
            if (is_string($path) && file_exists($path)) {
                $mail->attach($path);
            }
        }

        return $mail->send();
    }

}