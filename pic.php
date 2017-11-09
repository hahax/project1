<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title></title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="./static/css/layui.css"  media="all">
</head>
<body>
<?php
if(isset($_GET['link']))
{
	$link = $_GET['link'];
	$dir = array();
	if(is_dir($link))
	{
		foreach (scandir($link) as $afile) {
	        if ($afile == '.' || $afile == '..') {
	            continue;
	        }
	        $dir[] = $afile;
	    }
	    foreach ($dir as $k => $v) {
	    	$imgpath = $link.'/'.$v;
	    	echo "<img src='".$imgpath."' style='width:80%;margin:0 auto;display:block;margin-bottom:10px'>";
	    }
	}
}
?>
<script src="./static/layui.js" charset="utf-8"></script>
</body>
</html>