<?php
namespace App\Controllers;

/**
 * DatabaseController
 */
class DatabaseController extends BaseController
{
    public function dictionary()
    {
        $tree = [];
        $database = $this->config['database']['database'];
        $sql = "SELECT TABLE_NAME,COLUMN_NAME,IS_NULLABLE,COLUMN_TYPE,COLUMN_DEFAULT,COLUMN_COMMENT FROM information_schema.`COLUMNS` WHERE TABLE_SCHEMA='{$database}'";

        $lists = DB()->query($sql);
        foreach ($lists as $vo) {
            $tables[$vo['TABLE_NAME']][] = $vo;
        }
        return success($tables);
    }
}
