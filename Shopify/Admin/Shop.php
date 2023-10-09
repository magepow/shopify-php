<?php

namespace App\Shopify\Admin;

use Illuminate\Support\Facades\Session;
use Osiset\ShopifyApp\Util;

class Shop
{

    public function getShop()
    {
        $shop = \Auth::guard('web')->user();
        if(!$shop){
            $shop = Session::get('shop');
        }

        return $shop;
    }

    public function getShopByToken($jwt)
    {
        $parts = explode('.', $jwt);
        $body  = json_decode(Util::base64UrlDecode($parts[1]), true);
        $url   = $body['dest'] ? $body['dest'] : '';
        $shop = User::where('name', $url)->first();
        
        return $shop;
    }

}
