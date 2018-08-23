<?php
namespace common\components;

use Yii;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class Upload {
    private $accessKey;   //七牛云的ak
    private $secretKey;   //七牛云sk
    private $bucket;      //七牛云容器
    private $baseUrl;     //七牛云url
    private $rootPath;    // 根目录
    private $isQiniuUpload;
    private $originName;        //源文件名
    private $tmpFileName;              //临时文件名
    private $fileType;              //文件类型(文件后缀)
    private $fileSize;              //文件大小
    private $newFileName;             //新文件名
    private $errorNum;               //错误号
    private $errorMess;             //错误报告消息

    private $config = array(
        'path'       => "tmp/",          //上传文件保存的本地路径
        'allow_type'  => array('jpg','gif','png','pdf','doc','docx','zip','rar'), //设置限制上传文件的类型
        'maxsize'    => 2 * 1024 * 1024,           //限制文件上传大小（字节）
        'is_randname' => true,              //设置是否随机重命名文件， false不随机
        'fileFormat' => array(),       // 允许提交的文件格式
        'is_qiniu' => true,             // 上传到七牛
    );

    public function __construct($config=array())
    {
        $this->config = array_merge($this->config, $config);
        $this->accessKey = Yii::$app->params['qiniu_ak'];
        $this->secretKey = Yii::$app->params['qiniu_sk'];
        $this->bucket = Yii::$app->params['qiniu_bucket'];
        $this->baseUrl = Yii::$app->params['qiniu_domain'];
        $this->rootPath = $this->config['qiniu_path'];
        $this->isQiniuUpload = $this->config['is_qiniu'];

    }

    /**
     * 用于设置成员属性（$path, $allow_type,$maxsize, $is_randname）
     * 可以通过连贯操作一次设置多个属性值
     *@param  string $key  成员属性名(不区分大小写)
     *@param  mixed  $val  为成员属性设置的值
     *@return  object     返回自己对象$this，可以用于连贯操作
     */
    public function set($key, $val){
        $key = strtolower($key);
        if( array_key_exists( $key, get_class_vars(get_class($this) ) ) ){
            $this->setOption($key, $val);
        }
        return $this;
    }

    /**
     * 使用 $this->name 获取配置
     * @param  string $name 配置名称
     * @return multitype    配置值
     */
    public function __get($name) {
        return $this->config[$name];
    }

    public function __set($name,$value){
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

    public function __isset($name){
        return isset($this->config[$name]);
    }

    /**
     * 调用该方法上传文件
     * @param  string $fileFile  上传文件的表单名称
     * @return bool        如果上传成功返回数true
     */

    public function upload($files=null) {
        if ($_FILES) {
            $files = $_FILES;
        }else{
            $this->errorNum = 4;
            return $this->getError();
        }
        $return = true;
        /* 检查文件路径是否合法 */
        if( !$this->checkFilePath() ) {
            $this->errorMess = $this->getError();
            return false;
        }
        /* 将文件上传的信息取出赋给变量 */

        foreach ($files as $key => $file){
            $name = $file['name'];
            $tmp_name = $file['tmp_name'];
            $size = $file['size'];
            $error = $file['error'];
        }

        /* 如果是多个文件上传则$file["name"]会是一个数组 */
        if(is_array($name)){
           return;
        } else {
            /* 设置文件信息 */
            if($this->setFiles($name,$tmp_name,$size,$error)) {
                /* 上传之前先检查一下大小和类型 */
                if($this->checkFileSize() && $this->checkFileType()){
                    /* 为上传文件设置新文件名 */
                    $this->setNewFileName();
                    /* 上传文件  返回0为成功， 小于0都为错误 */
                    $saveName = $this->copyFile();
                    if($saveName){
                        if($this->isQiniuUpload){
                            $savePath = $this->newFileName;
                            // 创建子目录 TODO:(后期可改成自定义规则)
                            $subName = date('Ymd') . '/';
                            $path = $this->path . $saveName;
                            if($this->qiniuUpload($path, $this->rootPath . $subName . $savePath)){
                                unlink($path);
                                return $subName . $savePath;
                            }
                        }
                        return $saveName;
                    }else{
                        $return=false;
                    }
                }else{
                    $return=false;
                }
            } else {
                $return=false;
            }
            //如果$return为false, 则出错，将错误信息保存在属性errorMess中
            if(!$return)
                $this->errorMess=$this->getError();

            return $this->errorMess;
        }
    }

    /**
     * 获取上传后的文件名称
     * @param  void   没有参数
     * @return string 上传后，新文件的名称， 如果是多文件上传返回数组
     */
    public function getFileName(){
        return $this->newFileName;
    }

    /**
     * 上传失败后，调用该方法则返回，上传出错信息
     * @param  void   没有参数
     * @return string  返回上传文件出错的信息报告，如果是多文件上传返回数组
     */
    public function getErrorMsg(){
        return $this->errorMess;
    }

    /* 设置上传出错信息 */
    private function getError() {
        $str = "上传文件{$this->originName}时出错 : ";
        switch ($this->errorNum) {
            case 4: $str .= "没有文件被上传"; break;
            case 3: $str .= "文件只有部分被上传"; break;
            case 2: $str .= "上传文件的大小超过了HTML表单中MAX_FILE_SIZE选项指定的值"; break;
            case 1: $str .= "上传的文件超过了php.ini中upload_max_filesize选项限制的值"; break;
            case -1: $str .= "未允许类型"; break;
            case -2: $str .= "文件过大,上传的文件不能超过{$this->maxsize}个字节"; break;
            case -3: $str .= "上传失败"; break;
            case -4: $str .= "建立存放上传文件目录失败，请重新指定上传目录"; break;
            case -5: $str .= "必须指定上传文件的路径"; break;
            case -6: $str .= "必须上传指定的文件格式"; break;
            case -7: $str .= "七牛云上传出错"; break;
            default: $str .= "未知错误";
        }
        $res = [
            'code' => 500,
            'msg' => $str
        ];
        return $res;
    }

    /* 设置和$_FILES有关的内容 */
    private function setFiles($name="", $tmp_name="", $size=0, $error=0) {
        $this->setOption('errorNum', $error);
        if($error)
            return false;
        $this->setOption('originName', $name);
        $this->setOption('tmpFileName',$tmp_name);
        $aryStr = explode(".", $name);
        $this->setOption('fileType', strtolower($aryStr[count($aryStr)-1]));
        $this->setOption('fileSize', $size);
        return true;
    }

    /* 为单个成员属性设置值 */
    private function setOption($key, $val) {
        $this->$key = $val;
    }

    /* 设置上传后的文件名称 */
    private function setNewFileName() {
        if ($this->is_randname) {
            $this->setOption('newFileName', $this->proRandName());
        } else{
            $this->setOption('newFileName', $this->originName);
        }
    }

    /* 检查上传的文件是否是合法的类型 */
    private function checkFileType() {
        if (in_array(strtolower($this->fileType), $this->allow_type)) {
            return true;
        }else {
            $this->setOption('errorNum', -1);
            return false;
        }
    }

    private function checkFileFormat(){
        // 使用fileinfo完成对文件格式的检测，防止修改后缀
        $finfo = new \Finfo(FILEINFO_MIME_TYPE);
        $fileType = $finfo->file($this->tmpFileName);
        if (!in_array($fileType, $this->fileFormat)) {
            $this->setOption('errorNum', -6);
            return false;
        }
    }

    /* 检查上传的文件是否是允许的大小 */
    private function checkFileSize() {
        if ($this->fileSize > $this->maxsize) {
            $this->setOption('errorNum', -2);
            return false;
        }else{
            return true;
        }
    }

    /* 检查是否有存放上传文件的目录 */
    private function checkFilePath() {
        if(empty($this->path)){
            $this->setOption('errorNum', -5);
            return false;
        }
        if (!file_exists($this->path) || !is_writable($this->path)) {
            if (!@mkdir($this->path, 0755)) {
                $this->setOption('errorNum', -4);
                return false;
            }
        }
        return true;
    }

    /* 设置随机文件名 */
    private function proRandName() {
        $fileName = uniqid();
        return $fileName.'.'.$this->fileType;
    }

    /* 复制上传文件到指定的位置 */
    private function copyFile() {
        if(!$this->errorNum) {
            $path = $this->path . $this->newFileName;
            if (move_uploaded_file($this->tmpFileName, $path)) {
                return $this->newFileName;
            }else{
                $this->setOption('errorNum', -3);
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 上传七牛云
     * @param $filePath 要上传的文件路径
     * @param $saveName 七牛云保存的文件名
     * @return string
     */
    private function qiniuUpload($filePath, $savePath)
    {
        $auth = new Auth($this->accessKey, $this->secretKey);
        $uploadMgr = new UploadManager();

        // 上传到七牛后保存的文件名
        $key = $savePath;

        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        $res = $uploadMgr->putFile($auth->uploadToken($this->bucket), $key, $filePath);
        if ($res !== null) {
            $baseUrl = $this->baseUrl.'/'.$key;
            // 对链接进行签名
            $signedUrl = $auth->privateDownloadUrl($baseUrl);
            return $signedUrl;
        } else {
            $this->setOption('errorNum', -7);
            return false;
        }
    }
}