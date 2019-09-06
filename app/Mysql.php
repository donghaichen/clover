<?php

//select语句
/*
->where('id', 1)
     *
     *  ->where([
        ['name', 'like', $name . '%'],
        ['title', 'like', '%' . $title],
        ['id', '>', $id],
        ['status', ' = ', $status],
    ])
}
*/

namespace App;

class Mysql
{
    protected $_PDO;
    protected $prefix;
    protected $options = [
        'alias' => '',
        'param' => [],
        'where' => '',
        'join' => '',
        'order' => '',
        'limit' => '',
    ];
    protected $count = 0;
    protected $field = '';
    protected $debug = 'false';

    public function __construct($config, $table)
    {
        $type = $config['type'];     //数据库类型
        $host = $config['host']; //数据库主机名
        $database = $config['database'];    //使用的数据库
        $username = $config['username'];      //数据库连接用户名
        $password = $config['password'];          //对应的密码
        $dsn = "$type:host=$host;dbname=$database";
        //连接接数据库
        try {
            $this->_PDO = new \PDO($dsn, $username, $password, [\PDO::ATTR_PERSISTENT => true]); //初始化一个PDO对象
            //设置客户端字符集
            $charset = $config['charset'];
            $this->_PDO->exec("set names '$charset'");
            //禁用prepared statements的仿真效果 确保SQL语句和相应的值在传递到mysql服务器之前是不会被PHP解析
            $this->_PDO->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->prefix = $config['prefix'];
            $this->options['table'] = $config['prefix'] . $table;
        } catch (\PDOException $e) {
            throw new \Exception("Error!: " . $e->getMessage() . "<br/>");
        }
    }

    //返回PDO对象
    public function PDO()
    {
        return $this->_PDO;
    }

    //操作表
    public function table($table)
    {
        $this->options['table'] = $table;
        return $this;
    }

    //字段
    public function field($field)
    {
        $this->options['field'] = $field;
        $this->field = $field;
        return $this;
    }

    /**
     * Count elements of an object
     */
    public function count()
    {
        $this->options['field'] = 'COUNT(*) AS count';
        $res = $this->preQuery();
        return $res[0]['count'];
    }

    /**
     * 指定排序 order('id','desc') 或者 order(['id'=>'desc','create_time'=>'desc'])
     * @access public
     * @param string|array $field 排序字段
     * @param string           $order 排序
     * @return $this
     */
    public function order($field, $order = '')
    {
        $order = strtoupper($order);
        if (is_string($field))
        {
            $order = empty($order) ? 'DESC': $order;
            $this->options['order'] .= "$field $order, ";
        }else{
            foreach ($field as $k => $v)
            {
                $v = strtoupper($v);
                $this->options['order'] .= "$k $v,";
            }
        }
        return $this;
    }

    //限制
    public function limit($limit)
    {
        $this->options['limit'] = ' LIMIT '.$limit;
        return $this;
    }

    /**
     * 指定数据表别名
     * ->alias('a')
     * ->alias(['user'=>'user','dept'=>'dept'])
     * @access public
     * @param array|string $alias 数据表别名
     * @return $this
     */
    public function alias($alias)
    {
        if (is_array($alias)) {
            $this->options['alias'] = $alias;
        } else {
            $this->options['table'] = $this->options['table'] . " AS $alias";
        }
        return $this;
    }

    /**
     * 查询SQL组装 join
     * @access public
     * @param mixed  $join      关联的表名
     * @param mixed  $condition 条件
     * @param string $type      JOIN类型
     * @param array  $bind      参数绑定
     * @return $this
     */
    public function join($join,  $condition = null, $type = 'INNER', array $bind = [])
    {
        $this->options['join'] .= "$type JOIN $join ON $condition";
        return $this;
    }

    public function leftJoin($join, $condition = null, array $bind = [])
    {
        return $this->join($join, $condition, 'LEFT', $bind);
    }

    public function rightJoin($join, $condition = null, array $bind = [])
    {
        return $this->join($join, $condition, 'RIGHT', $bind);
    }

    public function fullJoin($join, $condition = null, array $bind = [])
    {
        return $this->join($join, $condition, 'FULL');
    }

    /**
     * 指定group查询
     * @access public
     * @param string|array $group GROUP
     * @return $this
     */
    public function group($group)
    {
        $this->options['group'] = "GROUP BY $group";
        return $this;
    }

    //条件
    public function where($field, $op = null, $condition = null)
    {
        $where = '';
        if(is_array($field))
        {
            foreach($field as $k => $v)
            {
                $v2 = is_string($v[2]) ? "'$v[2]'" : $v[2];
                $where .= $v[0] . ' ' . $v[1] . ' ' . $v2 . ' AND ';
            }
            $this->options['where'] .= $where;
        }
        if(is_string($field))
        {
            $op = is_null($op) ? '=' : "$op";
            $condition = is_string($condition) ? "'$condition'" : $condition;
            $where .= "$field $op $condition AND ";
            $this->options['where'] .= $where;
        }
        return $this;
    }

    //分页
    public function page($page = 1,$num = 12)
    {
        $page = intval($page);
        $num = intval($num);
        $start = ($page - 1) * $num;
        $this->options['limit'] = "Limit $start, $num";
        return $this;
    }

    //调试
    public function debug()
    {
        $this->debug = true;
        return $this;
    }

    //结果集
    public function select()
    {
        $res = $this->preQuery();
        return $res;
    }

    //获取单条数据
    public function find()
    {
        $this->limit(1);
        $res = $this->preQuery();
        $column = explode(',', $this->field);
        $this->field = '';
        if($res && count($column) == 1)
        {
            $column = str_replace(' ', '', $column);
            return $res[$column[0]];
        }else{
            return $res;
        }
    }

