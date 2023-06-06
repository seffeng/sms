<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Sms\Clients\Qcloud;

use Seffeng\Sms\Exceptions\SmsException;
use GuzzleHttp\Client as HttpClient;
use Seffeng\Sms\Helpers\ArrayHelper;
use GuzzleHttp\Exception\RequestException;

class Client
{
    /**
     *
     * @var string
     */
    private $scheme = 'https://';

    /**
     *
     * @var string
     */
    private $host = 'sms.tencentcloudapi.com';

    /**
     *
     * @var string
     */
    private $sdkAppId;

    /**
     *
     * @var string
     */
    private $secretId;

    /**
     *
     * @var string
     */
    private $secretKey;

    /**
     *
     * @var string
     */
    private $authorization;

    /**
     *
     * @var string
     */
    private $regionId;

    /**
     *
     * @var string
     */
    private $signName;

    /**
     *
     * @var string
     */
    private $signature;

    /**
     *
     * @var string
     */
    private $signatureMethod;

    /**
     *
     * @var string
     */
    private $signatureNonce;

    /**
     *
     * @var string
     */
    private $templateCode;

    /**
     *
     * @var string
     */
    private $timestamp;

    /**
     *
     * @var string
     */
    private $version = '2019-07-11';

    /**
     *
     * @var HttpClient
     */
    private $client;

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @param  string $secretId
     * @param  string $secretKey
     * @param  string $sdkAppId
     * @return Client
     */
    public function setClient(string $secretId, string $secretKey, string $sdkAppId)
    {
        $this->setSdkAppId($sdkAppId)->setSecretId($secretId)->setSecretKey($secretKey);
        $this->client = new HttpClient(['base_uri' => $this->getscheme() . $this->getHost()]);
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月21日
     * @param  string|array $phone
     * @param  array $content
     * @throws SmsException
     * @throws \Exception
     * @return boolean
     */
    public function send($phone, array $content)
    {
        try {
            $params = $this->getParams($phone, $content);
            $this->setSignature($params);

            $request = $this->client->post('/', [
                'headers' => $this->getHeaders(),
                'json' => $params,
            ])->getBody()->getContents();

            $content = json_decode($request, true);
            $response = ArrayHelper::getValue($content, 'Response');
            $error = ArrayHelper::getValue($response, 'Error');

            if ($error) {
                $errorCode = ArrayHelper::getValue($error, 'Code');
                $errorItem = new Error($errorCode);
                $message = $errorItem->getName();
                $message === '' && $message = ArrayHelper::getValue($error, 'Message', '短信发送失败！') .'['. ArrayHelper::getValue($error, 'Code') .']';
                throw new SmsException($message);
            }

            $sendStatusSet = ArrayHelper::getValue($response, 'SendStatusSet.0');
            $errorCode = ArrayHelper::getValue($sendStatusSet, 'Code');
            if ($errorCode && $errorCode === 'Ok') {
                return true;
            }

            $errorItem = new Error($errorCode);
            $message = $errorItem->getName();
            $message === '' && $message = ArrayHelper::getValue($sendStatusSet, 'Message', '短信发送失败！') .'['. ArrayHelper::getValue($sendStatusSet, 'Code') .']';
            throw new SmsException($message);
        } catch (RequestException $e) {
            $message = $e->getResponse()->getBody()->getContents();
            if (!$message) {
                $message = $e->getMessage();
            }
            throw new SmsException($message);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return string[]|integer[]
     */
    public function getHeaders()
    {
        return [
            'Host'           => $this->getHost(),
            'X-TC-Action'    => 'SendSms',
            'X-TC-Timestamp' => $this->getTimestamp(),
            'X-TC-Version'   => $this->getVersion(),
            'Content-Type'   => 'application/json',
            'Authorization'  => $this->getAuthorization(),
        ];
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @param  string|array $phone
     * @param  array $content
     * @return array
     */
    public function getParams($phone, array $content)
    {
        if (is_array($phone)) {
            $phones = [];
            foreach ($phone as $val) {
                $phones[] = '+86'. $val;
            }
        } else {
            $phones = ['+86'. $phone];
        }
        return [
            'PhoneNumberSet' => $phones,
            'TemplateID' => $this->getTemplateCode(),
            'SmsSdkAppid' => $this->getSdkAppId(),
            'Sign' => $this->getSignName(),
            'TemplateParamSet' => $content,
        ];
    }

    /**
     *
     * @author zxf
     * @date    2019年11月22日
     * @param string $appId
     * @return Client
     */
    public function setSdkAppId(string $sdkAppId)
    {
        $this->sdkAppId = $sdkAppId;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return string
     */
    public function getSdkAppId()
    {
        return $this->sdkAppId;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月22日
     * @return string
     */
    public function getscheme()
    {
        return $this->scheme;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月22日
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月22日
     * @param string $appSecret
     * @return Client
     */
    public function setSecretId(string $secretId)
    {
        $this->secretId = $secretId;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月22日
     * @return string
     */
    public function getSecretId()
    {
        return $this->secretId;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @param  string $secretKey
     * @return Client
     */
    public function setSecretKey(string $secretKey)
    {
        $this->secretKey = $secretKey;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月22日
     * @return Client
     */
    public function setRegionId(string $regionId)
    {
        $this->regionId = $regionId;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月22日
     * @return string
     */
    public function getRegionId()
    {
        return $this->regionId;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月21日
     * @param string $signName
     * @return Client
     */
    public function setSignName(string $signName)
    {
        $this->signName = $signName;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月21日
     * @return string
     */
    public function getSignName()
    {
        return $this->signName;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月21日
     * @param string $templateCode
     * @return Client
     */
    public function setTemplateCode(string $templateCode)
    {
        $this->templateCode = $templateCode;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月21日
     * @return string
     */
    public function getTemplateCode()
    {
        return $this->templateCode;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月22日
     * @param  string $version
     * @return Client
     */
    public function setVersion(string $version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月22日
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月22日
     * @return string
     */
    public function getDateTime()
    {
        return gmdate('Y-m-d', $this->getTimestamp());
    }

    /**
     *
     * @author zxf
     * @date    2019年11月22日
     * @return Client
     */
    public function setTimestamp()
    {
        $this->timestamp = time();
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月22日
     * @return integer
     */
    public function getTimestamp()
    {
        if (is_null($this->timestamp)) {
            $this->setTimestamp();
        }
        return $this->timestamp;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月22日
     * @return Client
     */
    public function setAuthorization()
    {
        $this->authorization = 'TC3-HMAC-SHA256 Credential='. $this->getSecretId() .'/'. $this->getDateTime() .'/sms/tc3_request, SignedHeaders=content-type;host, Signature='. $this->getSignature();
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月22日
     * @return string
     */
    public function getAuthorization()
    {
        if (is_null($this->authorization)) {
            $this->setAuthorization();
        }
        return $this->authorization;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月22日
     * @return Client
     */
    public function setSignature(array $params)
    {
        $canonicalRequest = "POST\n/\n\ncontent-type:application/json\nhost:sms.tencentcloudapi.com\n\ncontent-type;host\n". hash('SHA256', json_encode($params));
        $signature = "TC3-HMAC-SHA256\n". $this->getTimestamp() ."\n". $this->getDateTime() ."/sms/tc3_request\n". hash('SHA256', $canonicalRequest);

        $secretDate = hash_hmac('SHA256', $this->getDateTime(), 'TC3'. $this->getSecretKey(), true);
        $secretService = hash_hmac('SHA256', 'sms', $secretDate, true);
        $secretSigning = hash_hmac('SHA256', 'tc3_request', $secretService, true);
        $this->signature = hash_hmac('SHA256', $signature, $secretSigning);

        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月22日
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }
}
