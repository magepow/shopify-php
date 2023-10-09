<?php

namespace App\Shopify\Admin\Asset;

use App\Shopify\Admin\AppInfo;
use App\Shopify\Admin\Graphql\Asset as QueryAsset;
use App\Shopify\DataObject;
use App\Traits\DataResponse;

class Push extends DataObject
{
    use  DataResponse;

    /**
     * Shopify Id.
     *
     * @var gid
     */
    protected $gid;

    /**
     * App Info.
     *
     * @var \App\Shopify\Admin\AppInfo
     */
    protected $appInfo;

    public function __construct(
        array $data = []
    )
    {
        $this->appInfo = new AppInfo();
        $this->queryAsset = new QueryAsset();
        parent::__construct($data);
    }

    /**
     * App Info.
     *
     * return @var \App\Shopify\Admin\AppInfo
     */
    public function getAppInfo()
    {
        return $this->appInfo;
    }

    /**
     * Query Asset.
     *
     * return @var \App\Shopify\Admin\Graphql\Asset
     */
    public function getQueryAsset()
    {
        return $this->queryAsset;
    }
    /*
    * requirement @fileCreateInput [
    *        "alt" => "alt text",
    *        "contentType" => "IMAGE",
    *        "originalSource" => "https://example.com/image.jpg"
    *   ]]
    */
    public function fileCreate($fileCreateInput, $overwrite=false)
    {
        try {
            $queryCreate = $this->getQueryAsset()->fileCreate();
            // dd($queryCreate);
            $response    = $this->appInfo->getShopAPI()->graph($queryCreate, ["files" => $fileCreateInput]);
            $dataObject  = $this->getDataResponse($response, 'data', $queryCreate);
            return $dataObject;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /*
    * requirement @stagedUploadInput [
    *        // "fileSize" => "$filesize",
    *        "filename" => "$filename",
    *        "mimeType" => "$mimeType",
    *        "httpMethod" => "POST",
    *        "resource" => "IMAGE"
    *        // "resource" => "SHOP_IMAGE"
    *   ]]
    */
    public function stagedUploadsCreate($stagedUploadInput, $overwrite=false)
    {
        try {
            $queryCreate = $this->getQueryAsset()->stagedUploadsCreate();
            $response    = $this->appInfo->getShopAPI()->graph($queryCreate, [ 'input' => $stagedUploadInput]);
            $dataObject  = $this->getDataResponse($response, 'data', $queryCreate);
            return $dataObject;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
