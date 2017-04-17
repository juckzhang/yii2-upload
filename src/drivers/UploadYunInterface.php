<?php
namespace juckzhang\drivers;
use yii\base\Component;

/**
 * Created by PhpStorm.
 * User: chao
 * Date: 2016/1/14
 * Time: 13:47
 */
abstract class UploadYunInterface extends Component
{
    /**
     * 上传文件到云服务器
     * @param $fileName
     * @param $localFile
     * @return mixed
     */
    abstract public function uploadFile($fileName,$localFile);

    abstract public function uploadDir($remoteDir,$localDir,$recursive);
}