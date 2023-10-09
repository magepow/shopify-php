<?php

namespace App\Shopify\Admin\Metafield;

use App\Shopify\Admin\Graphql\Shop as queryShop;

class Shop extends Base
{

   /**
     * Query Shop.
     *
     * @var queryShop
     */
    protected $queryShop;

    public function getQueryShop()
    {   
        if(is_null($this->queryShop)){
            $this->queryShop = new queryShop();  
        }

        return $this->queryShop;  
    }

    /**
     * getMetafieldAppInstallation.
     *
     * @var $metafield = ['namespace' => 'magepowapps', 'key' => 'lookbook']
     */
    public function getMetafield($metafield)
    {
        $queryRetrieval = $this->appInfo->getQueryShop()->getMetafieldAppInstallation();
        $apiShopify     = $this->appInfo->getShopAPI();
        $response       = $apiShopify->graph($queryRetrieval, $metafield);
        $dataObject     = $this->getDataResponse($response, 'data/currentAppInstallation/metafield');

        return $dataObject;
    }

    public function createMetafield($metafieldStorefrontVisibilityInput, $overwrite=false)
    {
        try {
            $metafield = [
                'namespace' => $metafieldStorefrontVisibilityInput['namespace'],
                'key' => $metafieldStorefrontVisibilityInput['key']
            ];
            // $metafield = $this->metafieldExists($metafield);
            // if( !$overwrite && $metafield){
            //     echo "The metafield exist";
            // }
            /* Create own app metafield */
            if(!isset($metafieldStorefrontVisibilityInput['ownerType'])){
                $metafieldStorefrontVisibilityInput['ownerType'] = 'SHOP';
            }
            $queryCreate    = $this->getQueryShop()->createMetafieldStorefrontVisibility();
            $response       = $this->appInfo->getShopAPI()->graph($queryCreate, [ 'input' => $metafieldStorefrontVisibilityInput]);
            $dataObject  = $this->getDataResponse($response, 'data/metafield', $queryCreate);

            return $dataObject;
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
