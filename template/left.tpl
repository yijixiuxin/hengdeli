<table width="202" height="461" border="0" cellpadding="0" cellspacing="0" style="background:url('images/image_09.gif') no-repeat;">
	<tr align="center">
		<td height="207" width="202" valign="top" >
			<table width="194" height="158" align="right">
				<tr>
					<td height="52" ></td>
				</tr>
				<tr>
					<td width="583" height="98" >
					<form action="index.php" method="get">
						<input type="hidden" name="a" value="{$a}" />
						<input type="hidden" name="c" value="{$c}" />
						
						<select name="stage" id="stage" style="width:150px;margin:5px 0;">
							<option value="0">测评期数</option>
						{if $stages}
							{foreach from=$stages item=item}
							<option value="{$item.stage}" {if $item.c}selected="selected"{/if}>第{$item.stage}期</option> 
							{/foreach}
						{/if}
						</select><br/>
						
						<!--{*<select name="area" id="area" style="width:150px;margin:5px 0;">
							<option value="">区域</option>
						{if $areas}
							{foreach from=$areas item=item}
							<option value="{$item.code}" {if $item.c}selected="selected"{/if}>{$item.name}</option> 
							{/foreach}
						{/if}
						</select><br/>
						
						<select name="mendian" id="mendian" style="width:150px;margin:5px 0;">
							<option value="">门店</option>
						{if $mendian}
							{foreach from=$mendian item=item}
							<option value="{$item.code}" {if $item.c}selected="selected"{/if}>{$item.name}</option> 
							{/foreach}
						{/if}
						</select><br/>*}-->
						
						<input  type="submit" style="width:150px;margin:5px 0;" value="提交" />
					</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr align="center">
		<td height="254" valign="top" >
		<table width="141" height="60">
			<tr align="center">
				<td><span style="padding-left:20px"><!--img height="49" align="absmiddle" width="165" src="images/zhongguo2.gif"--></span></td>
				<!--td align="center"><img src="images/zhongguo.gif" width="165" height="49" align="absmiddle"  /></td-->
			</tr>
		</table>
		</td>
	</tr>
</table>
{literal}
<script>
$j(document).ready(function(){
	$j('#area').change(function(){
		$j.ajax({
			url : 'ajax.php?a=get_mendian_by_area',
			data : "acode="+$j('#area').val(),
			type:'post',
			dataType: 'json',
			success:function(data){
				if(!data.status){
					alert('area error');
					return false;
				}
				var str = '', data = data.data;
				for(var o in data){
					str += '<option value="'+ data[o].code +'">'+data[o].name+'</option>';
				}
				$j('#mendian').html(str);
			}
		});
	});
});
</script>
{/literal}