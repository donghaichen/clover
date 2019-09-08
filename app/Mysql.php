<?php

namespace App;

/**
 * SQL类
 *
 * 目前已完成 MySql 增删改查
 * @todo 事务，MODEL，支持更多数据库类型，超时问题，返回对象
 * @author      Donghai Chen
 * @version     1.0
 */

class Mysql
{
    protected $_PDO;
    protected $config = [];
    protected $queryStart = 0;
    protected $prefix;
    protected $table;
    protected $rowCount = 0;
    protected $options = [
        'alias' => '',
        'param' => [],
        'where' => '',
        'join' => '',
        'order' => '',
        'limit' => '',
        'field' => '*',
    ];
    protected $count = 0;
    protected $field = '*';
    protected $sql_debug = false;

    public function __construct($config, $table = '')
    {
        //连接接数据库
        try {
            $this->config = $config;
            $this->table = $table;
            $this->connect();

        } catch (\PDOException $e) {
            if ($e->getCode() == 'HY000') {
                $this->_PDO = null;
                $this->connect();
            }else{
                throw new \Exception($e->getMessage());
            }
        }
    }

    private function connect()
    {
        $this->_PDO = null;
        $config = $this->config;
        $type = $config['type'];     //数据库类型
        $host = $config['host']; //数据库主机名
        $database = $config['database'];    //使用的数据库
        $username = $config['username'];      //数据库连接用户名
        $password = $config['password'];          //对应的密码
        $dsn = "$type:host=$host;dbname=$database";
        $this->_PDO = new \PDO($dsn, $username, $password, [\PDO::ATTR_PERSISTENT => true]); //设置长链接
        //设置客户端字符集
        $charset = $config['charset'];
        $this->_PDO->exec("set names '$charset'");
        //禁用prepared statements的仿真效果 确保SQL语句和相应的值在传递到mysql服务器之前是不会被PHP解析
        $this->_PDO->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        $this->_PDO->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->prefix = $config['prefix'];
        $this->options['table'] = $config['prefix'] . $this->table;
    }

    //返回PDO对象
    public function PDO()
    {
        return $this->_PDO;
    }

    /**
     * @todo toArray
     */
    public function toArray()
    {

    }

