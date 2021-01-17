<?php
namespace app\index\controller;

class Index extends \think\Controller
{

    public function index($page=1)
    {
        $total = db('art')->count();
        $p = ceil($total/10);
        if($page <= 1){
            $page = 1;
        }
        if($page >= $p){
            $page = $p;
        }
        
        if($page == 1){
            $limit = 0;
        }else{
            $limit = ($page-1)*10;
        }
        
        $data = db('art')->limit($limit,10)->order('addtime desc')->select();

        $this->assign('data',$data);
        $this->assign('total',$total);
        $this->assign('page',$page);
        $this->assign('p',$p);
        return $this->fetch();
    }
    public function detail($id="",$t="")
    {
        if(!empty($id) && !empty($t) ){
            if($t == 'art'){
                $lm = '招标公告';
                $a = 'index';
            }elseif($t == 'zhongbiao'){
                $lm = '中标公示';
                $a = 'zhongbiao';
            }elseif($t == 'caigou'){
                $lm = '采购公告';
                $a = 'caigou';
            }elseif($t == 'chengjiao'){
                $lm = '成交公示';
                $a = 'chengjiao';
            }
            $data = db($t)->where('id',$id)->find();
            $data['click'] = $data['click'] + 1;
            db($t)->where('id',$id)->update(['click'=>$data['click']]);
            $this->assign('data',$data);
            $this->assign('lm',$lm);

            $this->assign('a',$a);
            return $this->fetch('index/detail');

        }else{
            return '错误参数';
        }
        
    }
    public function zhongbiao($page=1)
    {
        $total = db('zhongbiao')->count();
        $p = ceil($total/10);
        if($page <= 1){
            $page = 1;
        }
        if($page >= $p){
            $page = $p;
        }
        
        if($page == 1){
            $limit = 0;
        }else{
            $limit = ($page-1)*10;
        }
        
        $data = db('zhongbiao')->limit($limit,10)->order('addtime desc')->select();

        $this->assign('data',$data);
        $this->assign('total',$total);
        $this->assign('page',$page);
        $this->assign('p',$p);
        return $this->fetch('index/zhongbiao');
    }
    public function caigou($page=1)
    {
        $total = db('caigou')->count();
        $p = ceil($total/10);
        if($page <= 1){
            $page = 1;
        }
        if($page >= $p){
            $page = $p;
        }
        
        if($page == 1){
            $limit = 0;
        }else{
            $limit = ($page-1)*10;
        }
        
        $data = db('caigou')->limit($limit,10)->order('addtime desc')->select();

        $this->assign('data',$data);
        $this->assign('total',$total);
        $this->assign('page',$page);
        $this->assign('p',$p);
        return $this->fetch('index/caigou');
    }
    public function chengjiao($page=1)
    {
        $total = db('chengjiao')->count();
        $p = ceil($total/10);
        if($page <= 1){
            $page = 1;
        }
        if($page >= $p){
            $page = $p;
        }
        
        if($page == 1){
            $limit = 0;
        }else{
            $limit = ($page-1)*10;
        }
        
        $data = db('chengjiao')->limit($limit,10)->order('addtime desc')->select();

        $this->assign('data',$data);
        $this->assign('total',$total);
        $this->assign('page',$page);
        $this->assign('p',$p);
        return $this->fetch('index/chengjiao');
    }

   

}