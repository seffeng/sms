<?php  declare(strict_types=1);

namespace Seffeng\Sms;

use Seffeng\Sms\SmsClient;
use Seffeng\Sms\Exceptions\SmsException;
use PHPUnit\Framework\TestCase;

class SmsTest extends TestCase
{
    public function testSend()
    {
        try {
            $sdkAppId = '';             // 腾讯云SDKAppID  阿里云不需要
            $appSecretId = '';          // 腾讯云SecretId 或 阿里云 AccessKeyId
            $appSecretKey = '';         // 腾讯云SecretKey 或 阿里云 AccessKeySecret
            $name = 'qcloud';           // qcloud 或 aliyun
            $signName = '签名';         // 签名内容
            $tempCode = '1234'; // 腾讯云 templateId[1234] 或 阿里云 TemplateCode[SMS_153055065]
            $content = ['111111'];      // 腾讯云 ['111111'] 或 阿里云 ['code' => '111111']
            $phone = '13800138000';     // 相同内容可批量发送['13800138000', '13800138001']

            // 因阿里云与腾讯云的内容参数结构不一致，参考 $content；可通过 TemplateParams 实现以腾讯云结构发送
            $stdTemplateParams = new TemplateParams();
            $client = new SmsClient($appSecretId, $appSecretKey, $sdkAppId);
            $result = $client->setClient($name, $stdTemplateParams)
            ->setSignName($signName)
            ->setTemplateCode($tempCode)
            ->send($phone, $content);

            if ($result) {
                echo '发送成功！';
            } else {
                echo '发送失败！';
            }
        } catch (SmsException $e) {
            echo $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}

class TemplateParams extends \Seffeng\Sms\Clients\Aliyun\TemplateParams
{
    /**
     * 重写模板对应参数
     * @return array
     */
    public static function fetchNameItems()
    {
        return [
            'SMS_153055065' => ['code'],
            'SMS_153055066' => ['code', 'address'],
        ];
    }
}
