<?php

namespace App\Caches;

abstract class Cache extends \Phalcon\Mvc\User\Component
{

    /**
     * @var \Phalcon\Cache\Backend\Redis
     */
    protected $cache;

    public function __construct()
    {
        $this->cache = $this->getDI()->get('cache');
    }

    /**
     * 获取缓存内容
     *
     * @param mixed $id
     * @return mixed
     */
    public function get($id = null)
    {
        $key = $this->getKey($id);

        $content = $this->cache->get($key);

        if (!$this->cache->exists($key)) {

            $content = $this->getContent($id);

            /**
             * 原始内容为空，设置较短的生存时间，简单防止穿透
             */
            $lifetime = $content ? $this->getLifetime() : 5 * 60;

            $this->cache->save($key, $content, $lifetime);

            $content = $this->cache->get($key);
        }

        return $content;
    }

    /**
     * 删除缓存内容
     *
     * @param mixed $id
     */
    public function delete($id = null)
    {
        $key = $this->getKey($id);

        $this->cache->delete($key);
    }

    /**
     * 重建缓存内容
     *
     * @param mixed $id
     */
    public function rebuild($id = null)
    {
        $this->delete($id);

        $this->get($id);
    }

    /**
     * 获取缓存有效期
     *
     * @return int
     */
    abstract public function getLifetime();

    /**
     * 获取键值
     *
     * @param mixed $id
     * @return string
     */
    abstract public function getKey($id = null);

    /**
     * 获取原始内容
     *
     * @param mixed $id
     * @return mixed
     */
    abstract public function getContent($id = null);

}