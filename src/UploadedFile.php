<?php
namespace juckzhang;

use yii\helpers\FileHelper;

class UploadedFile extends \yii\web\UploadedFile
{
    protected $width = 0;
    protected $height = 0;

    public static function getUploadTool()
    {
        return \yii::$app->get('uploadTool');
    }
    /**
     * 阿里云图片上传
     * @param $fileName
     * @return bool
     */
    public function saveToRemote($fileName)
    {
        if ($this->error == UPLOAD_ERR_OK) {
            $_uploadHandle = static::getUploadTool();
            $_res = $_uploadHandle->uploadFile($fileName,$this->tempName);
            return $_res;
        }
        return false;
    }

    /**
     * Use the md5 string value to filename
     * @return string
     */
    public function getBaseName()
    {
        return md5(uniqid().time());
    }

    /**
     * @return mixed|string
     */
    public function getExtension()
    {
        $_extension = parent::getExtension();
        if($_extension != '') return $_extension;

        $_mimeType = mime_content_type($this->tempName);
        $_extensions = FileHelper::getExtensionsByMimeType($_mimeType);

        $_extension = array_pop($_extensions);
        return (string)$_extension;
    }

    /**
     * 获取图片宽
     * @return int
     */
    public function getWidth()
    {
        $_imageInfo = getimagesize($this->tempName);

        if( ! empty($_imageInfo[0]))
            $this->width = (int)$_imageInfo[0];

        return $this->width;
    }

    /**
     * 获取图片高
     * @return int
     */
    public function getHeight()
    {
        $_imageInfo = getimagesize($this->tempName);

        if( ! empty($_imageInfo[1]))
            $this->height = (int)$_imageInfo[1];

        return $this->height;
    }
}