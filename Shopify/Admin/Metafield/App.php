<?php

namespace App\Shopify\Admin\Metafield;

class App extends Base
{
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
    public function createMetafield($metafieldsSetInput, $overwrite = false)
    {
        try {
            $metafield = [
                'namespace' => $metafieldsSetInput['namespace'],
                'key' => $metafieldsSetInput['key']
            ];
            $metafield = $this->metafieldExists($metafield);
            if (!$overwrite && $metafield) {
                echo "The metafield exist";
            }
            /* Create own app metafield */
            if (!isset($metafieldsSetInput['ownerId'])) {
                $metafieldsSetInput['ownerId'] = $this->appInfo->getAppInstallationId();
            }
            $queryCreate = $this->appInfo->getQueryApp()->createAppOwnedMetafield();
            $response    = $this->appInfo->getShopAPI()->graph($queryCreate, ['metafields' => $metafieldsSetInput]);
            $dataObject  = $this->getDataResponse($response, 'data/currentAppInstallation/metafield', $queryCreate);

            return $dataObject;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

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

    public function updateMetafield($metafieldsSetInput)
    {
        try {
            $this->createMetafield($metafieldsSetInput, true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /*
    * requirement @var ['input' => [
    *        "id"   => "gid://shopify/Metafield/1063298196",
    *   ]]
    */
    public function metafieldDelete($metafieldDeleteInput)
    {
        try {
            $queryDelete = $this->appInfo->getQueryApp()->metafieldDelete();
            $apiShopify     = $this->appInfo->getShopAPI();
            $response       = $apiShopify->graph($queryDelete, $metafieldDeleteInput);
            $dataObject     = $this->getDataResponse($response, 'data/currentAppInstallation/metafield');

            return $dataObject;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}