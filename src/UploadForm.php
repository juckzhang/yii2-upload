<?php
namespace juckzhang;

use juckzhang\helpers\ConfigHelper;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Login form
 */
class UploadForm extends Model
{

    /**
     * @var 文件对象
     */
    public $file;

    /**
     * @var 文件上传目录前缀
     */
    private $path = '';

    /**
     * 文件访问url前缀
     * @var string
     */
    private $urlPrefix = '';

    private $remoteUpload = false;

    private $recursive = false;

    /**
     * 上传文件相关配置
     * @var array
     */
    private $_conf = [];

    public function init()
    {
        parent::init();
        $this->_conf = ConfigHelper::loadConfig('upload');

        //获取文件上传路径
        $scenario = $this->getScenario();
        $_conf = ArrayHelper::getValue($this->_conf,$scenario,[]);
        $this->path = ArrayHelper::getValue($_conf,'path','');
        $this->urlPrefix = ArrayHelper::getValue($_conf,'urlPrefix','');
        $this->remoteUpload = ArrayHelper::getValue($_conf,'remoteUpload',false);
        $this->recursive = ArrayHelper::getValue($_conf,'recursive',false);
    }

    public function scenarios()
    {
        $_scenarios = [];
        foreach($this->_conf as $key => $value){
            $_scenarios[$key] = ['file'];
        }
        return ArrayHelper::merge(parent::scenarios(), $_scenarios);
    }

    public function rules()
    {
        $_rules = [];
        foreach($this->_conf as $key => $item)
        {
            $_itemRule = [
                ['file'],
                'file',
                'extensions' => ArrayHelper::getValue($item,'extensions'),
                'mimeTypes' => ArrayHelper::getValue($item,'mimeTypes'),
                'minSize' => ArrayHelper::getValue($item,'minSize'),
                'maxSize' => ArrayHelper::getValue($item,'maxSize'),
                'uploadRequired' => ArrayHelper::getValue($item,'uploadRequired'),
                'tooBig' => ArrayHelper::getValue($item,'tooBig'),
                'tooSmall' => ArrayHelper::getValue($item,'tooSmall'),
                'tooMany' => ArrayHelper::getValue($item,'tooMany'),
                'wrongExtension' => ArrayHelper::getValue($item,'wrongExtension'),
                'wrongMimeType' => ArrayHelper::getValue($item,'wrongMimeType'),
                'on' => $key,
            ];
            $_rules[] = $_itemRule;
        }
        return $_rules;
    }

    public function getPath()
    {
        return rtrim($this->path,'/\\');
    }

    public function getUrlPrefix()
    {
        return rtrim($this->urlPrefix,'/\\') . '/';
    }

    public function getRemoteUpload()
    {
        return  (bool)$this->remoteUpload;
    }

    public function getRecursive()
    {
        return (is_int($this->recursive) && $this->recursive > 0) ? $this->recursive : false;
    }
}
