<?php
namespace App\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Bing;

/**
 * \HomeController
 */
class DatabaseController extends BaseController
{
    public function dictionary()
    {
        $tree = [];
        $db_name = $this->config['database']['database'];
        $sql = "SELECT TABLE_NAME,COLUMN_NAME,IS_NULLABLE,COLUMN_TYPE,COLUMN_DEFAULT,COLUMN_COMMENT FROM information_schema.`COLUMNS` WHERE TABLE_SCHEMA='{$db_name}'";
        $lists = Bing::select($sql);
        foreach ($lists as $vo) {
            $tables[$vo['TABLE_NAME']][] = $vo;
        }
        var_dump($tables);
        exit();

//        $addons = M('apps')->column('title', 'name');
//        $addons['core'] = 'WeiPHP基础';
//
//        $models = M('model')->field('name,title,addon')->select();
//        $px = config('database.prefix');
//        foreach ($models as $vo) {
//            $key = $px . $vo['name'];
//            $model_titles[$key] = $vo['title'];
//            $name = parse_name($vo['addon']);
//            $addon_titles[$key] = isset($addons[$name]) ? $addons[$name] : '其它';
//        }
//
//        foreach ($tables as $table_name => $vo) {
//            $addon = isset($addon_titles[$table_name]) ? $addon_titles[$table_name] : '其它';
//            $tree[$addon][$table_name] = $vo;
//        }
    }
}
