<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Sms;

use Seffeng\Sms\Exceptions\SmsException;
use Seffeng\Sms\Clients\Aliyun\TemplateParams;

/**
 * 短信服务
 * @author zxf
 * @date    2019年11月21日
 */
class SmsClient
{
    /**
     * 阿里云AccessKeyId 或 腾讯云SecretId
     * @var string
     */
    private $accessKeyId;

    /**
     * 阿里云AccessKeySecret 或 腾讯云SecretKey
     * @var string
     */
    private $accessSecret;

    /**
     * 腾讯云SmsSdkAppid
     * @var string
     */
    private $sdkAppId;

    /**
     *
     * @var \Seffeng\Sms\Clients\Aliyun\Client|\Seffeng\Sms\Clients\Qcloud\Client
     */
    private $client;

    /**
     *
     * @var string
     */
    private $signName;

    /**
     *
     * @var string
     */
    private $templateCode;

    /**
     *
     * @var array
     */
    private $allowClients = ['aliyun', 'qcloud'];

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @param string $accessKeyId
     * @param string $accessSecret
     * @param string $sdkAppId
     */
    public function __construct(string $accessKeyId, string $accessSecret, string $sdkAppId = '')
    {
        $this->accessKeyId = $accessKeyId;
        $this->accessSecret = $accessSecret;
        $this->sdkAppId = $sdkAppId;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @param  string $client
     * @param  TemplateParams $templateParamsModel
     * @throws SmsException
     * @return \Seffeng\Sms\SmsClient
     */
    public function setClient(string $client, TemplateParams $templateParamsModel = null)
    {
        $client = strtolower($client);
        $this->checkClient($client);

        $class = '\\Seffeng\\Sms\\Clients\\'. ucfirst($client) .'\\Client';
        $this->client = new $class;

        $this->client->setClient($this->accessKeyId, $this->accessSecret, $this->sdkAppId);
        if ($client === 'aliyun' && !is_null($templateParamsModel)) {
            $this->client->setTemplateParamsModel($templateParamsModel);
        }
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2020年6月11日
     * @param  string $client
     * @throws SmsException
     * @return boolean
     */
    public function checkClient(string $client)
    {
        if (!in_array($client, $this->allowClients)) {
            throw new SmsException('暂不支持该短信服务商！['. $client .']');
        }
        return true;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月21日
     * @param  string|array $phone
     * @param  string|array $content
     * @throws SmsException
     * @throws \Exception
     * @return boolean
     */
    public function send($phone, array $content)
    {
        try {
            if (is_null($this->client)) {
                throw new SmsException('非允许的短信服务商！');
            }
            if (!$this->validPhone($phone)) {
                throw new SmsException('手机号格式错误！');
            }
            return $this->client->setSignName($this->getSignName())->setTemplateCode($this->getTemplateCode())->send($phone, $content);
        } catch (SmsException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年11月25日
     * @param  string|array $phone
     * @return boolean|integer
     */
    public function validPhone($phone)
    {
        $regex = '/^1\d{10}$/';
        if (is_array($phone)) {
            foreach ($phone as $val) {
                if (!preg_match($regex, $val)) {
                    return false;
                }
            }
            return true;
        } else {
            return preg_match($regex, $phone);
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年11月21日
     * @param  string $signName
     * @return \Seffeng\Sms\SmsClient
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
     * @return \Seffeng\Sms\SmsClient
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
}
