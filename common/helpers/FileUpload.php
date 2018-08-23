<?php

namespace common\helpers;

use yii\web\UploadedFile;

class FileUpload
{
    /**
     * 文件上传
     * ```
     *  $model = new UploadValidate($config_name);
     *  $result = CommonHelper::myUpload($model, $field, 'invoice');
     * ```
     *
     * @param  object $model \common\models\UploadValidate 验证上传文件
     * @param  string $field 上传字段名称
     * @param  string $path  文件保存路径
     *
     * @return bool|array
     */
    public static function UploadImg($model, $field, $path = '')
    {
        $upload_path = \Yii::$app->params['img_upload']['upload_path'];
        $path = $path ? $path . "/" : '';
        if (\Yii::$app->request->isPost) {
            $file = UploadedFile::getInstanceByName($field);
            $model->file = $file;
            //文件上传存放的目录
            $dir = $upload_path . $path . '/'.date("Ymd");
            // echo $dir;die;
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }
            if ($model->validate()) {
                //生成文件名
                $rand_name = rand(1000, 9999);
                $fileName = date("YmdHis") . $rand_name . '_' . $model->file->baseName . "." . $model->file->extension;
                $save_dir = $dir . "/" . $fileName;
                $model->file->saveAs($save_dir);
                $uploadSuccessPath = $path . date("Ymd") . "/" . $fileName;
                $result['file_name'] = $model->file->baseName;
                $result['file_path'] = $uploadSuccessPath;
            } else {
                //上传失败记录日志
                // self::recordLog($model->errors, $field, 'Upload');
                return false;
            }
        } else {
            return false;
        }
    }
}