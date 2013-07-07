<?php /* Smarty version 2.6.26, created on 2013-07-06 15:05:53
         compiled from report.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php if ($this->_tpl_vars['page_style']): ?><?php echo $this->_tpl_vars['page_style']; ?>
<?php endif; ?>
		<td width="202" height="167" valign="top" style="text-align:left;">
			<form method="post">
				<div class="st_tree" style="text-align:center;">
					<select name="stage" style="width:150px;margin-bottom:5px;">
						<option value="0">测评期数</option>
					<?php if ($this->_tpl_vars['stages']): ?>
						<?php $_from = $this->_tpl_vars['stages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
						<option value="<?php echo $this->_tpl_vars['item']['stage']; ?>
" <?php if ($this->_tpl_vars['item']['c']): ?>selected="selected"<?php endif; ?>>第<?php echo $this->_tpl_vars['item']['stage']; ?>
期</option> 
						<?php endforeach; endif; unset($_from); ?>
					<?php endif; ?>
					</select>
					<?php if ($this->_tpl_vars['areas']): ?>
					<select name="acode" id="acode" style="width:150px;margin-bottom:5px;">
						<option value="">区域</option>
						<?php $_from = $this->_tpl_vars['areas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
						<option value="<?php echo $this->_tpl_vars['item']['code']; ?>
" <?php if ($this->_tpl_vars['item']['c']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['item']['name']; ?>
</option> 
						<?php endforeach; endif; unset($_from); ?>
					</select>
					<?php endif; ?>
					<input type="submit" class="" value="提交" style="width:150px;margin:0 auto;">
				</div>
			</form>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "left-tree.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</td>
		<td valign="top">
			<?php if ($this->_tpl_vars['creportInfo']): ?>
				<div style="padding:10px 0;">
					<div style="margin:0;text-align:left;">
						本期为第<?php echo $this->_tpl_vars['context']['cstage']['stage']; ?>
期<br/>
						<!---->
						<!---->
					</div>
				</div>
			<?php endif; ?>

			<?php if ($this->_tpl_vars['table_type'] == 'analyse' || $this->_tpl_vars['table_type'] == 'analyse_percent'): ?>
				<?php if ($this->_tpl_vars['table_name']): ?><p style="line-height:2.5em;font-size:13px"><?php echo $this->_tpl_vars['table_name']; ?>
</p><?php endif; ?>
				<table class="three-line" align="center" cellpadding="1" cellspacing="0" id="the_tb">
					<tr bgcolor="#f0f0f0">
						<th rowspan="2"></th>
						<th colspan="4">频次分布</th>
						<th rowspan="2"><?php if ($this->_tpl_vars['page_type'] == 'report'): ?>集团<?php else: ?>区域<?php endif; ?>平均成绩</th>
						<th rowspan="2">众数</th>
						<th rowspan="2">中位数</th>
					</tr>
					<?php if ($this->_tpl_vars['table_type'] == 'analyse'): ?>
					<tr bgcolor="#f0f0f0">
						<th>[0,65分)</th>
						<th>[65分,75分)</th>
						<th>[75分,85分)</th>
						<th>[85分,100分]</th>
					</tr>
					<?php else: ?>
					<tr bgcolor="#f0f0f0">
						<th>[0,65%)</th>
						<th>[65%,75%)</th>
						<th>[75%,85%)</th>
						<th>[85%,100%]</th>
					</tr>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['c_analyse']): ?>
					<tr bgcolor="#FFFFFF">
						<td>本期</td> <td><?php echo $this->_tpl_vars['c_analyse']['d']; ?>
</td><td><?php echo $this->_tpl_vars['c_analyse']['c']; ?>
</td><td><?php echo $this->_tpl_vars['c_analyse']['b']; ?>
</td> <td><?php echo $this->_tpl_vars['c_analyse']['a']; ?>
</td> <td><?php echo $this->_tpl_vars['c_analyse']['average']; ?>
</td><td><?php echo $this->_tpl_vars['c_analyse']['mode']; ?>
</td><td><?php echo $this->_tpl_vars['c_analyse']['mid']; ?>
</td>
					</tr>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['p_analyse']): ?>
					<tr bgcolor="#FFFFFF">
						<td>上期</td> <td><?php echo $this->_tpl_vars['p_analyse']['d']; ?>
</td><td><?php echo $this->_tpl_vars['p_analyse']['c']; ?>
</td><td><?php echo $this->_tpl_vars['p_analyse']['b']; ?>
</td> <td><?php echo $this->_tpl_vars['p_analyse']['a']; ?>
</td> <td><?php echo $this->_tpl_vars['p_analyse']['average']; ?>
</td><td><?php echo $this->_tpl_vars['p_analyse']['mode']; ?>
</td><td><?php echo $this->_tpl_vars['p_analyse']['mid']; ?>
</td>
					</tr>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['compare']): ?>
					<tr bgcolor="#FFFFFF">
						<td>变化情况</td> <td><?php echo $this->_tpl_vars['compare']['d']; ?>
</td><td><?php echo $this->_tpl_vars['compare']['c']; ?>
</td><td><?php echo $this->_tpl_vars['compare']['b']; ?>
</td> <td><?php echo $this->_tpl_vars['compare']['a']; ?>
</td> <td><?php echo $this->_tpl_vars['compare']['average']; ?>
</td><td><?php echo $this->_tpl_vars['compare']['mode']; ?>
</td><td><?php echo $this->_tpl_vars['compare']['mid']; ?>
</td>
					</tr>
					<?php endif; ?>
				</table>
				<!--div style="float:left;width:100%;" class="info-area">
					<div class="btn" style="float:right;">
						<a id="S_Menu_12" class="a-btn b2" href="javascript:void(0);">柱图</a>
						<a id="S_Menu_10" class="a-btn b3" href="javascript:void(0);">线图</a>
					</div>
				</div-->
				<!---->
				<?php if ($this->_tpl_vars['rank']): ?>
					<p style="line-height:2.5em;font-size:13px"><?php echo $this->_tpl_vars['rank']['name']; ?>
</p>
					<table class="three-line" align="center" cellpadding="1" cellspacing="0" id="the_tb">
						<thead>
							<tr bgcolor="#f0f0f0">
								<?php $_from = $this->_tpl_vars['rank']['titles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['title']):
        $this->_foreach['list']['iteration']++;
?>
									<th id="dth_<?php echo ($this->_foreach['list']['iteration']-1); ?>
"><?php echo $this->_tpl_vars['title']; ?>
</th><?php endforeach; endif; unset($_from); ?>
							</tr>
						</thead>
						<tbody>
							<?php $_from = $this->_tpl_vars['rank']['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
								<tr>
									<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['sublist'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['sublist']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['sublist']['iteration']++;
?>
										<td class="dtd_<?php echo ($this->_foreach['sublist']['iteration']-1); ?>
"><?php echo $this->_tpl_vars['item']; ?>
</td>
									<?php endforeach; endif; unset($_from); ?>
								</tr>
							<?php endforeach; endif; unset($_from); ?>
						</tbody>
					</table>
				<?php endif; ?>
			<?php elseif ($this->_tpl_vars['table_type'] == 'three_line'): ?> <!---->
				<?php if ($this->_tpl_vars['table_name']): ?><p style="line-height:2.5em;font-size:13px"><?php echo $this->_tpl_vars['table_name']; ?>
</p><?php endif; ?>
				<table class="three-line" align="center" cellpadding="1" cellspacing="0" id="the_tb">
					<thead>
						<tr bgcolor="#f0f0f0">
							<?php $_from = $this->_tpl_vars['table_titles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['title']):
        $this->_foreach['list']['iteration']++;
?>
								<th id="dth_<?php echo ($this->_foreach['list']['iteration']-1); ?>
"><?php echo $this->_tpl_vars['title']; ?>
</th><?php endforeach; endif; unset($_from); ?>
						</tr>
					</thead>
					<tbody>
						<?php $_from = $this->_tpl_vars['table_datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
							<tr>
								<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['sublist'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['sublist']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['sublist']['iteration']++;
?>
									<td class="dtd_<?php echo ($this->_foreach['sublist']['iteration']-1); ?>
"><?php echo $this->_tpl_vars['item']; ?>
</td>
								<?php endforeach; endif; unset($_from); ?>
							</tr>
						<?php endforeach; endif; unset($_from); ?>
					</tbody>
				</table>
			<!---->
			<?php elseif ($this->_tpl_vars['table_type'] == 'muilty_three_line'): ?> <!---->
				<?php $_from = $this->_tpl_vars['tables']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['tlist'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['tlist']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['table_item']):
        $this->_foreach['tlist']['iteration']++;
?>
				<?php if ($this->_tpl_vars['table_item']['table_name']): ?><p style="line-height:2.5em;font-size:13px"><?php echo $this->_tpl_vars['table_item']['table_name']; ?>
</p><?php endif; ?>
				<table class="three-line" align="center" cellpadding="1" cellspacing="0" id="tb_<?php echo ($this->_foreach['tlist']['iteration']-1); ?>
">
					<thead>
						<tr bgcolor="#f0f0f0">
						<?php $_from = $this->_tpl_vars['table_item']['titles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['title']):
        $this->_foreach['list']['iteration']++;
?>
							<th id="dth_<?php echo ($this->_foreach['tlist']['iteration']-1); ?>
_<?php echo ($this->_foreach['list']['iteration']-1); ?>
"><?php echo $this->_tpl_vars['title']; ?>
</th>
						<?php endforeach; endif; unset($_from); ?>
						</tr>
					</thead>
					<tbody>
						<?php $_from = $this->_tpl_vars['table_item']['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
							<tr>
							<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['sublist'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['sublist']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['sublist']['iteration']++;
?>
								<td class="dtd_<?php echo ($this->_foreach['tlist']['iteration']-1); ?>
_<?php echo ($this->_foreach['sublist']['iteration']-1); ?>
 dtd_<?php echo ($this->_foreach['sublist']['iteration']-1); ?>
"><?php echo $this->_tpl_vars['item']; ?>
</td>
							<?php endforeach; endif; unset($_from); ?>
							</tr>
						<?php endforeach; endif; unset($_from); ?>
					</tbody>
				</table><br/>
				<?php endforeach; endif; unset($_from); ?>
			<?php elseif ($this->_tpl_vars['table_type'] == 'compare_three_line'): ?> <!---->
				<?php $_from = $this->_tpl_vars['tables']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['tlist'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['tlist']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['table_item']):
        $this->_foreach['tlist']['iteration']++;
?>
				<?php if ($this->_tpl_vars['table_item']['table_name']): ?><p style="line-height:2.5em;font-size:13px"><?php echo $this->_tpl_vars['table_item']['table_name']; ?>
</p><?php endif; ?>
				<table class="three-line" align="center" cellpadding="1" cellspacing="0" id="tb_<?php echo ($this->_foreach['tlist']['iteration']-1); ?>
">
					<thead>
						<tr bgcolor="#f0f0f0">
						<?php if ($this->_tpl_vars['table_item']['type'] == 'ext'): ?>
							<?php $_from = $this->_tpl_vars['table_item']['titles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['title']):
        $this->_foreach['list']['iteration']++;
?>
								<?php if (($this->_foreach['list']['iteration']-1) == 0): ?>
								<th id="dth_<?php echo ($this->_foreach['tlist']['iteration']-1); ?>
_<?php echo ($this->_foreach['list']['iteration']-1); ?>
"><?php echo $this->_tpl_vars['title']; ?>
</th>
								<?php else: ?>
								<th id="dth_<?php echo ($this->_foreach['tlist']['iteration']-1); ?>
_<?php echo ($this->_foreach['list']['iteration']-1); ?>
" colspan="2"><?php echo $this->_tpl_vars['title']; ?>
</th>
								<?php endif; ?>
							<?php endforeach; endif; unset($_from); ?>
						<?php else: ?>
							<?php $_from = $this->_tpl_vars['table_item']['titles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['title']):
        $this->_foreach['list']['iteration']++;
?>
								<th id="dth_<?php echo ($this->_foreach['tlist']['iteration']-1); ?>
_<?php echo ($this->_foreach['list']['iteration']-1); ?>
"><?php echo $this->_tpl_vars['title']; ?>
</th>
							<?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php $_from = $this->_tpl_vars['table_item']['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
							<tr>
							<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['sublist'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['sublist']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['sublist']['iteration']++;
?>
								<td class="dtd_<?php echo ($this->_foreach['tlist']['iteration']-1); ?>
_<?php echo ($this->_foreach['sublist']['iteration']-1); ?>
"><?php echo $this->_tpl_vars['item']; ?>
</td>
							<?php endforeach; endif; unset($_from); ?>
							</tr>
						<?php endforeach; endif; unset($_from); ?>
					</tbody>
				</table><br/>
				<?php endforeach; endif; unset($_from); ?>
			<?php elseif ($this->_tpl_vars['table_type'] == 'dir'): ?>
				<?php $_from = $this->_tpl_vars['tables']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['tlist'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['tlist']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['tlist']['iteration']++;
?>
				<?php if ($this->_tpl_vars['item']['table_name']): ?><p style="line-height:2.5em;font-size:13px"><?php echo $this->_tpl_vars['item']['table_name']; ?>
</p><?php endif; ?>
				<table class="three-line" align="center" cellpadding="1" cellspacing="0">
					<thead>
						<tr bgcolor="#f0f0f0">
							<th colspan="2"><?php echo $this->_tpl_vars['item']['title']; ?>
</th>
						</tr>
					</thead>
					<tbody>
						<?php $_from = $this->_tpl_vars['item']['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
							<tr>
								<td style="text-align:left;padding-left:2em;"><?php echo $this->_tpl_vars['data']['title']; ?>
</td>
								<td style="width:7em;"><a href="<?php echo $this->_tpl_vars['data']['url']; ?>
">查看</a></td>
							</tr>
						<?php endforeach; endif; unset($_from); ?>
					</tbody>
				</table><br/>
				<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
			
				<div style="height:40px;padding-top:5px;">
					<ul class="f-btn">
						<li><a href="javascript:void(0);" onclick="history.go(-1);" class="fb1">返回</a></li>
						<li><a href="javascript:void(0);" onclick="window.print();" class="fb3">打印</a></li>
					</ul>
				</div>
				<div id="_chart" style="display:none"></div>
					</td>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo '
<script type="text/javascript">
function chart_window(controller, action, type){
	$j("#_chart").load(
		"./part/chart.php?c="+controller+"&a="+action+"&type="+type,
		function(a,b,c){
			art.dialog({
						title:\'图表\',
						id:\'testDialog\',
						//lock:true,
						content:a
					});
		}
	);
}
/*
function chart_window_old(controller, action, type){
	$j("#_chart").load(
		"index.php?c="+controller+"&a="+action,
		{type:type,ajax:\'chart\'},
		function(a,b,c){
			art.dialog({
						title:\'图表\',
						id:\'testDialog\',
						lock:true,
						content:a
					});
		}
	);
}*/
//1000-350
//750-350
//500-250
</script>
'; ?>