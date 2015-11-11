<?php
/*
* Copyright (c) 2014 Baidu.com, Inc. All Rights Reserved
*
* Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with
* the License. You may obtain a copy of the License at
*
* Http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on
* an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the
* specific language governing permissions and limitations under the License.
*/

error_reporting(-1);
date_default_timezone_set('UTC');

define('__LIVE_CLIENT_ROOT', dirname(__DIR__));

class LiveDefault {
    const BOSBUCKET  = "<你的bucket>";
    const USERDOMAIN = "<你绑定bucket的域名>";
    const PRESETNAME = "lss.rtmp_hls_forward_only";
}

$CUSTOM_LIVE_CONFIG =
    array(
        'credentials' => array(
            'ak' => '<ak>',
            'sk' => '<sk>',
        ),
        'endpoint' => 'http://lss.bj.baidubce.com',
    );

$STDERR = fopen('php://stderr', 'w+');
$__handler = new \Monolog\Handler\StreamHandler($STDERR, \Monolog\Logger::DEBUG);
$__handler->setFormatter(
    new \Monolog\Formatter\LineFormatter(null, null, false, true)
);
\BaiduBce\Log\LogFactory::setInstance(
    new \BaiduBce\Log\MonoLogFactory(array($__handler))
);
\BaiduBce\Log\LogFactory::setLogLevel(\Psr\Log\LogLevel::DEBUG);
