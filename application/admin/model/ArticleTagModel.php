<?php

namespace app\admin\model;
use think\Model;
use think\Db;

class ArticleTagModel extends Model
{
    protected $name = 'att';
    
    // 开启自动写入时间戳
    protected $autoWriteTimestamp = false;


    /**
     * [getAllCate 获取全部分类]
     * @author [田建龙] [864491238@qq.com]
     */
    public function getAllCate()
    {
        return $this->order('id asc')->select();
    }


    /**
     * [insertCate 添加分类]
     * @author [田建龙] [864491238@qq.com]
     */
    public function insertCate($param)
    {
        try{
            $result = $this->save($param);
            if(false === $result){     
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '分类添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }



    /**
     * [editMenu 编辑分类]
     * @author [田建龙] [864491238@qq.com]
     */
    public function editCate($param)
    {
        try{
            $result = $this->save($param, ['id' => $param['id']]);
            if(false === $result){          
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '标签编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }



    /**
     * [getOneMenu 根据分类id获取一条信息]
     * @return [type] [description]
     * @author [田建龙] [864491238@qq.com]
     */
    public function getOneCate($id)
    {
        return $this->where('id', $id)->find();
    }



    /**
     * [delMenu 删除分类]
     * @return [type] [description]
     * @author [田建龙] [864491238@qq.com]
     */
    public function delCate($id)
    {
        try{
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除分类成功'];
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

}