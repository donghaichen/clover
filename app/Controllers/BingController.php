<?php
namespace App\Controllers;

use App\Models\Bing;
use http\Env\Request;

/**
 * \HomeController
 */
class BingController extends BaseController
{

    private $staticUrl = 'https://api.mengniang.tv';
    private $downloadUrl = 'https://api.mengniang.tv/v1/bing/download?file=';
    private $thumbUrl = 'https://api.mengniang.tv/timthumb.php?&w=643&h=356&a=&zc=1&src=';
    protected $bing;
    
    public function __construct()
    {
        $this->bing = DB('bing');
    }

    public function home()
    {
        $orderBy = isset($_GET['orderBy']) && !empty($_GET['orderBy']) ? $_GET['orderBy'] : 'id';
        $current_page = isset($_GET['current_page']) ? $_GET['current_page'] : 1;
        $per_page = isset($_GET['per_page']) && $_GET['per_page'] > 0 && $_GET['per_page'] < 100 ? $_GET['per_page'] : 12;
        $where = [];
        if (isset($_GET['search']) && !empty($_GET['search']))
        {
            $search = htmlspecialchars($_GET['search']);
            $where = [
                ['title', 'like', "%$search%"],
            ];
        }
        $where = [
            ['name', 'like', 1 . '%'],
            ['title', 'like', '%' . 1],
            ['id', '>', 1],
            ['status', '=', 1]
        ];
//        $total = $this->bing
//            ->field('id, title, src')
//            ->where($where)
//            ->where('id', '=', '1111')
//            ->where('created_at', '<', '2012')
//            ->page(1)->select();
        $total = $this->bing
            ->alias('a')
//            ->join('user u','u.id = a.author_id')
//            ->alias(['bing' => 'user'])
            ->order('id','desc')
            ->order(['title' =>'asc', 'src' => 'asc'])
            ->field('id, title, src')
//            ->where($where)
//            ->where('id', '=', '1111')
//            ->where('created_at', '<', '2012')


//            ->find()
            ->count();

        var_dump($total);
        exit();
        $today = $this->bing->order('id', 'desc')->find();
        $today->src = $this->staticUrl . $today->src;
        $data = $this->bing->where($where)
            ->order($orderBy, 'desc')
            ->offset(($current_page - 1) * $per_page)
            ->limit($per_page)
            ->select();
        foreach ($data as $k => $v)
        {
            $data[$k]['download'] = $this->downloadUrl . urlencode($v['src']) . '&filename=' . urlencode($v['title']);
            $data[$k]['src'] = $this->staticUrl . $v['src'];
            $data[$k]['thumb'] = $this->thumbUrl . $v['src'];
            $data[$k]['created_at'] = date('Y-m-d', strtotime($v['created_at']));
        }
        $data = compact('today','total', 'current_page', 'per_page', 'data');
        return success($data);
    }

    public function all()
    {
        $data = $this->bing->order('id', 'desc')->column('src', 'title')->select();
        foreach ($data as $k => $v)
        {
            $data[$k]['src'] = $this->staticUrl . $v['src'];
        }
        return success($data);
    }

    public function download()
    {
        $file = $_GET['file'];
        $filename = $_GET['filename'];
        return download($file, $filename);
    }

    public function view($id)
    {
        $rs = $this->bing->find($id);
        return success($rs);
    }

    public function bg()
    {
        $total = $this->bing->count();
        $bg = $this->bing->where('id', rand(1, $total))->find();
        $bg->src = $this->staticUrl . $bg->src;
        return success($bg);
    }

    public function options()
    {
        $type = $_GET['type'];
        $id = $_GET['id'];
        switch ($type)
        {
            default;
                $rs = $this->bing->where('id', $id)->increment($type);
                break;
        }
        return success($rs);

    }

