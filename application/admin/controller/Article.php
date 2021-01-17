<?php

namespace app\admin\controller;
use think\Db;

class Article extends Base
{
   
    
    public function index(){

        $key = input('key');
        $map = [];
        if($key&&$key!=="")
        {
            $map['title'] = ['like',"%" . $key . "%"];
        }       
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = 10;// 获取总条数

        $count = Db::name('art')->where($map)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $article = new \app\admin\model\ArticleModel();
        $lists = $article->getArticleByWhere($map, $Nowpage, $limits);
        // dump($lists);die;
        foreach($lists as $k=>$v)
        {
            $lists[$k]['addtime']=date('Y-m-d H:i:s',$v['addtime']);
        }  
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count); 
        $this->assign('val', $key);
        if(input('get.page'))
        {
            return json($lists);
        }
        return $this->fetch();
    }


    public function add_ad()
    {

        
        $cate = new \app\admin\model\ArticleCateModel();
        if(request()->isPost()){
            
            $param = input('post.');
            $param['addtime'] = time();
            $param['click'] = 0;
            $res = db('art')->insert($param);
            if($res){
                $this->success('添加成功','article/index');
            }
            $this->error('添加失败','article/add_ad');
            
        }
        return $this->fetch('article/add_article');
    }

    
    public function edit_ad(){
        
        $cate = new \app\admin\model\ArticleCateModel();
        if (request()->isPost()){
            //dump(input());die();
            $param = input('post.');
            
            $id = $param['id'];
            unset($param['id']);
            db('art')->where('id',$id)->update($param);
            $this->success('修改成功','article/index');
        }
        $id = input('param.id');
        $data = db('art')->where('id',$id)->find();
        
        $this->assign('data',$data);
        
        return $this->fetch('article/edit_art');
    }

   
    public function del_ad ()
    {
        $id = input('id');
        $cout = new \app\admin\model\ArticleModel();
        $flag = $cout -> delArticle($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    public function caigou(){

        $key = input('key');
        $map = [];
        if($key&&$key!=="")
        {
            $map['title'] = ['like',"%" . $key . "%"];
        }       
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = 10;// 获取总条数

        $count = Db::name('caigou')->where($map)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $article = new \app\admin\model\ArticleModel();
        // $lists = $article->getArticleByWhere1($map, $Nowpage, $limits);
        // dump($lists);die;
        $lists = db('caigou')->field('*')->where($map)->page($Nowpage, $limits)->order('addtime desc')->select();
    
        foreach($lists as $k=>$v)
        {
            $lists[$k]['addtime']=date('Y-m-d H:i:s',$v['addtime']);
        }  
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count); 
        $this->assign('val', $key);
        if(input('get.page'))
        {
            return json($lists);
        }
        return $this->fetch('article/caigou');
    }


    public function add_caigou()
    {

        
        if(request()->isPost()){
            
            $param = input('post.');

            $param['addtime'] = time();
            $param['click'] = 0;
            $res = db('caigou')->insert($param);
            if($res){
                $this->success('添加成功','article/caigou');
            }
            $this->error('添加失败','article/add_caigou');
            
        }
        return $this->fetch('article/add_caigou');
    }

    
    public function edit_caigou(){
        
        if (request()->isPost()){
            //dump(input());die();
            $param = input('post.');
            
            $id = $param['id'];
            unset($param['id']);
            db('caigou')->where('id',$id)->update($param);
            $this->success('修改成功','article/caigou');
        }
        $id = input('param.id');
        $data = db('caigou')->where('id',$id)->find();
        
        $this->assign('data',$data);
        
        return $this->fetch('article/edit_caigou');
    }

   
    public function del_caigou()
    {
        $id = input('id');
        db('caigou')->where('id',$id)->delete();
        return json(['code' => 1, 'data' => '', 'msg' => '删除文章成功']);
    }
    


    public function zhongbiao(){

        $key = input('key');
        $map = [];
        if($key&&$key!=="")
        {
            $map['title'] = ['like',"%" . $key . "%"];
        }       
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = 10;// 获取总条数

        $count = Db::name('zhongbiao')->where($map)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $article = new \app\admin\model\ArticleModel();
        // $lists = $article->getArticleByWhere1($map, $Nowpage, $limits);
        // dump($lists);die;
        $lists = db('zhongbiao')->field('*')->where($map)->page($Nowpage, $limits)->order('addtime desc')->select();
    
        foreach($lists as $k=>$v)
        {
            $lists[$k]['addtime']=date('Y-m-d H:i:s',$v['addtime']);
        }  
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count); 
        $this->assign('val', $key);
        if(input('get.page'))
        {
            return json($lists);
        }
        return $this->fetch('article/zhongbiao');
    }


    public function add_zhongbiao()
    {

        
        if(request()->isPost()){
            
            $param = input('post.');
           
            $param['addtime'] = time();
            $param['click'] = 0;
            $res = db('zhongbiao')->insert($param);
            if($res){
                $this->success('添加成功','article/zhongbiao');
            }
            $this->error('添加失败','article/add_zhongbiao');
            
        }
        return $this->fetch('article/add_zhongbiao');
    }

    
    public function edit_zhongbiao(){
        
        if (request()->isPost()){
            //dump(input());die();
            $param = input('post.');
            
            $id = $param['id'];
            unset($param['id']);
            db('zhongbiao')->where('id',$id)->update($param);
            $this->success('修改成功','article/zhongbiao');
        }
        $id = input('param.id');
        $data = db('zhongbiao')->where('id',$id)->find();
        
        $this->assign('data',$data);
        
        return $this->fetch('article/edit_zhongbiao');
    }

   
    public function del_zhongbiao()
    {
        $id = input('id');
        db('zhongbiao')->where('id',$id)->delete();
        return json(['code' => 1, 'data' => '', 'msg' => '删除文章成功']);
    }


    public function chengjiao(){

        $key = input('key');
        $map = [];
        if($key&&$key!=="")
        {
            $map['title'] = ['like',"%" . $key . "%"];
        }       
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = 10;// 获取总条数

        $count = Db::name('chengjiao')->where($map)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $article = new \app\admin\model\ArticleModel();
        // $lists = $article->getArticleByWhere1($map, $Nowpage, $limits);
        // dump($lists);die;
        $lists = db('chengjiao')->field('*')->where($map)->page($Nowpage, $limits)->order('addtime desc')->select();
    
        foreach($lists as $k=>$v)
        {
            $lists[$k]['addtime']=date('Y-m-d H:i:s',$v['addtime']);
        }  
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count); 
        $this->assign('val', $key);
        if(input('get.page'))
        {
            return json($lists);
        }
        return $this->fetch('article/chengjiao');
    }


    public function add_chengjiao()
    {

        
        if(request()->isPost()){
            
            $param = input('post.');
           
            $param['addtime'] = time();
            $param['click'] = 0;
            $res = db('chengjiao')->insert($param);
            if($res){
                $this->success('添加成功','article/chengjiao');
            }
            $this->error('添加失败','article/add_chengjiao');
            
        }
        return $this->fetch('article/add_chengjiao');
    }

    
    public function edit_chengjiao(){
        
        if (request()->isPost()){
            //dump(input());die();
            $param = input('post.');
            
            $id = $param['id'];
            unset($param['id']);
            db('chengjiao')->where('id',$id)->update($param);
            $this->success('修改成功','article/chengjiao');
        }
        $id = input('param.id');
        $data = db('chengjiao')->where('id',$id)->find();
        
        $this->assign('data',$data);
        
        return $this->fetch('article/edit_chengjiao');
    }

   
    public function del_chengjiao()
    {
        $id = input('id');
        db('chengjiao')->where('id',$id)->delete();
        return json(['code' => 1, 'data' => '', 'msg' => '删除文章成功']);
    }


}