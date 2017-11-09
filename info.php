<?php
// $prefix = '\\\\192.168.2.2';  //前台使用相对路径可以找到时留空，填写网络地址用
$prefix = 'e:/phpStudy/WWW/stpath/a';  //前台使用相对路径可以找到时留空，填写网络地址用
$p = "a";        //相对路径，后台寻找文件夹用，查看目录下图片使用
$dir = getfiles($p);    //一级目录
$dir1 = array();
foreach ($dir as $v) {
	$dir1[]= getfilesSecond($p."/".$v);    //二级目录
}
$arr2 = array();
foreach ($dir1 as $k => $v) {
    foreach ($v as $m => $n) {
        $arr2[] = str_replace($p.'/', '', $n);  //二级目录地址去除默认路径，只保留后面路径
    }
}

foreach ($arr2 as $k => $v) {   //目录拼接，根据_拆分为数组
	$arr3[] = explode('_',$v);
}
foreach ($arr3 as $k => $v) {   //加入序号,方便前台使用
    $arr3[$k]['index'] = $k;
}
$page = isset($_REQUEST["page"])?$_REQUEST["page"]:0;   //默认第0页
$limit = isset($_REQUEST["limit"])?$_REQUEST["limit"]:10;   //默认10条数据一页
if($page<=1)
{
    $start = 0; //第一页
}else
{
    $start = $page*$limit-$limit;   //其他页
}
$arrPage = array_slice($arr3,$start,$limit);    //分页数据
foreach ($arrPage as $k => $v) {
    $arr4[$k]['id'] = $v['index']+1;
	$arr4[$k]['date'] = isset($v[0])?$v[0]:'';
	$arr4[$k]['name'] = isset($v[2])?$v[2]:'';
	$arr4[$k]['idcard'] = isset($v[3])?$v[3]:'';
    $v[1] = isset($v[1])?$v[1]:'';  //防止路径不符合规范报错
    // if($prefix!=='')
    // {
    //     // $arr4[$k]['link'] = $prefix.'\\'.$v[0].'\\'.$v[1].'_'.$v[2].'_'.$v[3];
    //     $arr4[$k]['link'] = $prefix.'/'.$v[0].'/'.$v[1].'_'.$v[2].'_'.$v[3];
    // }else{
    //     // $arr4[$k]['link'] = $p.'\\'.$v[0].'\\'.$v[1].'_'.$v[2].'_'.$v[3];    //文件夹地址拼接 2017-10-12_2010705043214_啊_3302251115552223   0_1_2_3
    //     $arr4[$k]['link'] = $p.'/'.$v[0].'/'.$v[1].'_'.$v[2].'_'.$v[3];    //文件夹地址拼接 2017-10-12_2010705043214_啊_3302251115552223   0_1_2_3
    // }
    // $arr4[$k]['link'] = $prefix.'\\'.$v[0].'\\'.$v[1].'_'.$v[2].'_'.$v[3];
    $arr4[$k]['link'] = $prefix.'/'.$arr4[$k]['date'].'/'.$v[1].'_'.$arr4[$k]['name'].'_'.$arr4[$k]['idcard'];    //文件夹地址
    $arr4[$k]['piclink'] = $p.'/'.$arr4[$k]['date'].'/'.$v[1].'_'.$arr4[$k]['name'].'_'.$arr4[$k]['idcard'];  //找图片使用相对路径
}
$arr5['code'] = 0;
$arr5['msg'] = '';
$arr5['count'] = count($arr3);
$arr5['data'] = $arr4;
// echo json_encode($arr5);
// echo urldecode(json_encode($arr5));    //将数据转化为json数据给前台
echo JSON($arr5);    //将数据转化为json数据给前台


// ----------------------------------------方法-------------------------------
function getfiles($path)	//获取一级目录
{
	$dir = array();
    foreach (scandir($path) as $afile) {
        if ($afile == '.' || $afile == '..') {
            continue;
        }
        if (is_dir($path . '/' . $afile)) {
        	$dir[] = $afile;
        }
    }
    return $dir;
}
function getfilesSecond($path)		//获取二级目录
{
	$dir = array();
    foreach (scandir($path) as $afile) {
        if ($afile == '.' || $afile == '..') {
            continue;
        }
        if (is_dir($path . '/' . $afile)) {
            $dir[] = $path.'_'.$afile;
        	// $dir[] = $path.'_'.iconv("utf-8","gbk",$afile);
            // getfiles($path . '/' . $afile);
        }
    }
    return $dir;
}

function dd($data){		//打印参数调整
    header('Content-type:text/html;charset=utf-8');
    echo "<pre>";
    print_r($data);
    exit;
}

function getOs($path)    //获取系统，win进行iconv转义
{
    $osPath = PATH_SEPARATOR==';'? iconv("utf-8","gbk",$path) : $path;
    return $osPath;
}

function arrayRecursive(&$array, $function, $apply_to_keys_also = false)    //json_encode替代用，未使用
{
    static $recursive_counter = 0;
    if (++$recursive_counter > 1000) {
        die('possible deep recursion attack');
    }
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            arrayRecursive($array[$key], $function, $apply_to_keys_also);
        } else {
            $array[$key] = $function($value);
        }
        if ($apply_to_keys_also && is_string($key)) {
            $new_key = $function($key);
            if ($new_key != $key) {
                $array[$new_key] = $array[$key];
                unset($array[$key]);
            }
        }
    }
    $recursive_counter--;
}

function JSON($array) {
 arrayRecursive($array, 'urlencode', true);
 $json = json_encode($array);
 return urldecode($json);
}