    //数据表
    public function table($table)
    {
        $this->options['table'] = $table;
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

    //字段
    public function field(string $field = '*')
    {
        $this->options['field'] = $field;
        $this->field = $field;
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

    //条件
    public function where($field, $op = null, $condition = null)
    {
        $v = $op;
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
            $op = is_null($condition) ? '=' : $op;
            $op = strtoupper($op);
            $condition = is_null($condition) ? $v : $condition;
            $condition = is_string($condition) ? "'$condition'" : $condition;
            $where .= "$field $op $condition AND ";
            $this->options['where'] .= $where;
        }
        return $this;
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

    //分页
    public function page($page = 1, $num = 10)
    {
        $page = intval($page);
        $num = intval($num);
        $start = ($page - 1) * $num;
        $this->options['limit'] = "Limit $start, $num";
        return $this;
    }

    //自动判断新增还是更新
    public function save(array $data) :int
    {
        if(strlen($this->options['where']) > 0)
        {
            return $this->update($data);
        }
        return $this->insert($data);
    }

    //添加
    public function insert(array $data) :int
    {
        $update = '';
        foreach($data as $k => $v)
        {
            $this->options['param'][':' . $k]=$v;
            $update .= $k . "=:" . $k . ",";
        }
        $update = trim($update, ',');
        $this->options['sql'] = "INSERT INTO {$this->options['table']} SET $update;";
        $this->exec($this->options['sql'], $this->options['param']);
        return $this->_PDO->lastInsertId();
    }

    //删除
    public function delete() :int
    {
        $this->pre();
        $this->options['sql'] = "DELETE FROM {$this->options['table']} {$this->options['where']}";
        $this->exec($this->options['sql'], $this->options['param']);
        return $this->rowCount;
    }

    //更新
    public function update(array $data) :int
    {
        $this->pre();
        $update = '';
        foreach($data as $k => $v)
        {
            $this->options['param'][':' . $k]=$v;
            $update .= $k . "=:" . $k . ",";
        }
        $update = trim($update, ',');
        $this->options['sql'] = "UPDATE {$this->options['table']} SET {$update} {$this->options['where']}";
        $this->exec($this->options['sql'], $this->options['param']);
        return $this->rowCount;
    }

    //结果集
    public function select()
    {
        $res = $this->preQuery();
        return $res;
    }

    /**
     * Count elements of an object
     */
    public function count(string $field = '*') :int
    {
        $this->options['field'] = "COUNT($field) AS count";
        $res = $this->preQuery();
        return $res[0]['count'];
    }

    private function one($res)
    {
        if (count($res) == 0)
        {
            return null;
        }
        $res = $res[0];
        $column = explode(',', $this->field);
        if($res && $this->field != '*' && count($column) == 1)
        {
            $column = str_replace(' ', '', $column);
            return $res[$column[0]];
        }else{
            return $res;
        }
    }

    //获取单条数据
    public function find()
    {
        $this->limit(1);
        $res = $this->preQuery();
        return $this->one($res);
    }

    //自增
    public function increment($field, $step = 1)
    {
        $this->pre();
        $update = "$field = $field + $step";
        $this->options['sql'] = "UPDATE {$this->options['table']} SET $update {$this->options['where']}";
        $this->exec($this->options['sql'], $this->options['param']);
        return $this->rowCount;
    }

    //自减
    public function decrement($field, $step = 1)
    {
        $this->pre();
        $update = "$field = $field - $step";
        $this->options['sql'] = "UPDATE {$this->options['table']} SET $update {$this->options['where']}";
        $this->exec($this->options['sql'], $this->options['param']);
        return $this->rowCount;
    }

    //事务
    public function trans($callback, $arr = [])
    {
        $this->_PDO->beginTransaction();
        try {
            $result = null;
            if (is_callable($callback)) {
                $result = call_user_func_array($callback, [$arr]);
            }
            $this->_PDO->commit();
            return $result;
        } catch (\PDOException $e)
        {
            $this->error($e->getMessage(), $this->getSql());
        }
    }

    //执行query
    public function query($sql, $param = [])
    {
        $this->clearParam();
        $this->queryStart = microtime(true);
        if($this->sql_debug === true)
        {
            return $this->getSql();
        }else{
            try{
                $pre = $this->_PDO->prepare($sql);
                $pre->execute($param);
                $action = strtoupper(explode(' ', $sql)[0]);
                if ($action == 'SELECT')
                {
                    return $pre->fetchAll(\PDO::FETCH_ASSOC);
                }else if ($action == 'INSERT'){
                    return $this->_PDO->lastInsertId();
                }else{
                    return $pre->rowCount();
                }

            }catch (\PDOException $e)
            {
                $this->error($e->getMessage(), $sql);
            }
        }
    }

    //执行原生exec
    public function exec($sql,$param = [])
    {
        $this->clearParam();
        $this->queryStart = microtime(true);
        if($this->sql_debug === true)
        {
            return $this->getSql();
        }else{
            try{
                $pre = $this->_PDO->prepare($sql);
                $pre->execute($param);
                $this->rowCount = $pre->rowCount();
            }catch (\PDOException $e)
            {
                $this->error($e->getMessage(), $this->getSql());
            }
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
            $this->options['order'] = 'ORDER BY '. rtrim($this->options['order'],', ');
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

    //清空参数
    public function clearParam()
    {
        $this->options['field'] = '*';
        $this->options['where'] = '';
        $this->options['order'] = '';
        $this->options['limit'] = '';
        $this->options['join'] = '';
//        $this->options['param'] = [];
//        $this->options['sql'] = '';
    }

    //错误处理
    protected function error($errorMsg, $sql)
    {
        $log = new Log();
        $runTime = bcdiv(bcsub(microtime(true), $this->queryStart, 10), 1000, 6) . ' s';
        $context = compact('runTime', 'errorMsg');
        $log->sql($sql, $context);
        throw new \PDOException($errorMsg . str_repeat('-', 80) . 'SQL: ' .  $sql . str_repeat('-', 100));
    }

    //调试
    public function debug()
    {
        $this->sql_debug = true;
        return $this;
    }

    //生成调试sql
    public function getSql()
    {
        $sql = $this->options['sql'];
        foreach ($this->options['param'] as $k => $v) {
            $v = is_string($v) ? "'$v'" : $v;
            var_dump(":$k", $v);
            $sql = str_replace($k, $v, $sql);
        }
        return $sql;
    }
}
