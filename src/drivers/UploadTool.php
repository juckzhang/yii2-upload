<?php
namespace juckzhang\drivers;

use yii\base\Component;
use yii\base\InvalidConfigException;

class UploadTool extends Component{

    /**
     * @var
     */
    public $handler;

    public function init()
    {
        if(is_string($this->handler))
            $this->handler = \yii::$app->get($this->handler);

        if(is_array($this->handler))
            $this->handler = \yii::createObject($this->handler);

        if( ! ($this->handler instanceof UploadYunInterface))
            throw new InvalidConfigException('config for upload handler error');

    }

    /**
     * @param $fileName
     * @param $localFile
     */
    public function uploadFile($fileName,$localFile)
    {
        return $this->handler->uploadFile($fileName,$localFile);
    }

    /**
     * 上传本地目录到远程
     * @param $remoteDir
     * @param $localDir
     * @param bool|true $recursive
     * @return mixed
     */
    public function uploadDir($remoteDir,$localDir,$recursive = true)
    {
        return $this->handler->uploadDir($remoteDir,$localDir,$recursive);
    }
}