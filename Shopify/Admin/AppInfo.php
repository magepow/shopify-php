<?php

namespace App\Shopify\Admin;

use App\Shopify\DataObject;
use App\Shopify\Admin\Graphql\App as QueryApp;
use App\Traits\DataResponse;
use Osiset\ShopifyApp\Util;
use Osiset\BasicShopifyAPI\ResponseAccess;

class AppInfo
{
    use  DataResponse;

    /**
     * appInfo.
     */
    protected $appInfo;

    /**
     * appInstallation.
     */
    protected $appInstallation;

    /**
     * appInfo.
     */
    protected $queryApp;

    /**
     * appInfo.
     */
    protected $shopApi;

    public function __construct() 
    {
        $this->queryApp = new QueryApp();
    }

    public function getNamespace()
    {
        return 'magepowapps';
    }

    public function getShop()
    {
        return \Auth::guard('web')->user();
    }

    public function getShopAPI()
    {
        if($this->shopApi) return $this->shopApi;  
        $shop = $this->getShop();
        if($shop){
            /* @shopApi instance \Osiset\BasicShopifyAPI\BasicShopifyAPI; */
            $this->shopApi = $shop->api();
        }

        return $this->shopApi;
    }
    
    public function getQueryApp()
    {
        return $this->queryApp;  
    }

    public function getAppApiVersion()
    {
        return Util::getShopifyConfig('api_version');
    }

    public function getAppApiKey()
    {
        return Util::getShopifyConfig('api_key');
    }

    /* Alias of method getAppApiKey */
    public function getAppApiPublicKey()
    {
        return $this->getAppApiKey();
    }

    public function getAppApiSecret()
    {
        return Util::getShopifyConfig('api_secret');
    }

     /* Alias of method getAppApiSecret */
    public function getAppApiPrivateKey()
    {
        return $this->getAppApiSecret();
    }

    public function getAppName()
    {
        return Util::getShopifyConfig('app_name');
    }

    public function getAppInfoByAPIKey($apiKey)
    {
        $shopApi  = $this->getShopAPI();
        $query    = $this->getQueryApp()->getAppByKey();
        $response = $shopApi->graph($query, ['apiKey' => $apiKey]);

        return $this->getDataResponse($response, 'data/appByKey');
    }

    public function getAppInfoById($gid)
    {
        $shopApi  = $this->getShopAPI();
        $query    = $this->getQueryApp()->getAppById();
        $response = $shopApi->graph($query, ['id' => $gid]);

        return $this->getDataResponse($response, 'data/app');
    }

    public function getCurrentAppInstallation()
    {
        if($this->appInstallation) return $this->appInstallation;
        $shopApi  = $this->getShopAPI();
        $query    = $this->getQueryApp()->getCurrentAppInfo();
        $response = $shopApi->graph($query);
        $this->appInstallation = $this->getDataResponse($response, 'data/currentAppInstallation', $query);

        return $this->appInstallation;
    }

    public function getAppInfo()
    {
        if($this->appInfo) return $this->appInfo;
        $data          = $this->getCurrentAppInstallation()->getApp();
        $this->appInfo = new DataObject($data);

        return $this->appInfo;
    }

    public function getAppInstallationId()
    {
        return $this->getCurrentAppInstallation()->getData('id');
    }

    public function getGloballAppID()
    {
        return $this->getAppInfo()->getData('id');
    }

    public function getAppID()
    {
        return $this->getAppInfo()->getData('id');
    }

    public function convertGloballIdToId($gid)
    {
        return preg_replace('/\D/', '', $gid);
    }
   
    public function convertShopifyIdToNumber($gid)
    {
        return $this->convertGloballIdToId($gid);
    }

    public function getAppHandle()
    {
        return $this->getAppInfo()->getData('handle');
    }

    public function getLaunchUrl()
    {
        return $this->getAppInfo()->getData('launchUrl');
    }

    public function getAppIdByHandle($handle)
    {

    }

    public function getAppHandleById($id)
    {

    }

}
