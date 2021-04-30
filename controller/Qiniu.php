<?php

namespace app\service\controller;

use beacon\core\Config;
use beacon\core\Controller;
use beacon\core\Method;
use Qiniu\Auth;

class Qiniu extends Controller
{

    #[Method(act: 'auth', method: Method::POST, contentType: 'json')]
    public function auth(): array
    {
        $bucket = Config::get('qiniu.bucket');
        $accessKey = Config::get('qiniu.access_key');
        $secretKey = Config::get('qiniu.secret_key');
        $domain = Config::get('qiniu.domain', 'http://rwxf.qiniudn.com/');
        $domain = trim($domain, '/') . '/';
        $attr['data-domain'] = $domain;
        $ret = [
            'data' => [
                'url' => $attr['data-domain'] . '$(key)',
                'orgName' => '$(x:name)',
                'localName' => '$(x:name)',
                'shortUrl' => '$(key)',
            ],
            'status' => true,
            'msg' => '上传成功'
        ];
        $returnBody = json_encode($ret, JSON_UNESCAPED_UNICODE);
        $policy = array(
            'returnBody' => $returnBody
        );
        $auth = new Auth($accessKey, $secretKey);
        $upToken = $auth->uploadToken($bucket, null, 60, $policy);
        return ['token' => $upToken];
    }

}