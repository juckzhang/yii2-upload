<?php
namespace juckzhang;

use juckzhang\helpers\ConfigHelper;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * 文件上传服务
 * Class UploadService
 * @package common\services
 */
class UploadService extends Component
{
    /**
     * 文件上传场景
     * @var array
     */
    private $fileScenarios = [];

    /**
     * 文件上传服务实例
     * @var null
     */
    private static $_instance = null;
    public function init()
    {
        //獲取文件上傳配置信息
        $conf = ConfigHelper::loadConfig('upload');
        foreach($conf as $key => $item)
        {
            $this->fileScenarios[] = $key;
        }
        parent::init(); // TODO: Change the autogenerated stub
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function getService(){
        if(static::$_instance === null OR !(static::$_instance instanceof UploadService))
        {
            $_className = static::className();
            static::$_instance = \yii::createObject($_className);
        }

        return static::$_instance;
    }

    /**
     * 單文件上傳
     * @param string $fileType
     * @param string $fileName null
     * @param string $subDir
     * @return array|int|mixed
     * @throws Exception
     */
    public function upload($fileType,$fileName = null,$subDir = '')
    {
        if(! in_array($fileType,$this->fileScenarios))
            throw  new Exception($fileType . ' 配置信息不存在!');

        $model = new UploadForm(['scenario' => $fileType]);
        $model->file = UploadedFile::getInstance($model, 'file');

        if($model->file === null) throw Exception('上传文件不存在!');
        if ($model->validate()) {
            if($model->remoteUpload === true) return $this->saveToRemote($model,$fileName,$subDir);
            else return $this->saveAs($model,$fileName,$subDir); //本地上传
        }else {
            //不是一个文件
            return current(current($model->getErrors()));
        }
    }

    /**
     * 多文件上傳
     * @param $fileType
     * @param string $fileName null
     * @param string $path
     * @return array|int|mixed
     * @throws Exception
     */
    public function multiUpload($fileType,$fileName = null,$path = '')
    {
        if(! in_array($fileType,$this->fileScenarios))
            throw  new Exception($fileType . ' 配置信息不存在!');

        $model = new UploadForm(['scenario' => $fileType]);

        $files = UploadedFile::getInstances($model, 'file');

        if($files === null)  throw Exception('上传文件不存在!');

        if ($model->validate()) {
            $_return = [];
            foreach($files as $file)
            {
                $model->file = $file;
                if($model->remoteUpload === true) $_return[] =  $this->saveToRemote($model,$fileName,$path);
                else $_return[] =  $this->saveAs($model,$fileName,$path); //本地上传
            }
            return $_return;

        }else {
            //不是一个文件
            return current(current($model->getErrors()));
        }
    }

    /**
     * 上传到云盘
     * @param UploadForm $model
     * @param string $fileName null
     * @param string $subDirStr
     * @return array|int
     */
    private function saveToRemote(UploadForm $model,$fileName,$subDirStr = '')
    {
        if($subDirStr !== '') $subDirStr = trim($subDirStr,DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        //递归创建目录
        if($model->getRecursive())
            $subDirStr .= $this->createSubDirString($model->recursive);

        //获取完整文件名
        if($subDirStr !== '') $subDirStr = trim($subDirStr,DIRECTORY_SEPARATOR) .DIRECTORY_SEPARATOR;
        if($fileName !== null)
            $fullFileName = $model->getPath() . DIRECTORY_SEPARATOR  . $subDirStr .$fileName . '.'.$model->file->extension;
        else
            $fullFileName = $model->getPath() . DIRECTORY_SEPARATOR  . $subDirStr . $model->file->baseName . '.' . $model->file->extension;

        if($model->file->saveToRemote($fullFileName))
            return [
                'code' => 200,
                'data' => [
                    'url' => $model->getUrlPrefix() . $fullFileName,
                    'fullFileName' => $fullFileName,
                    'type' => $model->file->type,
                    'size' => $model->file->size,
                    'width' => $model->file->width,
                    'height' => $model->file->height,
                ]
            ];


        return ['code' => 400,'message' => '文件上传失败'];
    }

    /**
     * 上传到本地
     * @param UploadForm $model
     * @param string $fileName null
     * @param string $subDirStr
     * @return array
     */
    private function saveAs(UploadForm $model,$fileName = null,$subDirStr = '')
    {
        if($subDirStr !== '') $subDirStr = trim($subDirStr,DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        //递归创建目录
        if($model->getRecursive())
        {
            $subDirStr .= $this->createSubDirString($model->recursive);
            //创建子目录
            mkdir($model->getPath() . DIRECTORY_SEPARATOR .$subDirStr,0755,true);
        }
        //获取完整文件名
        if($subDirStr !== '') $subDirStr = trim($subDirStr,DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if($fileName !== null)
        {
            $relativePath = $model->getPath() . DIRECTORY_SEPARATOR  . $subDirStr .$fileName . '.'.$model->file->extension;
            $fullFileName = \Yii::getAlias('@webroot/') . $relativePath;
        }
        else{
            $relativePath = $model->getPath() . DIRECTORY_SEPARATOR  . $subDirStr . $model->file->baseName . '.' . $model->file->extension;
            $fullFileName = \Yii::getAlias('@webroot/') . $relativePath;
        }

        if($model->file->saveAs($fullFileName))
            return [
                'code' => 200,
                'data' => [
                    'url' => $model->getUrlPrefix() . $fullFileName,
                    'fullFileName' => $fullFileName,
                    'type' => $model->file->type,
                    'size' => $model->file->size,
                    'width' => $model->file->width,
                    'height' => $model->file->height,
                ]
            ];

         return ['code' => 400,'message' => '文件上传失败'];
    }

    /**
     * 生成子目录
     * @param bool|false $recursive
     * @return string
     */
    private function createSubDirString($recursive = false)
    {
        $subDir = '';
        if(is_int($recursive) && $recursive > 0)
        {
            for($i = 0; $i < $recursive; ++$i)
            {
                $subDir .= ConfigHelper::randString(6) . DIRECTORY_SEPARATOR;
            }
        }
        return trim($subDir ,DIRECTORY_SEPARATOR);
    }
}