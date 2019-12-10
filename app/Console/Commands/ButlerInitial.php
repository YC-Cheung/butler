<?php

namespace App\Console\Commands;

use App\Models\Administrator;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ButlerInitial extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'butler:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize the basic data';
    public static $confirmTips = 'Initialization operation: This operation will clear the admin related data table. Are you sure?';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->confirm(static::$confirmTips)) {
            $this->createMenu();
            $this->createUserRolePerm();
            $this->info('初始化完成，管理员为：admin，密码为：123456');
        }
        return 1;
    }

    protected function createMenu()
    {
        $inserts = [
            [1, 0, 0, 'System', '系统', 'Layout', 'system', '/system', 0],
            [2, 1, 0, 'SystemUser', '用户管理', 'system/user/index', 'user', 'user', 0],
            [3, 1, 0, 'SystemRole', '角色管理', 'system/role/index', 'user', 'role', 0],
            [4, 1, 0, 'SystemPerm', '权限管理', 'system/perm/index', 'user', 'perm', 0],
            [5, 1, 0, 'SystemMenu', '菜单管理', 'system/menu/index', 'user', 'menu', 0],
        ];
        $inserts = $this->combineInserts(
            ['id', 'parent_id', 'order', 'name', 'title', 'component', 'icon', 'path', 'is_hidden'],
            $inserts,
            [
                'permission' => null,
                'is_cache' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        Menu::truncate();
        Menu::insert($inserts);
    }

    protected function createUserRolePerm()
    {
        Administrator::truncate();
        Role::truncate();
        Permission::truncate();

        collect(['admin_role_users', 'admin_role_menu', 'admin_user_permissions', 'admin_role_permissions'])
            ->each(function ($table) {
                DB::table($table)->truncate();
            });

        $user = Administrator::create([
            'username' => 'admin',
            'name' => '超级管理员',
            'password' => bcrypt('123456'),
        ]);

        $user->roles()->create([
            'name' => '超级管理员',
            'slug' => 'administrator',
            'description' => '超级管理员',
        ]);

        Role::first()
            ->permissions()
            ->create([
                'name' => '所有权限',
                'slug' => '*',
                'http_path' => '*',
            ]);
    }

    /**
     * 组合字段和对应的值
     *
     * @param array $fields 字段
     * @param array $inserts 值，不带字段的
     * @param array $extra 每列都相同的数据，带字段
     *
     * @return array
     */
    protected function combineInserts(array $fields, array $inserts, array $extra): array
    {
        return array_map(function ($i) use ($fields, $extra) {
            $i = array_combine($fields, $i);
            return array_merge($i, $extra);
        }, $inserts);
    }
}


