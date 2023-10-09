<?php

namespace App\Shopify\Admin\Metafield;

class Product extends Base
{

    /**
     * getMetafieldAppInstallation.
     *
     * @var $metafield = ['namespace' => 'magepowapps', 'key' => 'lookbook']
     */
    public function getMetafield($metafield)
    {
        $queryRetrieval = $this->appInfo->getQueryApp()->getMetafieldAppInstallation();
        $apiShopify     = $this->appInfo->getShopAPI();
        $response       = $apiShopify->graph($queryRetrieval, $metafield);
        $dataObject     = $this->getDataResponse($response, 'data/currentAppInstallation/metafield');

        return $dataObject;
    }

    /**
     * Check Metafield Exists.
     *
     * @var $metafield = ['namespace' => 'magepowapps', 'key' => 'lookbook']
     */
    public function metafieldExists($metafield)
    {
        $dataObject = $this->getMetafield($metafield);

        return is_null($dataObject) ? false : true;
    }

    /*
    $input = [
        "owner" => "gid://shopify/Product/8159048204528",
        "namespace" => "magepowapps",
        "key" => "lookbook",
        "valueInput" => [
            "value" => "Private Metafield AppInstallation Text App",
            "valueType" => "STRING"
        ]
    ];
    $queryCreate    = $appInfo->getQueryApp()->createPrivateMetafield();
    $response       = $apiShopify->graph($queryCreate, ['input' => $input]);
    $queryRetrieval = $appInfo->getQueryApp()->getPrivateMetafield();
    $input          = [
        "id" => "gid://shopify/Product/8159048204528",
        "namespace" => "magepowapps",
        "key" => "lookbook",
    ];
    $response       = $apiShopify->graph($queryRetrieval, $input);
    */

    /* ownerId can is type Id of app or product
        $appInstallationId = $this->appInfo->getAppInstallationId();
        $metafieldsSetInput =  [
            "namespace" => "magepowapps",
            "key" => "lookbook",
            // "type" => "single_line_text_field",
            "type" => "multi_line_text_field",
            // "type" => "json",
            "value" => "value content",
            "ownerId" => "$appInstallationId"
        ];
    */
    public function createMetafield($metafieldsSetInput, $overwrite=false)
    {
        try {
            $metafield = [
                'namespace' => $metafieldsSetInput['namespace'],
                'key' => $metafieldsSetInput['key']
            ];
            $metafield = $this->metafieldExists($metafield);
            if( !$overwrite && $metafield){
                echo "The metafield exist";
            }
            /* Create own app metafield */
            if(!isset($metafieldsSetInput['ownerId'])){
                $metafieldsSetInput['ownerId'] = $this->appInfo->getAppInstallationId();
            }
            $queryCreate    = $this->appInfo->getQueryApp()->createAppOwnedMetafield();
            $response       = $this->appInfo->getShopAPI()->graph($queryCreate, [ 'metafields' => $metafieldsSetInput]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateMetafield($metafieldsSetInput)
    {
        try {
            $this->createMetafield($metafieldsSetInput, true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