    /*
     * 存储每日图片
     */
    public function getBing()
    {
        if (!password_verify($_GET['token'], '$2y$10$b0I0jYdqeGK/q2OANlEsH.XWpqqK0jHHC1i9t0tE/g3r.gDlx.Hj2'))
        {
            return error('Token 错误');
        }
        $bingUrl= 'https://cn.bing.com';
        $bing = httpRequest($bingUrl . '/HPImageArchive.aspx?format=js&idx=0&n=1');
        $bing = json_decode($bing, true)["images"][0];
        $title = trim(explode('(', $bing['copyright'])[0]);
        $src = $bingUrl . $bing['url'];
        $created_at = date('Y-m-d');
        parse_str(str_replace('https://www.bing.com/search?', '', $bing['copyrightlink']));
        $tag = $q;
        $path = '/static/bing/' . date('Y/') . date('m/');
        $src = $filename = $path . md5(password_hash($src, PASSWORD_DEFAULT)) . '.jpg';
        $downloadImg = downloadImg($bingUrl . $bing['url'], PUBLIC_PATH . $filename);
        $download_count = rand(100, 1000);
        $like_count = rand(500, 2000);
        $view_count = ($download_count + $like_count) * rand(1, 10);
        $data = compact('title', 'src', 'tag', 'created_at', 'download_count', 'view_count', 'like_count');
        $rs = $this->bing->insert($data);
        var_dump($downloadImg, $rs, $data);
    }

    //API 方法开始

    public function today()
    {
        $bingUrl= 'https://cn.bing.com';
        $bing = httpRequest($bingUrl . '/HPImageArchive.aspx?format=js&idx=0&n=1');
        $bing = json_decode($bing, true)["images"][0];
        $src = $bingUrl . $bing['url'];
        return redirect($src);
    }

    public function image()
    {
        $rs = $this->bing->where('created_at', date('Y-m-d H:i:s', strtotime($_GET['date'])))->find();
        if (is_null($rs))
        {
            return error('暂无该日图片');
        }
        $rs->src = $this->staticUrl . $rs->src;
        return redirect($rs->src);
    }

    public function rand()
    {
        $total = $this->bing->count();
        $rs = $this->bing->where('id', rand(1, $total))->find();
        $rs->src = $this->staticUrl . $rs->src;
        if (is_null($rs))
        {
            return error('暂无该日图片');
        }
        $rs->src = $this->staticUrl . $rs->src;
        return redirect($rs->src);
    }

    public function imageDesc()
    {
        $rs = $this->bing->where('created_at', date('Y-m-d H:i:s', strtotime($_GET['date'])))->find();
        $rs->src = $this->staticUrl . $rs->src;
        if (is_null($rs))
        {
            return error('暂无该日图片');
        }
        $rs->src = $this->staticUrl . $rs->src;
        return success($rs);
    }

    public function list()
    {
        $orderBy = isset($_GET['orderBy']) && !empty($_GET['orderBy']) ? $_GET['orderBy'] : 'id';
        $current_page = isset($_GET['current_page']) ? $_GET['current_page'] : 1;
        $per_page = isset($_GET['per_page']) && $_GET['per_page'] > 0 && $_GET['per_page'] < 100 ? $_GET['per_page'] : 12;
        $where = [];
        if (isset($_GET['search']) && !empty($_GET['search']))
        {
            $search = htmlspecialchars($_GET['search']);
            $where = [
                ['title', 'like', "%$search%"],
            ];
        }

        $total = $this->bing->where($where)->count();
        if ($total == 0)
        {
            return error('当前查询条件下暂无图片，请参考 API 修改查询条件');
        }
        $data = $this->bing->where($where)
            ->order($orderBy, 'desc')
            ->offset(($current_page - 1) * $per_page)
            ->limit($per_page)
            ->select();
        foreach ($data as $k => $v)
        {
            $data[$k]['src'] = $this->staticUrl . $v['src'];
            $data[$k]['created_at'] = date('Y-m-d', strtotime($v['created_at']));
        }
        $data = compact('total', 'current_page', 'per_page', 'data');
        return success($data);
    }
    //API 方法结束
}
