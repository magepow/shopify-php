<?php

namespace App\Shopify\Admin\Files;

use App\Shopify\Admin\AppInfo;
use App\Shopify\Admin\Graphql\Files as QueryFiles;
use App\Shopify\DataObject;
use App\Traits\DataResponse;
use Illuminate\Http\UploadedFile;

class Upload extends DataObject
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
        $this->queryFiles = new QueryFiles();
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
     * Query Files.
     *
     * return @var \App\Shopify\Admin\Graphql\Files
     */
    public function getQueryFiles()
    {
        return $this->queryFiles;
    }

    /*
    * requirement @fileCreateInput [
    *        "alt" => "alt text",
    *        "contentType" => "IMAGE",
    *        "originalSource" => "https://example.com/image.jpg"
    *   ]]
    */
    public function fileCreate($fileCreateInput)
    {
        try {
            $query      = $this->getQueryFiles()->fileCreate();
            $response   = $this->appInfo->getShopAPI()->graph($query, ['files' => $fileCreateInput]);
            $dataObject = $this->getDataResponse($response, 'data', $query);
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
    public function stagedUploadsCreate($stagedUploadInput)
    {
        try {
            $query      = $this->getQueryFiles()->stagedUploadsCreate();
            $response   = $this->appInfo->getShopAPI()->graph($query, ['input' => $stagedUploadInput]);
            $dataObject = $this->getDataResponse($response, 'data', $query);
            return $dataObject;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getFileUrl($gid)
    {
        try {
            //* not work with @var
            $query      = $this->getQueryFiles()->getFileUrl();
            $response   = $this->appInfo->getShopAPI()->graph($query, ['id' => $gid]);
            //*/
            // $query      = $this->getQueryFiles()->getFileUrl($gid);
            // $response   = $this->appInfo->getShopAPI()->graph($query);
            // dd($response);
            $dataObject = $this->getDataResponse($response, 'data', $query);
            return $dataObject;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /* 
    * $image instanceof Illuminate\Http\UploadedFile
    * return string image url or null
    */
    public function Upload(UploadedFile $image, $alt='', $wait=3)
    {
        $filename = $image->getClientOriginalName();
        $mimeType = $image->getClientMimeType();
        $filesize = $image->getSize();
        $pathname = $image->getPathname();
        $input = [
            // "fileSize" => "$filesize",
            "filename" => "$filename",
            "mimeType" => "$mimeType",
            "httpMethod" => "POST",
            "resource" => "IMAGE"
            // "resource" => "SHOP_IMAGE"
            
        ];
        // fileCreate
        /* process Files */
        $response      = $this->stagedUploadsCreate($input);
        $stagedTargets = $response->getDataByPath('stagedUploadsCreate/stagedTargets/0');
        $stagedTargets = new \App\Shopify\DataObject($stagedTargets);
        $url           = $stagedTargets->getData('url');
        $resourceUrl   = $stagedTargets->getData('resourceUrl');
        $parameters    = $stagedTargets->getData('parameters');

        $formData = [];
        foreach ($parameters as $parameter) {
            $formData[] = [
                'name' => $parameter['name'],
                'contents' => $parameter['value']
            ];
        }
        $formData[] = [
            'name' => 'file',
            'contents' => fopen($image->getRealPath(), 'r')
        ];

        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Accept'     => 'application/json',
                ],
                'multipart' => $formData
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
        $input = [
            "alt" => $alt,
            "contentType" => "IMAGE",
            "originalSource" => $resourceUrl
        ];
        $response = $this->fileCreate($input);
        $imageId  = $response->getDataByPath('fileCreate/files/0/id');
        
        sleep($wait); // wait (sleep) for 3 seconds for Shopify done upload image

        $fileUrl  = $this->getFileUrl($imageId);
        $imageUrl = $fileUrl->getDataByPath('node/image/url');

        return $imageUrl;
    }

}
