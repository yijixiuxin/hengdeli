{include file="admin/header.tpl"}
<div style="width:100%; text-align:center; margin:10px 0;">
	<a style="margin:0 10px;" class="submit ui-state-default ui-corner-all" href="project.php">回项目列表</a>
	<a style="margin:0 10px;" class="submit ui-state-default ui-corner-all" href="project.php?a=gedit&id={$pid}">题组</a>
	<a style="margin:0 10px;" class="submit ui-state-default ui-corner-all" href="project.php?a=qedit&id={$pid}">问题</a>
</div>

<form method="post" action="project.php">
	<input type="hidden" value="qupdate" name="a" />
	<input type="hidden" value="{$pid}" name="id" />
{if $action eq "gedit"}
	<table width="95%" class="StormyWeatherFormTABLE" style="margin:auto;text-align:center;" id="sortable_module">
		<thead> 
			<tr>
				<th>组id</th>
				<th>名称</th>
				<th>需要统计</th>
				<th>qids</th>
				{if $editable}<th>删除</th>{/if}
			</tr>
		</thead> 
		<tbody>
			{if $editable}
				{foreach from=$qids key=key item=group}
				<tr>
					<td><input type="text" value="{$group.gcode}" name="gcode[]" style="width:90%"/></td>
					<td><input type="text" value="{$group.gname}" name="gname[]" style="width:90%"/></td>
					<td><select name="calcble[]">
						{if $group.calcble}<option value="1">yes</option><option value="0">no</option>
						{else}<option value="0">no</option><option value="1">yes</option>{/if}
					</select></td>
					<td><input type="text" value="{$group.qids_str}" name="qids[]" style="width:90%" readonly="true"/></td>
					<td><span onClick="itemRemove(this);">删除</span></td>
				</tr>
				{/foreach}
			{else}
				{foreach from=$qids key=key item=group}
				<tr>
					<td><input type="text" value="{$group.gcode}" name="gcode[]" style="width:90%" readonly="true"/></td>
					<td><input type="text" value="{$group.gname}" name="gname[]" style="width:90%"/></td>
					<td>{if $group.calcble} yes {else} no {/if}<input type="hidden" name="calcble[]" value="{$group.calcble}" /></td>
					<td><input type="text" value="{$group.qids_str}" name="qids[]" style="width:90%" readonly="true"/></td>
				</tr>
				{/foreach}
			{/if}
		</tbody>
	</table>
	
	<div style="width:100%; text-align:center; margin-top:10px;">
		{if $editable}
		<input style="width:45%;margin:auto;" type="button" value="添加一组" onClick="itemAdd();" class="submit ui-state-default ui-corner-all"/>
		<input style="width:45%;margin:auto;" type="submit" value="提交" class="submit ui-state-default ui-corner-all"/>
		{else}
		<input style="width:90%;margin:auto;" type="submit" value="提交" class="submit ui-state-default ui-corner-all"/>
		{/if}
	</div>
	
{else}
	<table width="95%" class="StormyWeatherFormTABLE" style="margin:auto;">
		<tr>
			<th><input type="checkbox" id="check_all" /></th>
			<th>qid</th>
			<th>问题</th>
		</tr>
		{foreach from=$question_list key=key item=item}
		<tr>
			<td><input type="checkbox" value="{$item.qid}" /></td>
			<td>{$item.qid}</td>
			<td>{$item.question}</td>
		</tr>
		{/foreach}
	</table>
	
	<div style="width:100%; margin:10px 0;">
		{foreach from=$qids key=key item=group}
		<div style="width:95%; margin:5px auto;">
			{$group.gcode} - {$group.gname}<br/>
			<input type="hidden" name="gcode[]" value="{$group.gcode}" />
			<input type="hidden" name="gname[]" value="{$group.gname}" />
			<input type="hidden" name="calcble[]" value="{$group.calcble}" /></td>
		{if $editable}
			<input type="text" name="qids[]" value="{$group.qids_str}" style="width:100%" /><br/>
		{else}
			<input type="text" name="qids[]" value="{$group.qids_str}" style="width:100%" readonly="true"/><br/>
		{/if}
		</div>
		{/foreach}
	</div>
	
	<div style="width:100%; text-align:center;"><input style="width:95%;line-height:2em;" type="submit" value="提交" class="submit ui-state-default ui-corner-all"/></div>
{/if}

</form>
{literal}
<script>
	$(function(){
		//$("#sortable_module").disableSelection(); //使文字无法选中
		$("#sortable_module tbody").sortable({
			stop:function(event,ui){},
			placeholder: 'ui-state-highlight', //占位符
			update: function(event, ui) {
				//orderToStr();
			}
		});
		$("#sortable_module tbody tr").live("mousemove ",function(){ 
            $(this).css("background","#d1e5ff"); 
        }); 
        $("#sortable_module tbody tr").live("mouseout ",function(){ 
            $(this).css("background",""); 
        });
		//orderToStr();
	});
	function orderToStr(){
		var res = $('#sortable_module tbody').sortable('toArray');
		console.log(res); return true;
		var str = '';
		for(var key in res){
			if(str) str = str + '_' + res[key].substr(4);
			else str = res[key].substr(4); //第一个
		}
		$('#module_list_hidden').val(str);
	}

function itemAdd(){
	$('#sortable_module').append('<tr><td><input type="text" value="" name="gcode[]" style="width:90%"/></td>' 
		+'<td><input type="text" value="" name="gname[]"  style="width:90%"/></td>'
		+'<td><select name="calcble[]"><option value="1">yes</option><option value="0">no</option></select></td>'
		+'<td><input type="text" value="" name="qids[]" style="width:90%" readonly="true"/></td>'
		+'<td><span onClick="itemRemove(this);">删除</span></td></tr>');
}
function itemRemove(item){
	var res = confirm('确定删除吗？删除后改组内问题同样被删除。');
	if(!res) return true;
	$(item).parent().parent().remove();
	//alert(a);
}
</script>
{/literal}
{include file="admin/footer.tpl"}

{if $keshihua}
{foreach from=$q_of_p key=key item=group}
	{$group.gcode} - {$group.gname}<br/>
	<table width="95%" class="StormyWeatherFormTABLE" style="margin:auto;">
		<tr><th>{$group.gcode}</th><th>{$group.gname}</th></tr>
		{foreach from=$group.qids key=qid item=question}
		<tr><td>{$qid}</td><td>{$question.question}</td></tr>
		{/foreach}
	</table>
{/foreach}
{/if}