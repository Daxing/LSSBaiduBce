<?php
/*
* Copyright (c) 2014 Baidu.com, Inc. All Rights Reserved
*
* Licensed under the Apache License, Version 2.0 (the "License"); you may not
* use this file except in compliance with the License. You may obtain a copy of
* the License at
*
* Http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
* WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
* License for the specific language governing permissions and limitations under
* the License.
*/

include 'BaiduBce.phar';
require 'live_config.php';

use BaiduBce\BceClientConfigOptions;
use BaiduBce\Util\Time;
use BaiduBce\Util\MimeTypes;
use BaiduBce\Http\HttpHeaders;
use BaiduBce\Services\Live\LiveClient;
use BaiduBce\Auth\SignOptions;

use BaiduBce\Log\LogFactory;

class LiveClientApp {
    private static $instance;
    private $client;
    private $bosBucket;

    private function __construct() {
        global $CUSTOM_LIVE_CONFIG;
        $this->client = new LiveClient($CUSTOM_LIVE_CONFIG);
        $this->bosBucket = LiveDefault::BOSBUCKET;
    }

    public function __destruct() {}

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * create custom live session
     * @param $presetName the name of session template 
     * @param $bosBucket the bos bucket to bind the session
     * @param $userDomain the domain user binded to the bos bucket
     * @author stephen@orangelab.cn
     */
    public function createLiveSession($presetName = LiveDefault::PRESETNAME, 
                                $bosBucket  = LiveDefault::BOSBUCKET, 
                                $userDomain = LiveDefault::USERDOMAIN) {
        $body = [
            'description' => "ol_".time(),
            "target" => [ 
                "bosBucket" => $bosBucket,
                "userDomain" => $userDomain 
            ],
            "presetName" => $presetName,
            "publish" => [
                "pushAuth" => false
            ],
            //"notification" => "live_notification"
        ];

        return $this->client->createLiveSession($body);
    }

    /**
     * get live session
     * @param $sessionId the live session id
     * @author stephen@orangelab.cn 
     */
    public function getLiveSession($sessionId) {
       return $this->client->getLiveSession($sessionId); 
    }

    /**
     * list live sessions
     * @param string $status the status of sessions, its value should one of READY/ONGOING/PAUSED
     * @author stephen@orangelab.cn 
     */
    public function listLiveSessions($status) {
       return $this->client->listLiveSessions($status); 
    }

    /**
     * stop live session
     * @param $sessionId the live session id
     * @author stephen@orangelab.cn 
     */
    public function stopLiveSession($sessionId) {
        return $this->client->stopLiveSession($sessionId);
    }

    /**
     * delete live session
     * @param $sessionId the live session id
     * @author stephen@orangelab.cn 
     */
    public function deleteLiveSession($sessionId) {
        return $this->client->deleteLiveSession($sessionId);
    }

    /**
     * resume live session
     * @param $sessionId the live session id
     * @author stephen@orangelab.cn 
     */
    public function resumeLiveSession($sessionId) {
        return $this->client->resumeLiveSession($sessionId);
    }

    /**
     * refresh live session
     * @param $sessionId the live session id
     * @author stephen@orangelab.cn 
     */
    public function refreshLiveSession($sessionId) {
        return $this->client->refreshLiveSession($sessionId);
    }
}

function testLiveApp() {
    echo "---------  START -----------\n";
    $liveApp = LiveClientApp::getInstance();
    //$ret = $liveApp->createLiveSession();
    $ret = $liveApp->createLiveSession();
    $sessionId = $ret->sessionId; 
    echo "---------TEST get-----------\n";
    $ret = $liveApp->getLiveSession($sessionId);
    echo "---------TEST stop-----------\n";
    $ret = $liveApp->stopLiveSession($sessionId);
    echo "---------TEST resume-----------\n";
    $ret = $liveApp->resumeLiveSession($sessionId);
    echo "---------TEST refresh-----------\n";
    $ret = $liveApp->refreshLiveSession($sessionId);
    echo "---------TEST delete-----------\n";
    $ret = $liveApp->deleteLiveSession($sessionId);
    echo "---------   END   -----------\n";
}
testLiveApp();

?>
