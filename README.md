Qiniu Ftp AliYun Extension for Yii2
=================

The Qiniu integration for the Yii framework

[![Build Status](https://travis-ci.org/juckzhang/yii2-upload.svg)](https://travis-ci.org/juckzhang/yii2-upload)
[![Code Climate](https://codeclimate.com/github/juckzhang/yii2-upload/badges/gpa.svg)](https://codeclimate.com/github/juckzhang/yii2-upload)
[![Issue Count](https://codeclimate.com/github/juckzhang/yii2-upload/badges/issue_count.svg)](https://codeclimate.com/github/juckzhang/yii2-upload)
[![Latest Stable Version](https://poser.pugx.org/juckzhang/yii2-upload/version)](https://packagist.org/packages/juckzhang/yii2-upload)
[![Total Downloads](https://poser.pugx.org/juckzhang/yii2-upload/downloads)](https://packagist.org/packages/juckzhang/yii2-upload)
[![License](https://poser.pugx.org/juckzhang/yii2-upload/license)](https://packagist.org/packages/juckzhang/yii2-upload)

Installation
--------------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require juckzhang/yii2-upload:*
```

or add

```json
"juckzhang/yii2-upload": "*"
```

to the `require` section of your composer.json.


Configuration
--------------------

To use this extension, simply add the following code in your application configuration:

#### [配置]
```php
/** QiNiu **/
return [
    'components' => [
      'uploadTool' => [
          'class' => 'juckzhang\drivers\UploadTool',
           'handler' => [
               'class' => 'juckzhang\drivers\UploadQiNiu',
               'diskName' => 'privateBucket',
               'config' => [
               'class' => 'dcb9\qiniu\Component',
               'accessKey' => 'YOUR ACCESSKEY',
               'secretKey' => 'YOUR SECRETKEY',
               'disks' => [
                   'privateBucket' => [
                       'bucket' => 'YOUR BUCKET',
                       'baseUrl' => 'http://of2iqabtp.bkt.clouddn.com/',
                       'isPrivate' => true,
                       'zone' => 'zone1', // 可设置为 zone0, zone1 @see \Qiniu\Zone
                   ],
               ],
           ],
      ],
    ],
]

/** OR AliYun **/
return [
    'components' => [
      'uploadTool' => [
          'class' => 'juckzhang\drivers\UploadTool',
          'handler' => [
              'class' => 'juckzhang\drivers\UploadAliYun',
              'accessKeyId' => 'YOUR ACCESSSKEYID',
              'accessKeySecret' => 'ACCESSKEYSECRET',
              'bucket' => 'test-zaizaitv-upload',
              'endPoint' => 'http://test-zaizaitv-upload.oss-cn-beijing.aliyuncs.com',
          ],
      ],
    ],
]

/** OR Ftp **/
return [
    'components' => [
      'uploadTool' => [
          'class' => 'juckzhang\drivers\UploadTool',
          'handler' => [
              'class' => 'juckzhang\drivers\UploadFtp',
              'config' => [
                  'class' => 'gftp\FtpComponent',
                  'connectionString' => 'ftp://USERNAME:PASSWORD@HOST:PORT',
                  'driverOptions' => [
                      'timeout' => 30,
                  ],
              ],
          ],
      ],
    ],
  ]

```

#### [使用1]
```php
\Yii::$app->get('uploadTool')->uploadFile($remoteFileName,$localFileName);
```

#### [使用2]
```php
/**单文件上传**/
juckzhang\UploadService::getService()->upload($sceneType);

/**多文件上传**/
juckzhang\UploadService::getService()->multiUpload($sceneType);
```

#### [控制器中使用]
```php
    'controllerMap' => [
        'upload' => [
            'class' => 'juckzhang\controllers\UploadController',
        ],
    ],
```

Tricks
--------------------

* 给配置的组件加 IDE 自动补全 [IDE autocompletion for custom components](https://github.com/samdark/yii2-cookbook/blob/master/book/ide-autocompletion.md)
