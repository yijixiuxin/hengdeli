<?php /* Smarty version 2.6.26, created on 2013-06-27 04:26:31
         compiled from left-tree.tpl */ ?>
<link rel="stylesheet" type="text/css" href="./css/tree/SimpleTree.css" />
<script type="text/javascript" language="JavaScript" src="./js/SimpleTree.js"></script>
<div class="st_tree">
<ul>
<?php if ($this->_tpl_vars['nav_arrays']): ?>
	<?php $_from = $this->_tpl_vars['nav_arrays']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ngroup']):
?>
	<li><a href="javascript:;"><?php echo $this->_tpl_vars['ngroup']['title']; ?>
</a></li>
	<ul><!---->
		<?php $_from = $this->_tpl_vars['ngroup']['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['nav']):
?>
		<li><a href="<?php echo $this->_tpl_vars['nav']['url']; ?>
"><?php echo $this->_tpl_vars['nav']['title']; ?>
</a></li>
		<?php endforeach; endif; unset($_from); ?>
	</ul>
	<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>
</ul>
</div>
<?php echo '
<script>
$j(document).ready(function(){
	$j(".st_tree").SimpleTree({
		click:function(a){
			if("false" == $j(a).attr("hasChild")){
				location.href=$j(a).attr("href");
			}
				//alert($j(a).attr("ref"));
		}
	});
});
</script>
'; ?>