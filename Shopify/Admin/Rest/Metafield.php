<?php

namespace App\Shopify\Admin\Rest;

class Metafield extends \App\Shopify\Admin\Metafield\Base
{

    public function getShopAPI()
    {
        return $this->getAppInfo()->getShopAPI();
    }

    /*
    *   @var ['metafield' => [
    *       'namespace' => 'magepowapps',
    *       'key' => 'lookbook',
    *       'type' => 'single_line_text_field',
    *       'value' => 'Test value '
    *   ]];
    */
    public function addMetafields($input)
    {
        return $this->getShopAPI()->rest('POST', '/admin/api/metafields.json', $input);
    }

    /*
    *   @var param = metafield[owner_id]=382285388&metafield[owner_resource]=blog
    */
    public function getMetafields($param='')
    {
        $uri = '/admin/api/metafields.json';
        if($param){
            $uri = $uri . '?' . $param; 
        }
        return $this->getShopAPI()->rest('GET', $uri);
    }

    /*
    *   @var param = 721389482
    */
    public function updateMetafields($id)
    {
        $uri = '/admin/api/metafields/';
        if($id){
            $uri = $uri . $id . '.json'; 
        }
        return $this->getShopAPI()->rest('PUT', $uri);
    }

}
