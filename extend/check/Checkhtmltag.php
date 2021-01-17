<?php


namespace check;


class Checkhtmltag
{


//检查html标签是否开闭
    public function checkTag($str){

        $str_len = strlen($str);
//记录起始标签
        $pre_data = array();
//记录起始标签位置
        $pre_pos = array();
        $last_data = array();
        $error_data = array();
        $error_pos = array();
        $i = 0;
//标记为 < 开始
        $start_flag = false;
        while( $i < $str_len ) {
            if($str[$i]=="<" && $str[$i+1]!='/' && $str[$i+1]!='!') {
                $i++;
                $_tmp_str = '';
                //标记为 < 开始
                $start_flag = true;
                //标记空白
                $space_flag = false;
                while($str[$i]!=">" && $str[$i]!="'" && $str[$i]!='"' && $str[$i] !='/' && $i<$str_len){
                    if($str[$i]==' ') {
                        $space_flag = true;
                    }
                    if(!$space_flag) {
                        $_tmp_str .= $str[$i];
                    }
                    $i++;
                }
                $pre_data[] = $_tmp_str;
                $pre_pos[] = $i;
            } else if ($str[$i]=="<" && $str[$i+1]=='/') {
                $i += 2;
                $_tmp_str = '';
                while($str[$i]!=">" && $i<$str_len){
                    $_tmp_str .= $str[$i];
                    $i++;
                }
                $last_data[] = $_tmp_str;
                //查看开始标签的上一个值
                if(count($pre_data)>0) {
                    $last_pre_node = $this->getLastNode($pre_data, 1);
                    if($last_pre_node == $_tmp_str) {
                        //配对上, 删除对应位置的值
                        array_pop($pre_data);
                        array_pop($pre_pos);
                        array_pop($last_data);
                    } else {
                        //没有配对上， 有两种情况
                        //情况一： 只有闭合标签， 没有开始标签
                        //情况二：只有开始标签， 没有闭合标签
                        array_pop($last_data);
                        $error_data[] = $_tmp_str;
                        $error_pos[] = $i;
                    }
                } else {
                    array_pop($last_data);
                    $error_data[] = $_tmp_str;
                    $error_pos[] = $i;
                }
            }else if ($str[$i]=="<" && $str[$i+1]=="!") {
                $i++;
                while($i<$str_len) {
                    if($str[$i]=="-" && $str[$i+1]=="-" && $str[$i+2]==">") {
                        $i++;
                        break;
                    } else {
                        $i++;
                    }
                }
                $i++;
            }else if($str[$i]=='/' && $str[$i+1]=='>') {
                //跳过自动单个闭合标签
                if($start_flag) {
                    array_pop($pre_data);
                    array_pop($pre_pos);
                    $i+=2;
                }
            }else if($str[$i]=="/" && $str[$i+1]=="*"){
                $i++;
                while($i<$str_len) {
                    if($str[$i]=="*" && $str[$i+1]=="/") {
                        $i++;
                        break;
                    } else {
                        $i++;
                    }
                    $i++;
                }
            }else if($str[$i]=="'"){
                $i++;
                while($str[$i]!="'" && $i<$str_len) {
                    $i++;
                }
                $i++;
            } else if($str[$i]=='"'){
                $i++;
                while($str[$i]!='"' && $i<$str_len ) {
                    $i++;
                }
                $i++;
            } else {
                $i++;
            }
        }

        $this-> sort_data($pre_data, $pre_pos, $error_data, $error_pos);
        $new_str =$this-> modify_data($str, $pre_data, $pre_pos, $error_data, $error_pos);
//        echo $new_str; die;
        return $new_str;



    }
//确定起始标签的位置
    function confirm_pre_pos($str, $pre_pos){
        $str_len = strlen($str);
        $j=$pre_pos;
        while($j < $str_len) {
            if($str[$j] == '"') {
                $j++;
                while ($j<$str_len) {
                    if($str[$j]=='"') {
                        $j++;
                        break;
                    }
                    $j++;
                }
            }
            else if($str[$j] == "'") {
                $j++;
                while ($j<$str_len) {
                    if($str[$j]=="'") {
                        $j++;
                        break;
                    }
                    $j++;
                }
            }
            else if($str[$j]==">") {
                $j++;
                while ($j<$str_len) {
                    if($str[$j]=="<") {
                        //退回到原有内容位置
                        $j--;
                        break;
                    }
                    $j++;
                }
                break;
            }
            else {
                $j++;
            }
        }
        return $j;
    }
//确定起始标签的位置
    function confirm_err_pos($str, $err_pos){
        $str_len=strlen($str);
        $j=$err_pos;
        $j--;
        while($j > 0) {
            if($str[$j] == '"') {
                $j--;
                while ($j<$str_len) {
                    if($str[$j]=='"') {
                        $j--;
                        break;
                    }
                    $j--;
                }
            }
            else if($str[$j] == "'") {
                $j--;
                while ($j<$str_len) {
                    if($str[$j]=="'") {
                        $j--;
                        break;
                    }
                    $j--;
                }
            }
            else if($str[$j]==">") {
                $j++;
                break;
            }
            else {
                $j--;
            }
        }
        return $j;
    }
//获取数组的倒数第num个值
    function getLastNode(array $arr, $num){
        $len = count($arr);
        if($len > $num) {
            return $arr[$len-$num];
        } else {
            return $arr[0];
        }
    }
//整理数据， 主要是向后看， 进一步进行检查
    function sort_data(&$pre_data, &$pre_pos, &$error_data, &$error_pos){
        $rem_key_array = array();
        $rem_i_array = array();
        //获取需要删除的值
        foreach($error_data as $key=>$value){
            $count = count($pre_data);
            for($i=($count-1) ; $i>=0; $i--) {
                if($pre_data[$i] == $value && !in_array($i, $rem_i_array)) {
                    $rem_key_array[] = $key;
                    $rem_i_array[] = $i;
                    break;
                }
            }
        }
        //删除起始标签相应的值
        foreach($rem_key_array as $_item) {
            unset($error_pos[$_item]);
            unset($error_data[$_item]);
        }
        //删除结束标签相应的值
        foreach($rem_i_array as $_item) {
            unset($pre_data[$_item]);
            unset($pre_pos[$_item]);
        }
    }
//整理数据， 闭合标签
    function modify_data($str, $pre_data, $pre_pos, $error_data, $error_pos){
        $move_log = array();
        //只有闭合标签的数据
        foreach ($error_data as $key => $value) {
            // code...
            $_tmp_move_count = 0;
            foreach ($move_log as $pos_key => $move_value) {
                // code...
                if($error_pos[$key]>=$pos_key) {
                    $_tmp_move_count += $move_value;
                }
            }
            $data =$this-> insert_data($str, $value, $error_pos[$key]+$_tmp_move_count, false);
            $str = $data['str'];
            $move_log[$data['pos']] = $data['move_count'];
        }
        //只有起始标签的数据
        foreach ($pre_data as $key => $value) {
            // code...
            $_tmp_move_count = 0;
            foreach ($move_log as $pos_key => $move_value) {
                // code...
                if($pre_pos[$key]>=$pos_key) {
                    $_tmp_move_count += $move_value;
                }
            }
            $data =$this-> insert_data($str, $value, $pre_pos[$key]+$_tmp_move_count, true);
            $str = $data['str'];
            $move_log[$data['pos']] = $data['move_count'];
        }
        return $str;
    }
//插入数据， $type 表示插入数据的方式
    function insert_data($str, $insert_data, $pos, $type) {
        $len = strlen($str);
        //起始标签类型
        if($type==true) {
            $move_count = strlen($insert_data)+3;
            $pos = $this->confirm_pre_pos($str, $pos);
            $pre_str = substr($str, 0, $pos);
            $end_str = substr($str, $pos);
            $mid_str = "</" . $insert_data . ">";
            //闭合标签类型
        } else {
            $pos = $this->confirm_err_pos($str, $pos);
            $move_count = strlen($insert_data) + 2;
            $pre_str = substr($str, 0, $pos);
            $end_str = substr($str, $pos);
            $mid_str = "<" . $insert_data . ">";
        }
        $str = $pre_str.$mid_str.$end_str;
        return array('str'=>$str, 'pos'=>$pos, 'move_count'=>$move_count);
    }


}