    //更新
    public function update($data)
    {
        if($this->options['where'])
        {
            $update = '';
            foreach($data as $k => $v)
            {
                $column_key = '';
                foreach (explode('.',$k) as $kk => $vv) {
                    $column_key .= '`'.$vv.'`.';
                    $column_plac=$vv;
                }
                $this->options['param'][$column_plac]=$v;
                $column_key = trim($column_key,'.');
                $update .= $column_key . "=:".$column_plac . ",";
            }
            $update = trim($update,',');
            $this->where();
            $this->options['sql'] = "update {$this->options['table']} set $update {$this->options['where']};";
            return $this->exec($this->options['sql'], $this->options['param']);
        }else{
            echo '保存数据需指定条件';
            exit();
        }
    }

    //添加
    public function insert($data)
    {
        $update = '';
        foreach($data as $k => $v)
        {
            $column_key = '';
            foreach (explode('.',$k) as $kk => $vv) {
                $column_key .= '`'.$vv.'`.';
                $column_plac=$vv;
            }
            $this->options['param'][$column_plac]=$v;
            $column_key = trim($column_key,'.');
            $update .= $column_key . "=:".$column_plac . ",";
        }
        $update = trim($update,',');
        $this->options['sql'] = "insert into {$this->options['table']} set $update;";
        $this->exec($this->options['sql'], $this->options['param']);
        return $this->_PDO->lastInsertId();
    }

    //删除
    public function delete()
    {
        if($this->options['where'])
        {
            $this->where();
            $this->options['sql'] = "delete from {$this->options['table']} {$this->options['where']};";
            return $this->exec($this->options['sql'], $this->options['param']);
        }else{
            echo '删除数据需指定条件';
            exit();
        }
    }

    //执行原生query
    public function query($sql, $param = [])
    {
        $this->clearParam();
        if($this->debug === true)
        {
            echo "<pre>";
            echo $this->debugSql();
            exit();
        }else{
            $pre = $this->_PDO->prepare($sql);
            if(!$pre)
            {
                $this->_error();
            }
            $pre->execute($param);
            if($this->_error())
            {
                return $pre->fetchAll(\PDO::FETCH_ASSOC);
            }
        }
    }

    //执行原生exec
    public function exec($sql,$param = [])
    {
        $this->clearParam();
        if($this->debug)
        {
            echo "<pre>";
            echo $this->debugSql();
            exit();
        }else{
            $pre = $this->_PDO->prepare($sql);
            $res = $pre->execute($param);
            if($this->_error())
            {
                return $res;
            }
        }
    }

    //事务
    public function trans($callback,$arr=[])
    {
        $this->_PDO->beginTransaction();
        try {
            $result = null;
            if (is_callable($callback)) {
                $result = call_user_func_array($callback, [$arr]);
            }
            $this->_PDO->commit();
            return $result;
        } catch (\Exception $e) {
            $this->_PDO->rollback();
            throw $e;
        }
    }

    //清空参数
    public function clearParam()
    {
        $this->options['field'] = '*';
        $this->options['where'] = '';
        $this->options['order'] = '';
        $this->options['limit'] = '';
        $this->options['join'] = '';
        $this->options['param'] = [];
        $this->options['sql'] = '';
    }

    //自增
    public function setInc($field,$step=1)
    {
        if($this->options['where'])
        {
            $update = $field.' = '.$field.'+'.$step;
            $this->pre();
            $this->options['sql'] = "update {$this->options['table']} set $update {$this->options['where']};";
            return $this->exec($this->options['sql'], $this->options['param']);
        }else{
            echo '保存数据需指定条件';
            exit();
        }
    }

    //自减
    public function setDec($field, $step = 1)
    {
        if($this->options['where'])
        {
            $update = $field.' = '.$field.'-'.$step;
            $this->pre();
            $this->options['sql'] = "update {$this->options['table']} set $update {$this->options['where']};";
            return $this->exec($this->options['sql'], $this->options['param']);
        }else{
            echo '保存数据需指定条件';
            exit();
        }
    }

    //预处理
    protected function pre()
    {
        if(strlen($this->options['where']) > 0)
        {
            $this->options['where'] = 'WHERE '. rtrim($this->options['where'],'AND ');
        }
        if(strlen($this->options['order']) > 0)
        {
            $this->options['order'] = 'ORDER BY '. rtrim($this->options['order'],',');
        }
        return $this;
    }

    //查询
    protected function preQuery()
    {
        $this->pre();
        $this->options['sql'] = "SELECT {$this->options['field']} FROM {$this->options['table']} {$this->options['join']} {$this->options['where']} {$this->options['order']} {$this->options['limit']}";
        if(strlen($this->options['alias']) > 0)
        {
            foreach ($this->options['alias'] as $k => $v)
            {
                $this->options['sql'] = str_replace($k, "$k AS $v", $this->options['sql']);
            }
        }
        return $this->query($this->options['sql'], $this->options['param']);
    }

    //错误处理
    protected function _error()
    {
        if($this->_PDO->errorCode() == 00000)
        {
            return true;
        }else{
            echo '<pre>';
            $error_msg = $this->_PDO->errorInfo()[2];
            $e = new \Exception($error_msg);
            echo '<h2>'.$error_msg.'</h2>';
            echo '<h2>'.$e->getTrace()[2]['file'].' In line '.$e->getTrace()[2]['line'].'</h2>';
            echo '<h2>SQL 语句:'.$this->debugSql().'</h2>';
            exit();
        }
    }

    //生成调试sql
    public function debugSql()
    {
        $this->debug = true;
        $res = $this->options['sql'];
        foreach ($this->options['param'] as $k => $v) {
            $res = str_replace(':'.$k,'"'.$v.'"',$res);
        }
        return $res;
    }
}
