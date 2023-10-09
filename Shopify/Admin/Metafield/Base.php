<?php

namespace App\Shopify\Admin\Metafield;

use App\Shopify\Admin\AppInfo;
use App\Shopify\DataObject;
use App\Traits\DataResponse;

class Base extends DataObject
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

}
