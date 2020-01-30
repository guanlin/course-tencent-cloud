<?php

namespace App\Providers;

use App\Library\Cache\Backend\Redis as RedisBackend;
use Phalcon\Cache\Frontend\Igbinary as IgbinaryFrontend;

class Cache extends Provider
{

    protected $serviceName = 'cache';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {

            $config = $this->getShared('config');

            $frontend = new IgbinaryFrontend([
                'lifetime' => $config->redis->lifetime,
            ]);

            $backend = new RedisBackend($frontend, [
                'host' => $config->redis->host,
                'port' => $config->redis->port,
                'auth' => $config->redis->auth,
                'index' => $config->redis->index,
                'prefix' => $config->redis->prefix,
                'persistent' => $config->redis->persistent,
            ]);

            return $backend;
        });
    }

}