<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2016/1/14
 * Time: 13:51
 */

namespace juckzhang\drivers;

use dcb9\qiniu\Component;
use yii\base\InvalidValueException;

class UploadQiNiu extends UploadYunInterface
{
    public $qiNiuConfig;
    public $diskName;
    private $qiNiuClient;

    public function init()
    {
        parent::init();

        if(is_string($this->qiNiuConfig)) $this->qiNiuClient = \Yii::$app->get($this->qiNiuConfig);

        if(is_array($this->qiNiuConfig)){
            isset($this->qiNiuConfig['class']) OR $this->qiNiuConfig['class'] = 'dcb9\qiniu\Component';
            $this->qiNiuClient = \Yii::createObject($this->qiNiuConfig);
        }

        if( ! ($this->qiNiuClient instanceof Component))
            throw  new InvalidValueException('the value of qiNiuClient is error !');

    }

    /**
     * @param $fileName
     * @param $localFile
     * @return bool
     */
    public function uploadFile($fileName,$localFile)
    {
        try{
            $token = $this->getUploadToken();
            $config = ['token' => $token];
            return $this->qiNiuClient->getDisk($this->diskName)->put($fileName,file_get_contents($localFile),$config);
        }catch(InvalidValueException $e){
            $_errorMessage = $e->getMessage();
            \yii::warning("文件上传失败 \$\$fleName={$fileName} error={$_errorMessage}");
            return false;
        }
    }

    /**
     * @param $remoteDir
     * @param $localDir
     * @param bool $recursive
     * @return bool
     */
    public function uploadDir($remoteDir,$localDir,$recursive = true)
    {
        return true;
    }

    /**
     * 获取上传文件时候的token
     * @return mixed
     */
    private function getUploadToken()
    {
        return $this->qiNiuClient->getUploadToken($this->diskName);
    }
}