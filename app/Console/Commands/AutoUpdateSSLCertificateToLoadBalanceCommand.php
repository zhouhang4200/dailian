<?php

namespace App\Console\Commands;


use Redis;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Core\Regions\Endpoint;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\Regions\EndpointConfig;
use Aliyun\Core\Regions\EndpointProvider;
use Aliyun\SLB\Request\DeleteLoadBalancerRequest;
use Aliyun\SLB\Request\UploadServerCertificateRequest;
use Aliyun\SLB\Request\SetLoadBalancerHTTPSListenerAttributeRequest;
use Illuminate\Console\Command;

/**
 * 自动更新ssl证书到负载均衡
 * Class AutoUpdateSSLCertificateToLoadBalance
 * @package App\Console\Commands
 */
class AutoUpdateSSLCertificateToLoadBalanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wanzi:AutoUpdateSSLCertificateToLoadBalance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自动更新ssl证书到负载均衡';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 配置网关
        $endpoint = new Endpoint('cn-hangzhou', EndpointConfig::getRegionIds(), EndpointConfig::getProductDomains());
        EndpointProvider::setEndpoints([$endpoint]);
        // 授权资料
        $profile = DefaultProfile::getProfile('cn-hangzhou', 'LTAI5TUoYIJablEa', 'crjFw80SlZ3B4mP0fTNKaG38HhtS9k');

        // 创建客户端
        $client = new DefaultAcsClient($profile);

        //上传ssl证书
        $uploadCertificate = new UploadServerCertificateRequest();
        $uploadCertificate->setActionName('UploadServerCertificate');
        $uploadCertificate->setServerCertificateName('丸子代练');
        $uploadCertificate->setRegionId('cn-hangzhou');
        $uploadCertificate->setPrivateKey(file_get_contents(storage_path('logs/fulugou.net/fulugou.net.key')));
        $uploadCertificate->setServerCertificate(file_get_contents(storage_path('logs/fulugou.net/fulugou.net.pem')));
        $uploadCertificateResult = $client->getAcsResponse($uploadCertificate);

        // 修改负载配置
        $updateHTTPSListenerAttribute = new SetLoadBalancerHTTPSListenerAttributeRequest();
        $updateHTTPSListenerAttribute->setActionName('SetLoadBalancerHTTPSListenerAttribute');
        $updateHTTPSListenerAttribute->setRegionId('cn-hangzhou');
        $updateHTTPSListenerAttribute->setLoadBalancerId('lb-bp1ad69a6d1yozfhu6opm');
        $updateHTTPSListenerAttribute->setListenerPort(443);
        $updateHTTPSListenerAttribute->setServerCertificateId($uploadCertificateResult->ServerCertificateId);
        $updateHTTPSListenerAttribute->setBandwidth(-1);
        $updateHTTPSListenerAttribute->setStickySession('off');
        $updateHTTPSListenerAttribute->setHealthCheck('off');
        $client->getAcsResponse($updateHTTPSListenerAttribute);

        // 删除过期证书
        if ($sslCertificateId = Redis::get('sslCertificateId')) {
            $deleteLoadBalance = new DeleteLoadBalancerRequest();
            $deleteLoadBalance->setRegionId('cn-hangzhou');
            $deleteLoadBalance->setLoadBalancerId($sslCertificateId);
            $client->getAcsResponse($deleteLoadBalance);
        }
        // 将本次证书存入redis
        Redis::set('sslCertificateId', $uploadCertificateResult->ServerCertificateId);
    }
}
