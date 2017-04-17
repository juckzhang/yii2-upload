<?php
namespace juckzhang\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\Response;
/**
 * Site controller
 */
class UploadController extends Controller
{
    const UPLOAD_SUCCESS = 200;
    const UPLOAD_FAILED  = 450;
    /**
     * 单文件上传
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

    /**
     * 多文件上传
     * @return array
     */
    public function actionUploadFiles()
    {
        $type = ArrayHelper::getValue($this->paramData,'type');
        $result = UploadService::getService()->multiUpload($type);

        if(is_array($result))
            return $this->returnSuccess($result);

        return $this->returnError($result);
    }

    /**
     * 上传成功
     * @param $result
     * @return array
     */
    protected function returnSuccess($result)
    {
        return $this->returnResult(static::UPLOAD_SUCCESS,$result);
    }

    /**
     * 上传失败
     * @param $message
     * @return array
     */
    protected function returnError($message)
    {
        return $this->returnResult(static::UPLOAD_FAILED,$message);
    }

    /**
     * 返回结果
     * @param $code
     * @param $data
     * @return array
     */
    private function returnResult($code,$data)
    {
        if($code == static::UPLOAD_SUCCESS){
            $return = ['code' => $code,'data' => $data];
        }else{
            $return = ['code' => $code,'message' => $data];
        }
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        return $return;
    }
}
