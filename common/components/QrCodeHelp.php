<?php

namespace common\components;

use Yii;
use yii\web\Response;
use Da\QrCode\QrCode;

class QrCodeHelp
{
    /**
     * 直接输出二维码
     * @param $url
     * @return mixed
     */
    public function displayQrCode($url)
    {
        $qr = Yii::$app->get('qr');

        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', $qr->getContentType());

        return $qr
            ->setText($url)
            ->writeString();
    }

    /**
     * 生成二维码图片
     * @param $url
     * @param $savePath
     */
    public function imgQrCode($url, $savePath)
    {
        $qrCode = (new QrCode($url))
            ->setSize(250)
            ->setMargin(5);
        $qrCode->writeFile( $savePath);
    }
}