<?php
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        DB::table('users')->insert([
            'username' => 'admin',
            'password' => bcrypt('123456'),
            'realname' => 'ADMIN',
            'mobile' => '13912345678',
            'email' => 'webmaster@qq.com',
            'status' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('profiles')->insert([
            'user_id' => 1,
            'resumes' => '',
        ]);

        DB::table('roles')->insert([
            [
                'name' => 'Administrators',
                'display_name' => '系统管理员',
                'description' => '系统管理员允许进入后台管理系统',
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'name' => 'NewsManger',
                'display_name' => '资讯管理员',
                'description' => '允许对所有新闻资讯栏目进行管理',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('role_user')->insert([
            'user_id' => 1,
            'role_id' => 1,
        ]);

        DB::table('permissions')->insert([
            [
                'name' => 'system',
                'display_name' => '系统管理',
                'description' => '角色管理,用户管理,权限管理…',
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'name' => 'link',
                'display_name' => '链接管理',
                'description' => '友情链接,链接标签,…',
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'name' => 'news',
                'display_name' => '资讯管理',
                'description' => '资讯栏目结构,信息管理,…',
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'name' => 'siteconfig',
                'display_name' => '站点配置',
                'description' => 'Meta设置,Logo图片,…',
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'name' => 'pageadver',
                'display_name' => '页面图片管理',
                'description' => '网站页面广告位管理…',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('permission_role')->insert([
            'permission_id' => 1,
            'role_id' => 1,
        ]);
    }
}
