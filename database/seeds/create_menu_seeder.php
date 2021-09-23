<?php

use Illuminate\Database\Seeder;

class create_menu_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $menus = [
            ['id' => 1, 'title' =>'概述', 'href' => '/zds/dashboard', 'icon' => 'yzd-home', 'icon_active' => 'yzd-homefill', 'is_icon' => true, 'sort' => 1,'unicid' =>1],
/*            ['id' => 2, 'title' =>'多少', 'icon' => '/static/public/img/icon_market_1.png', 'icon_active' => '/static/public/img/icon_market_2.png',
                'is_icon' => false,  'sort' => 2],*/
            ['id' => 2, 'title' => 'App', 'href' => '', 'icon' =>'', 'icon_active' => 'yzd-ho', 'is_icon' => false, 'sort' => 2,'unicid' =>1],

            ];
         $childMenus = [
             ['id'=>20,'title' => '基础设置', 'href' => '', 'parent_id' =>2,'unicid' =>1],
             ['id'=>21,'title' => 'APP管理', 'href' => '/zds/basic/setting', 'parent_id' =>2,'unicid' =>1],


         ];
         \Illuminate\Support\Facades\DB::table('mg_menus')->delete();
         foreach($menus as $menu) {
             \App\Model\MgMenu::create($menu);
         }

         foreach($childMenus as $child)
         {
            \App\Model\MgMenu::create($child);
         }

    }
}
