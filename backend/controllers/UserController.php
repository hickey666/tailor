<?php

namespace backend\controllers;

use common\components\Email;
use Yii;
use backend\models\User;
use common\helpers\StringHelper;
use common\helpers\FuncHelper;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public $enableCsrfValidation = false;

    private $expire = 120;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['send-captcha', 'reset-password'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionResetPassword($id)
    {
        $model = $this->findModel($id);


        if (Yii::$app->request->isPost) {
            $redis = Yii::$app->redis;
            $params = Yii::$app->request->post();
            $captcha = $redis->get($params['email']);
            if (!$captcha)
                FuncHelper::ajaxReturn(400, '验证码过期...');
            if ($captcha != $params['captcha'])
                FuncHelper::ajaxReturn(400, '验证码不正确...');
            if (strlen($params['password']) < 6)
                FuncHelper::ajaxReturn(400, '请输入6位以上的密码..');
            $user = $this->findModel($params['id']);
            $user->email = $params['email'];
            $user->auth_key = '';
            $user->setPassword($params['password']);
            if ($user->save()){
                Yii::$app->user->logout();
                FuncHelper::ajaxReturn(200, '修改密码成功');
            }
            FuncHelper::ajaxReturn(400, '修改失败，请稍后...');
        }

        return $this->render('reset-password', [
            'model' => $model,
        ]);
    }

    /**
     * 获取验证码
     */
    public function actionSendCaptcha()
    {
        // redis缓存
        $redis = Yii::$app->redis;
        $email = Yii::$app->request->post('email');
        $key = $email . '_isSend';
        if (!$email) FuncHelper::ajaxReturn(400, '请输入邮箱');
        // 防止频繁发送短信
        if ($captcha = $redis->get($email) && $redis->get($key)){
            FuncHelper::ajaxReturn(400, '请不要频繁操作获取验证码');
        } else {
            $captcha = StringHelper::randString(6,1);
            $redis->set($email, $captcha);
            $redis->expire($email, $this->expire);
        }
        // 发送验证码
        $data['code'] = $captcha;
        $res = Email::send($data, 'captcha', $email, 'tailor的验证码');
        if ($res == true){
            $redis->set($key, 1);
            $redis->expire($key, $this->expire);
            FuncHelper::ajaxReturn(200, '请查看邮件');
        } else {
            FuncHelper::ajaxReturn(400, '邮件发送失败，请稍后重试...');
        }
    }
}
