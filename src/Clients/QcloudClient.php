<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Sms\Clients;

use Seffeng\Sms\Exceptions\SmsException;
use Qcloud\Sms\SmsSingleSender;

class QcloudClient
{
    /**
     *
     * @var string
     */
    private $appid;

    /**
     *
     * @var string
     */
    private $secret;

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
     * @var mixed
     */
    private $client;

    /**
     *
     * @author zxf
     * @date    2019年11月21日
     * @param string $appid
     * @param string $secret
     * @return QcloudClient
     */
    public function setClient(string $appid, string $secret)
    {
        $this->client = new SmsSingleSender($appid, $secret);
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月21日
     * @param string $phone
     * @param array $content
     * @throws SmsException
     * @throws \Exception
     * @return boolean
     */
    public function send(string $phone, array $content)
    {
        try {
            $result = $this->client->sendWithParam('86', $phone, $this->getTemplateCode(), $content, $this->getSignName());
            $content = json_decode($result, true);
            $code = isset($content['result']) ? $content['result'] : 0;
            if ($code > 0) {
                $message = isset($content['errmsg']) ? $content['errmsg'] : '短信发送失败！';
                $ext = isset($content['ext']) ? $content['ext'] : '';
                throw new SmsException($message . '['. $code .']'. ($ext ? ('['. $ext .']') : ''));
            }
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年11月21日
     * @param string $signName
     * @return QcloudClient
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
     * @return QcloudClient
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
