<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Sms;

use Seffeng\Sms\Exceptions\SmsException;

/**
 * 短信服务
 * @author zxf
 * @date    2019年11月21日
 */
class SmsClient
{
    /**
     *
     * @var string
     */
    private $appId;

    /**
     *
     * @var string
     */
    private $appSecret;

    /**
     *
     * @var \Seffeng\Sms\Clients\AliyunClient|\Seffeng\Sms\Clients\QcloudClient
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
     * @date    2019年11月21日
     * @param  string $appId
     * @param  string $appSecret
     */
    public function __construct(string $appId, string $appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月21日
     * @param string $client
     */
    public function setClient(string $client)
    {
        $client = strtolower($client);
        if (!in_array($client, $this->allowClients)) {
            throw new SmsException('非允许的短信服务商！');
        }
        $class = '\\Seffeng\\Sms\\Clients\\'. ucfirst($client) .'Client';
        $this->client = new $class;
        $this->client->setClient($this->appId, $this->appSecret);
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月21日
     * @param  string $phone
     * @param  string|array $content
     * @throws SmsException
     * @throws \Exception
     * @return boolean
     */
    public function send(string $phone, $content)
    {
        try {
            if (is_null($this->client)) {
                throw new SmsException('非允许的短信服务商！');
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
     * @date    2019年11月21日
     * @throws \Exception
     */
    public function batchSend()
    {
        try {
            echo 'batch send test';
        } catch (\Exception $e) {
            throw $e;
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
