<?php
return [
    'advertisement' => [
        'extensions' => null,
        'mimeTypes'  => null,
        'minSize' => 1024,
        'maxSize' => 10 * 1048576,
        'uploadRequired' => '上传文件不存在!',
        'tooBig'  => '文件大小超过限制',
        'tooSmall' => '上传文件太小',
        'tooMany' => '上传文件数量超过限制',
        'wrongExtension' => '文件扩展名不支持',
        'wrongMimeType' => '文件mime-type不支持',
        'path'  => realpath(__DIR__ . '/../upload'),
        'urlPrefix'   => 'http://localhost',
        'remoteUpload' => true,
        'recursive' => false,
    ],
];