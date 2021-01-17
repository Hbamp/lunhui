<?php

namespace app\admin\controller;
use app\admin\Model\CategoryModel;
use think\Db;
use think\Request;

class Category extends Base
{	
    
    /**
     * [index 菜单列表]
     * @return [type] [description]
     * @author [田建龙] [864491238@qq.com]
     */
    public function index()
    {
        $data = db('type')->field('id,pid,name,status,ispart,sort')->where('pid',0)->order('id')->select();
        if(!empty(input('pid'))){
            $data = db('type')->field('id,pid,name,status,ispart,sort')->where('pid',input('pid'))->order('id')->select();
        }
        // dump($data);die;
        $this->assign('data',$data);
        return $this->fetch();
    }

	
    /**
     * [add_rule 添加栏目]
     * @return [type] [description]
     * @author [田建龙] [864491238@qq.com]
     */
	public function add_rule()
    {
        
        if(request()->isPost()){
            $data = input();
            unset($data['/admin/category/add_rule_html']);
            $data['status'] = isset($data['status'])?$data['status']:1;
            $data['pid'] = 0;
            db('type')->insert($data);
            $this->success('增加成功','category/index');
        }
        return $this->fetch();
    }


    public function add_son()
    {
        
        if(request()->isPost()){
            $data = input();
            unset($data['/admin/category/add_son_html']);
            $data['status'] = isset($data['status'])?$data['status']:1;
            
            db('type')->insert($data);
            $this->success('增加成功','category/index?pid='.$data['pid']);
        }
        $pid = input('id');
        $this->assign('pid',$pid);
        return $this->fetch();
    }


    /**
     * [edit_rule 编辑菜单]
     * @return [type] [description]
     * @author [田建龙] [864491238@qq.com]
     */
    public function edit_rule()
    {
        if(request()->isPost()){
            $data = input();
            
            $id = $data['id'];
            unset($data['/admin/category/edit_rule_html']);
            unset($data['id']);
            $data['status'] = isset($data['status'])?$data['status']:1;
            db('type')->where('id',$id)->update($data);
            $this->success('修改成功','category/index');
        }    
        $id = input('id');
        $data = db('type')->field('id,pid,name,status,sort,seotitle,content')->where('id',$id)->find();
        $this->assign('data',$data);
        return $this->fetch();
    }


    /**
     * [roleDel 删除角色]
     * @return [type] [description]
     * @author [田建龙] [864491238@qq.com]
     */
    public function del_rule()
    {
        $id = input('param.id');
        $menu = new \app\admin\model\CategoryModel();
        $flag = $menu->delMenu($id);
        db('type')->where('pid',$id)->delete();
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }



    /**
     * [ruleorder 排序]
     * @return [type] [description]
     * @author [田建龙] [864491238@qq.com]
     */
    public function ruleorder()
    {
        if (request()->isAjax()){
            $param = input('post.');     
            $auth_rule = Db::name('type');
            foreach ($param as $id => $sort){
                $auth_rule->where(array('id' => $id ))->setField('sort' , $sort);
            }
            return json(['code' => 1, 'msg' => '排序更新成功']);
        }
    }


    /**
     * [rule_state 菜单状态]
     * @return [type] [description]
     * @author [田建龙] [864491238@qq.com]
     */
    public function rule_state()
    {
        $id = input('param.id');

        $status = Db::name('type')->where(array('id'=>$id))->value('status');//判断当前状态
        $arr['status'] = $status==1?0:1;
        db('type')->where('id',$id)->update($arr);
        $this->redirect($_SERVER["HTTP_REFERER"]);
        
    
    }



}