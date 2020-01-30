<?php

namespace App\Models;

use App\Caches\Config as ConfigCache;

class Config extends Model
{

    /**
     * 主键
     *
     * @var int
     */
    public $id;

    /**
     * 配置块
     *
     * @var string
     */
    public $section;

    /**
     * 配置键
     *
     * @var string
     */
    public $item_key;

    /**
     * 配置值
     *
     * @var string
     */
    public $item_value;

    public function getSource()
    {
        return 'config';
    }

    public function afterUpdate()
    {
        $configCache = new ConfigCache();
        $configCache->rebuild();
    }

}