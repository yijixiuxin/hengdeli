<?php
/*取图中转*/
if(
	!in_array($_REQUEST['c'], array('report', 'junior'))
	|| !preg_match('/^report\d_\d$/', $_REQUEST['a'])
	|| !in_array($_REQUEST['type'], array('line','zhu'))
){
	exit('param error');
}
$url = "?c={$_REQUEST['c']}&a={$_REQUEST['a']}&type={$_REQUEST['type']}&ajax=chart";
?>
<p>&nbsp;</p>
<iframe src="./index.php<?php echo $url; ?>" frameborder="0" height="400" width="800"></iframe>