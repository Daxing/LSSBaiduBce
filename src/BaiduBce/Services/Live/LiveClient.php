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

namespace BaiduBce\Services\Live;

use BaiduBce\Auth\BceV1Signer;
use BaiduBce\Auth\SignOptions;
use BaiduBce\Bce;
use BaiduBce\BceClientConfigOptions;
use BaiduBce\BceBaseClient;
use BaiduBce\Exception\BceClientException;
use BaiduBce\Exception\BceServiceException;
use BaiduBce\Http\BceHttpClient;
use BaiduBce\Http\HttpHeaders;
use BaiduBce\Http\HttpContentTypes;
use BaiduBce\Http\HttpMethod;
use BaiduBce\Util\MimeTypes;
use BaiduBce\Util\HashUtils;
use BaiduBce\Util\HttpUtils;
use BaiduBce\Util\StringUtils;

class LiveClient extends BceBaseClient
{

    /**
     * @var \BaiduBce\Auth\SignerInterface
     */
    private $signer;
    private $httpClient;
    private $prefix = '/v3';

    /**
     * The BosClient constructor
     * 
     * @param array $config The client configuration
     */
    function __construct(array $config)
    {
        parent::__construct($config, 'live');
        $this->signer = new BceV1Signer();
        $this->httpClient = new BceHttpClient();
    }

    /**
     * create live session
     * @param array $body the post parameters
     * @author stephen@orangelab.cn 
     */
    public function createLiveSession($body, $options = array()) {
        list($config) = $this->parseOptions($options, 'config');
        return $this->sendRequest(
            HttpMethod::POST,
            array(
                'config' => $config,
                'body' => json_encode($body)
            ),
            '/live/session'
        );
    }

    /**
     * get live session
     * @param $sessionId the live session id
     * @author stephen@orangelab.cn 
     */
    public function getLiveSession($sessionId, $options = array()) {
        list($config) = $this->parseOptions($options, 'config');
        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config
            ),
            '/live/session/' . $sessionId
        );
    }
    
    /**
     * list live sessions
     * @param string $status the status of sessions, its value should one of READY/ONGOING/PAUSED
     * @author stephen@orangelab.cn 
     */
    public function listLiveSessions($status, $options = array()) {
        list($config) = $this->parseOptions($options, 'config');
        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config,
                'params' => array('status' => $status), 
            ),
            '/live/session/' . $sessionId
        );
    }
    /**
     * stop live session
     * @param $sessionId the live session id
     * @author stephen@orangelab.cn 
     */
    public function stopLiveSession($sessionId, $options = array()) {
        list($config) = $this->parseOptions($options, 'config');
        return $this->sendRequest(
            HttpMethod::PUT,
            array(
                'config' => $config,
                'params' => array('stop' => ''), 
            ),
            '/live/session/' . $sessionId
        );
    }   
    /**
     * delete live session
     * @param $sessionId the live session id
     * @author stephen@orangelab.cn 
     */
    public function deleteLiveSession($sessionId, $options = array()) {
        list($config) = $this->parseOptions($options, 'config');
        return $this->sendRequest(
            HttpMethod::DELETE,
            array(
                'config' => $config,
            ),
            '/live/session/' . $sessionId
        );
    }   
    /**
     * resume live session
     * @param $sessionId the live session id
     * @author stephen@orangelab.cn 
     */
    public function resumeLiveSession($sessionId, $options = array()) {
        list($config) = $this->parseOptions($options, 'config');
        return $this->sendRequest(
            HttpMethod::PUT,
            array(
                'config' => $config,
                'params' => array('resume' => ''), 
            ),
            '/live/session/' . $sessionId
        );
    }   

    /**
     * refresh live session
     * @param $sessionId the live session id
     * @author stephen@orangelab.cn 
     */
    public function refreshLiveSession($sessionId, $options = array()) {
        list($config) = $this->parseOptions($options, 'config');
        return $this->sendRequest(
            HttpMethod::PUT,
            array(
                'config' => $config,
                'params' => array('refresh' => ''), 
            ),
            '/live/session/' . $sessionId
        );
    }

    /**
     * Create HttpClient and send request
     * @param string $httpMethod The Http request method
     * @param array $varArgs The extra arguments
     * @param string $requestPath The Http request uri
     * @return mixed The Http response and headers.
     */
    private function sendRequest($httpMethod, array $varArgs, $requestPath = '/')
    {
        $defaultArgs = array(
            'config' => array(),
            'body' => null,
            'headers' => array(),
            'params' => array(),
        );

        $args = array_merge($defaultArgs, $varArgs);
        if (empty($args['config'])) {
            $config = $this->config;
        } else {
            $config = array_merge(
                array(),
                $this->config,
                $args['config']
            );
        }
        if (!isset($args['headers'][HttpHeaders::CONTENT_TYPE])) {
            $args['headers'][HttpHeaders::CONTENT_TYPE] = HttpContentTypes::JSON;
        }
        $path = $this->prefix . $requestPath;
        $response = $this->httpClient->sendRequest(
            $config,
            $httpMethod,
            $path,
            $args['body'],
            $args['headers'],
            $args['params'],
            $this->signer
        );

        $result = $this->parseJsonResult($response['body']);
        $result->metadata = $this->convertHttpHeadersToMetadata($response['headers']);
        return $result;
    }

}
