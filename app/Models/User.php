<?php

namespace App\Models;

use App\Caches\MaxUserId as MaxUserIdCache;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class User extends Model
{

    /**
     * 教学角色
     */
    const EDU_ROLE_STUDENT = 1; // 学员
    const EDU_ROLE_TEACHER = 2; // 讲师

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 名称
     *
     * @var string
     */
    public $name;

    /**
     * 头像
     *
     * @var string
     */
    public $avatar;

    /**
     * 头衔
     *
     * @var string
     */
    public $title;

    /**
     * 介绍
     *
     * @var string
     */
    public $about;

    /**
     * 教学角色
     *
     * @var int
     */
    public $edu_role;

    /**
     * 后台角色
     *
     * @var int
     */
    public $admin_role;

    /**
     * VIP标识
     *
     * @var int
     */
    public $vip;

    /**
     * 锁定标识
     *
     * @var int
     */
    public $locked;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted;

    /**
     * VIP期限
     *
     * @var int
     */
    public $vip_expiry;

    /**
     * 锁定期限
     *
     * @var int
     */
    public $lock_expiry;

    /**
     * 最后活跃
     *
     * @var int
     */
    public $last_active;

    /**
     * 最后IP
     *
     * @var string
     */
    public $last_ip;

    /**
     * 通知数量
     *
     * @var int
     */
    public $notice_count;

    /**
     * 私信数量
     *
     * @var int
     */
    public $msg_count;


    /**
     * 创建时间
     *
     * @var int
     */
    public $created_at;

    /**
     * 更新时间
     *
     * @var int
     */
    public $updated_at;

    public function getSource()
    {
        return 'user';
    }

    public function initialize()
    {
        parent::initialize();

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
    }

    public function beforeCreate()
    {
        $this->created_at = time();
    }

    public function beforeUpdate()
    {
        $this->updated_at = time();
    }

    public function afterCreate()
    {
        $maxUserIdCache = new MaxUserIdCache();
        $maxUserIdCache->rebuild();
    }

    public static function eduRoles()
    {
        $list = [
            self::EDU_ROLE_STUDENT => '学员',
            self::EDU_ROLE_TEACHER => '讲师',
        ];

        return $list;
    }

}