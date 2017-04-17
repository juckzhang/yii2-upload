<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2016/1/14
 * Time: 13:51
 */

namespace juckzhang\drivers;

use yii\base\InvalidConfigException;

/**
 * [
 *     components => [
 *         'uploadTool' => 'ftp',
 *    ],
 * ]
 * OR
 * [
 *     components => [
 *         'uploadHandle' => [
 *                            'class' => 'gftp\FtpComponent',
 *                            'connectionString' => 'ftp://root:Zaizai123@123.56.160.211:21',
 *                            'driverOptions' => [
 *                            'timeout' => 30,
 *                         ],
 *    ],
 * ]
 * Class UploadFtp
 * @package common\components\uploadYun
 */
class UploadFtp extends UploadYunInterface
{
    public $uploadHandle = null;

    public function init()
    {
        parent::init();

        if( is_string($this->uploadHandle))
            $this->uploadHandle = \yii::$app->get($this->uploadHandle);

        if( is_array($this->uploadHandle))
            $this->uploadHandle = \yii::createObject($this->uploadHandle);

        if($this->uploadHandle === null)
            throw new InvalidConfigException('config for uploadTool is error');
    }

    /**
     * @param $bucket
     * @param $fileName
     * @param $localFile
     * @return bool
     */
    public function uploadFile($fileName,$localFile,$bucket = null)
    {
        try{
            $this->uploadHandle->put($localFile, $fileName,FTP_BINARY);
            return true;
        }catch (InvalidConfigException $e) {
            \yii::warning("文件上传失败 fileName={$fileName} and localFile={$localFile}");
            return false;
        }
    }

    /**
     * 暂不提供该功能
     * @param $remoteDir
     * @param $localDir
     * @param bool|true $recursive
     * @param null $bucket
     * @throws InvalidConfigException
     */
    public function uploadDir($remoteDir,$localDir,$recursive = true,$bucket = null)
    {
        throw new InvalidConfigException();
    }
}