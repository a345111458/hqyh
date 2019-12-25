<?php
use App\Models\User;

function ss($arr){

    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}




function delValue($arr){

}


// 返回用户数据
function responseJson($arr){

    return response()->json(['status'=>$arr['status'],'msg'=>$arr['msg']]);
}


function route_uri($name){

    //return app('router')->getRoutes()->getByName($name)->getUri();
}


// 返回 layui 表格所需要的数据格式
function returnJson($arr , $count){
    $data['code'] = 0;
    $data['msg']  = '请求数据成功';
    $data['count']= $count;
    $data['data'] = $arr;
    echo json_encode($data);
}


/**
* 获取所有 子孙目录
 */
function getTreeData($array, $fid = 0, $level = 0) {
    $column = [];
    foreach ($array as $key => $vo) {
        if ($vo['pid'] == $fid) {
            $vo['level'] = $level;
            $column[$key] = $vo;
            $column [$key]['allchild'] = getTreeData($array, $vo['id'], $level + 1);
        }
    }
    return $column;
}




/**
 * [数组下数据 相加]
 * @param  [type] $arr [description]
 * @return [type]      [description]
 */
function dataMerge($data){
    $arr = getTreeData($data);

    foreach ($arr as $k => $v) {

        $arr[$k]['team_zon'] = count($arr[$k]['allchild']);
        if (is_array($v['allchild'])) {
            $arr[$k]['allchilds'] = getTreeData($v['allchild']);
            $arr[$k]['team_ztui'] = count($arr[$k]['allchild']);
            $arr[$k]['team_zon'] += array_sum(array_column($arr[$k]['allchilds'],'team_zon'));
        }
        //unset($arr[$k]['allchilds']);
        //unset($arr[$k]['allchild']);
    }

    return array_values($arr);
}


// 返回 layui 分页所需格式
function pageLimit($request){

    $pages['pagenNum'] = $request->page ?: 1;
    $pages['limit'] = $request->limit ?: 10;
    $pages['page'] = $pages['pagenNum'] - 1;

    if ($pages['page'] != 0){
        $request->filled('limits') ?
            $pages['page'] = $request->limits * $pages['page']
            : $pages['page'] = $pages['page'] * $request->limit;
    }

    if ($request->filled('limits')){
        $pages['limit'] = $request->limits;
    }else{
        $pages['limit'] = $pages['limit'];
    }
    return $pages;
}



function getTree($data , $pid = 1 , $level = 0){

    $arr = [];
    foreach ($data as $k => $v) {
        if ($v['first_leader'] == $pid) {
            $v['level'] = $level;
            $arr[] = $v;
//            $arr['child'] =getTree($data , $v->user_id , $level+1);
            $arr = array_merge($arr , getTree($data , $v['user_id'] , $level+1));
        }
    }
    return $arr;
}



//(循环数组,父栏目ID,层次)
function getson($arr,$tid=0,$level=0){
    static $res = [];//静态变量 只会被初始化一次
    foreach($arr as $k=>$v){
        if($v['pid']===$tid){
            $tmp = $v;
            $tmp['levelsign'] = $level;
            $res[] = $tmp;
            getson($arr,$v['id'],$level+1);
        }
    }
    return $res;
}


//html构造方法
function levelhtml($level=0){
    $x=0;
    $html='';
    while($x<$level){
        $html='—'.$html;
        $x++;
    }
    return $x===0?$html:'|'.$html;
}


function getMenuTree($array, $fid = 0, $level = 0) {
    $column = [];
    foreach ($array as $k => $v) {
        if ($v['first_leader'] == $fid) {
            $v['level'] = $level;
            $column[$k] = $v;
            $column[$k]['child'] = getMenuTree($array,$v['user_id'], $level + 1);
        }
    }

    return $column;
}



/**
* 实现以二维数组指定某一个key排序
 */
function arraySort($array,$keys,$sort='asc') {
    $newArr = $valArr = array();
    foreach ($array as $key=>$value) {
        $valArr[$key] = $value[$keys];
    }
    ($sort == 'asc') ?  asort($valArr) : arsort($valArr);//先利用keys对数组排序，目的是把目标数组的key排好序
    reset($valArr); //指针指向数组第一个值
    foreach($valArr as $key=>$value) {
        $newArr[$key] = $array[$key];
    }
    return $newArr;
}













?>