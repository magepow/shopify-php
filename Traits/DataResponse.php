<?php

namespace App\Traits;

use App\Shopify\DataObject;
use Osiset\BasicShopifyAPI\ResponseAccess;

trait DataResponse
{

    /**
     * Filter Data by Key from API
     * $query: string query use to debug
     */
    public function getDataResponse($response, $path='data', $query='grapql', $dataObject=true)
    {
        try {
            if($response['errors'] && $query){
                echo "Query: <pre>$query</pre>";
                echo "Response return an error result data:";
                dd($response);
            }
            $responseAccess = $response['body'];
            $keys = explode('/', $path);
            foreach ($keys as $key) {
                if(!$responseAccess instanceof ResponseAccess || !$responseAccess->__isset($key)){
                    break;
                }
                $responseAccess = $responseAccess->__get($key);
            }
            if($dataObject){
                return is_array($responseAccess->container) ? new DataObject($responseAccess->container) : $responseAccess;
            }

            return $responseAccess;
        } catch (\Throwable $message) {
            // echo 'The first param must result return from method "rest" instance \Osiset\BasicShopifyAPI';
            echo $message;
            if($query){
                dd($response);
            }
        }
 
    }

}
