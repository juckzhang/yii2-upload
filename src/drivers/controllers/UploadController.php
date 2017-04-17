<?php
namespace juckzhang\controllers;

use Yii;
use juckzhang\UploadService;
use yii\helpers\ArrayHelper;
/**
 * Site controller
 */
class UploadController extends \yii\web\Controller
{
    /**
     * 文件上传
     * @return string
     */
    public function actionUploadFile()
    {
        $type = ArrayHelper::getValue($this->paramData,'type');

        $result = UploadService::getService()->upload($type);

        if(is_array($result))
            return $this->returnSuccess($result);

        return $this->returnError($result);
    }
}
