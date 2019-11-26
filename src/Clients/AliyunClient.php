<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Sms\Clients;

use AlibabaCloud\Client\AlibabaCloud;
use Seffeng\Sms\Exceptions\SmsException;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class AliyunClient
{
    /**
     *
     * @var string
     */
    private $accessKeyId;

    /**
     *
     * @var string
     */
    private $accessKeySecret;

    /**
     *
     * @var string
     */
    private $signName;

    /**
     *
     * @var string
     */
    private $regionId = 'cn-hangzhou';

    /**
     *
     * @var string
     */
    private $templateCode;

    /**
     *
     * @author zxf
     * @date    2019年11月21日
     * @param string $accessKeyId
     * @param string $accessKeySecret
     * @return AliyunClient
     */
    public function setClient(string $accessKeyId, string $accessKeySecret)
    {
        AlibabaCloud::accessKeyClient($accessKeyId, $accessKeySecret)->regionId($this->regionId)->asDefaultClient();
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月21日
     * @param string $phone
     * @param array $content
     * @throws SmsException
     * @throws ClientException
     * @throws ServerException
     * @return boolean
     */
    public function send(string $phone, array $content)
    {
        try {
            $request = AlibabaCloud::rpc()->product('Dysmsapi')
            // ->scheme('https') // https | http
            ->version('2017-05-25')
            ->action('SendSms')
            ->method('POST')
            ->host('dysmsapi.aliyuncs.com')
            ->options([
                'query' => [
                    'RegionId' => $this->regionId,
                    'PhoneNumbers' => $phone,
                    'SignName' => $this->signName,
                    'TemplateCode' => $this->templateCode,
                    'TemplateParam' => json_encode($content),
                ],
            ])->request();
            $content = $request->toArray();
            $code = isset($content['Code']) ? $content['Code'] : 'Faild';
            if ($code === 'OK') {
                return true;
            }
            $message = isset($content['Message']) ? $content['Message'] : '短信发送失败！';
            throw new SmsException($message . '['. $code .']');
        } catch (ClientException $e) {
            throw $e;
        } catch (ServerException $e) {
            throw $e;
        }
    }

    /**
     *
     * @author zxf
     * @date    2019年11月21日
     * @param string $signName
     * @return AliyunClient
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
     * @return AliyunClient
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
