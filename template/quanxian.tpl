{include file="header.tpl"}
		<td width="202" height="167"  valign="top" >{include file="left_quanxian.tpl"}</td>
<script type="text/javascript">
<!--
	{literal}
	
	{/literal}
	//-->
</script>

		<td valign="top">
		
					{if $message}
						<div class="message_output" style="padding: 3px 0 3px 0; width: 300px; margin: 0 auto; text-align: center">
							<p style="font-size:18px"><font color="red">{$message}</font></p>
						</div>
					{/if}
			<div class="wenti-biao">
				<table width="100%" height="46" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#FFFFFF" bgcolor="#999999" >
					<tr align="center" bgcolor="#f0f0f0"  >
						<td>id</td>
						<td>用户名</td>
						<td>类型</td>
						<td>区域</td>
						<td colspan="2">操作</td>
					</tr>
					{if $gaoceng}
						{foreach from=$gaoceng key=key item=item}
						<tr bgcolor="#FFFFFF">
							<td align="center">{$item.id}</td>
							<td align="center">{$item.name}</td>
							<td align="center">{$item.group}</td>
							<td align="center"> - </td>
							<td align="center">
								<a href="#" class="biandan_juzhong" onClick="modify_user({$item.id});" >修改</a></td>
							<td align="center">
								<a href="#" class="biandan_juzhong" onClick="delete_user({$item.id});" >删除</a></td>
						</tr> 
						{/foreach}
					{/if}
					
					{if $qudao}
						{foreach from=$qudao key=key item=item}
						<tr bgcolor="#FFFFFF">
							<td align="center">{$item.id}</td>
							<td align="center">{$item.name}</td>
							<td align="center">{$item.group}</td>
							<td align="center">{$item.ainfo.name}</td>
							<td align="center">
								<a href="#" class="biandan_juzhong" onClick="modify_user({$item.id});" >修改</a></td>
							<td align="center">
								<a href="#" class="biandan_juzhong" onClick="delete_user({$item.id});" >删除</a></td>
						</tr> 
						{/foreach}
					{/if}
					<tr bgcolor="#FFFFFF"><td colspan="6"><a href="#" class="biandan_juzhong" onClick="add_user();">添加用户</a></td></tr>
				</table>
			</div>

<div id="modify_api" title="用户管理">
	<form id="modify_api_form" method="post">
		<input value="0" name="id" id="user_id" type="hidden" />
		用户名：<input type="text" name="name" id="user_name" style="width:150px" /> </br/>
		类　型：<select id="user_level" name="level" style="width:150px" onChange="select_area();">
			<option value="10">高层</option>
			<option value="5">渠道</option>
		</select></br/>
		密　码：<input type="password" name="pwd" id="user_pwd" style="width:150px" /> <span id="chgpwd"><input type="checkbox" name="chgpwd" value="1" />修改密码</span></br/>
		<span id="user_area_span" style="display:none;">
		区　域：<select name="acode" id="user_area" style="width:150px">
				{foreach from=$able_areas item=item}<option value="{$item.code}">{$item.name}</option>{/foreach}
			</select></br/>
		</span>
		<input type="button" value="提交" onClick="user_submit();"/>
	</form>
</div>
		</td>
{include file="footer.tpl"}

<script type="text/javascript">
{literal}
$j(function(){
		$j(".message_output").fadeOut(5000);		//消息框淡出
		//modify api ajax - todo
		$j('#modify_api').dialog({
			autoOpen: false,
			width: 400,
			bgiframe: true,
			resizable: false,
			modal: true, //遮罩
			close: function(){  }
		});
});
function modify_user(id)
{
	$j('#chgpwd').show();
	$j.ajax({
		url:'index.php?c=user&a=edit&id='+id,
		dataType:'json',
		success:function(data){
			if(!data) alert('未知错误');
			else if(data.status) {
				data = data.data;
				$j('#user_id').val(data.id);
				$j('#user_name').val(data.name);
				$j('#user_level').val(data.level);
				if(data.level < 10){
					$j('#user_area').val(data.acode);
					$j('#user_area_span').show();
				}
				$j('#modify_api').dialog("open");
			}
		}
	});
}
function select_area(){
	var level = $j('#user_level').val();
	if(level < 10){
		$j('#user_area_span').show();
	}else{
		$j('#user_area_span').show();
	}
}
function delete_user(id){
	var res = confirm('确定删除该用户吗？');
	if(!res) return true;
	$j.ajax({
		url:'index.php?c=user&a=delete&id='+id,
		dataType:'json',
		success:function(data){
			if(!data) alert('未知错误');
			else if(data.status) { alert('删除成功'); location.reload(); }
			else alert('删除失败'+ data.msg);
		}
	});
}
//添加用户
function add_user()
{
	$j('#chgpwd').hide();
	$j('#modify_api').dialog("open");
}
function user_submit(){
	$j.ajax({
		url:'index.php?c=user&a=update',
		data:$j('#modify_api_form').serialize(),
		type: "post",
		dataType:'json',
		success:function(data){
			if(!data) alert('未知错误');
			else if(data.status) { alert('成功'); location.reload(); }
			else alert('失败'+ data.msg);
		}
	});
}
{/literal}
</script>