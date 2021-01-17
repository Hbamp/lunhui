<?php


namespace check;


use think\Db;

class Checkahref
{

   //替换表里的文章id 的column里的a标签里的href为#号
    public function  quitAhref($id,$tname,$column){

        $date=Db::name($tname)->where(['id'=>$id])->select();
        $text=$date[0][$column];

        //    正则匹配所有a标签
        /*    $preg='/<a .*?href="(http\:\/\/.*?)".*?>/is';*/
        $preg='/<a .*?href="(.*?)".*?>/is';
        preg_match_all($preg, $text,$da);
        for($i=0;$i<count($da[1]);$i++)//逐个输出超链接地址
        {
        $text=str_replace($da[1][$i],'#',$text);
        }

         Db::name($tname)->where(['id'=>$id])->setField([$column=>$text]);
        //print_r($text);die();
    }


    //替换表里的文章id 的column里的a标签里的href为#号
    public function  batchquitAhref($tname,$column){

        $preg='/<a .*?href="(.*?)".*?>/is';
        $date=Db::name($tname)->select();
        foreach ($date as $k=>$v) {
            preg_match_all($preg, $v[$column], $da);
            for ($i = 0; $i < count($da[1]); $i++)//逐个输出超链接地址
            {
                $v[$column] = str_replace($da[1][$i], '#', $v[$column]);
            }
                Db::name($tname)->where(['id' => $v['id']])->setField([$column => $v[$column]]);
        }
    }


//找到img src中的包含特定字符的img

public  function  findSrc($str,$tname,$column){

         $preg='/<img .*?src="(.*?'.$str.')".*?>/is';
         $date=Db::name($tname)->select();
         foreach ($date as $k=>$v) {
         preg_match_all($preg, $v[$column], $da);
         for ($i = 0; $i < count($da[0]); $i++)//逐个输出img地址
         {
             $v[$column] = str_replace($da[0][$i], '', $v[$column]);
             Db::name($tname)->where(['id' => $v['id']])->setField([$column => $v[$column]]);
         }

    }
}







}