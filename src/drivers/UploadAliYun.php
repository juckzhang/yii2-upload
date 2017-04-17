<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2016/1/14
 * Time: 13:51
 */

namespace juckzhang\drivers;

use OSS\OssClient;
use OSS\Core\OssException;
use yii\base\InvalidConfigException;

class UploadAliYun extends UploadYunInterface
{
    /**
     * opendId
     * @var
     */
    public $accessKeyId;

    /**
     * key
     * @var
     */
    public $accessKeySecret;

    /**
     * @var string
     */
    public $bucket = '';

    /**
     * @var
     */
    public $endPoint = '';

    public $isCName = false;

    public $securityToken = null;

    public $ossClient = null;

    public function init()
    {
        parent::init();

        if( empty($this->accessKeyId))
            throw new InvalidConfigException("Ali yun config error for accessKeyId");

        if( empty($this->accessKeySecret) )
            throw new InvalidConfigException("Ali yun config error for accessKeySecret");

        if( empty($this->bucket))
            throw new InvalidConfigException("Ali yun config error for bucket");

        if( ! ($this->ossClient instanceof OssClient))
            $this->ossClient = new OssClient($this->accessKeyId,$this->accessKeySecret,
                $this->endPoint,$this->isCName,$this->securityToken);
    }

    /**
     * @param $fileName
     * @param $localFile
     * @return bool
     */
    public function uploadFile($fileName,$localFile)
    {
        $bucket = $this->bucket;
        try{
            $this->ossClient->uploadFile($bucket,$fileName,$localFile);
            return true;
        }catch(OssException $e){
            $_errorMessage = $e->getMessage();
            \yii::warning("文件上传失败 \$bucket={$bucket} and \$fleName={$fileName} error={$_errorMessage}");
            return false;
        }
    }

    /**
     * 上传本地目录到云服务器
     * @param $remoteDir
     * @param $localDir
     * @param bool|true $recursive
     * @return bool
     */
    public function uploadDir($remoteDir,$localDir,$recursive = true)
    {
        $bucket = $this->bucket;
        try{
            $_result = $this->ossClient->uploadDir($bucket, $remoteDir, $localDir, '.|..|.svn|.git|.xls|.db|.txt', $recursive);
            return $_result;
        }catch(OssException $e){
            $_errorMessage = $e->getMessage();
            \yii::warning("上传目录失败 \$bucket={$bucket} and \$fleName={$localDir} error={$_errorMessage}");
            return false;
        }

    }

    /**
     * @param $fileName
     * @param null $localFile
     * @param null $bucket
     * @return bool
     */
    public function getObject($fileName,$localFile = null,$bucket = null)
    {
        $bucket = $bucket == null ? $this->bucket : $bucket;
        try{
            if($localFile !== null ) $options[OssClient::OSS_FILE_DOWNLOAD] = \Yii::getAlias($localFile);
            $_res = $this->ossClient->getObject($bucket, $fileName, $options);
            return $_res;
        }catch(OssException $e){
            $_errorMessage = $e->getMessage();
            \yii::warning("上传目录失败 \$bucket={$bucket} and \$fleName={$fileName} error={$_errorMessage}");
            return false;
        }
    }
}