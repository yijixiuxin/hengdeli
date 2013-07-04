//��Ӣ�Ľ�ȡ
String.prototype.cn_substr = function(start, len, replace)
{
	var s = this.replace(/([\u4e00-\u9fa5])/g,"\xff\$1");
	if(s.length > len)
	{
		if(s.length == this.length)return this.substring(start, len);
		return (s.substring(start, len).replace(/\xff/g, '') + replace);
	}
	
		alert(this);
	//return s.substring(start, len).replace(/\xff/g, '');
}

function addslashes( str ) {
    // Escapes single quote, double quotes and backslash characters in a string with backslashes  
    // *     example 1: addslashes("kevin's birthday");
    // *     returns 1: 'kevin\'s birthday'
 
    return (str+'').replace(/([\\"'])/g, "\\$1").replace(/\0/g, "\\0");
}

/*
function animateBar(){
	$(".item .precent").each(function(){
		var pre;
		$(this).css("width","0px");
		if ($(this).attr("precent"))
		{
			pre = $(this).attr("precent");
		}
		else
		{
			pre = 0;
		}alert(pre);
		$(this).animate({width: pre},"slow");
	});
}*/

/**
 * ��ʾ��Դ����
 *
 * @param string from_type  ��Դ���� globa:������� �����id���֣�ĳ�������
 * @param string con_id  Ҫ��ʾ������id
 * @param htmlElement obj Ҫ������dom����
 * @param event e ���ݵ��¼�
 * @param boolean allow_close �Ƿ�����ر� Ĭ������
 * @return void
 */
function areaInfo(from_type,con_id,obj,e,allow_close)
{
	if(typeof allow_close == 'undefined'){
		allow_close = true;
	}
	/*var e = e || window.event;
	var target= e.target || e.srcElement;
	if (jQuery.browser.msie && obj != target){//ie���м��BUG
		$("#" + con_id).css("margin-top","-4px");
	}
	target = $(target);*/
	areaDom = $("#" + con_id);
	if ($(obj).attr("class").indexOf("selected") >= 0 && allow_close){ //���ڴ�״̬ && ����ر�
		$(obj).removeClass("selected");
		hideArea(areaDom);
	}else{ //δ�򿪵�״̬
		$(obj).addClass("selected"); //�õ��������ѡ��״̬
		if($('#'+con_id).attr('loaded') == '1'){ //����д˽ڵ��˾�ֱ����ʾ��������ȡ�����ڵ㣬Ȼ����ʾ����
			showArea(areaDom);
		}else{
			//AJAX��ȡ���
			var cgi = '';
			if(from_type == 'global'){//��ȡ���������Դ��Ϣ����Ҫ��id��aid���� &debug=1//200909251058
				cgi = "http://survey.sports.sina.com.cn/api/fusioncharts/get_from_data.php?sid="+Conf.sid+"&jsoncallback=?";
			}else{
				cgi = "http://survey.sports.sina.com.cn/api/fusioncharts/get_from_data.php?sid="+Conf.sid+"&aid="+from_type+"&jsoncallback=?";	
				
			}
			setTimeout(function(){//setTimeout�� ie request mybe aborted
				$.getJSON(cgi,
					function(json_data){
						//���json��ݻ��html����
						var html = get_html_by_from_json_data(from_type,json_data);
						////alert(html);
						//��html����׷�ӵ�ָ��������ʾdom��
						$('#'+con_id).html(html);
						//ִ��html����Ҫִ�е�js����
						//eval_function(from_type,json_data);						
						//��ȡ��ɺ����
						$('#'+con_id).attr('loaded','1');
						showArea(areaDom,function(){
												  eval_function(from_type,json_data);	//ִ��html����Ҫִ�е�js����  �ص���ʽ��������⿨�����
												  });
					}); 
				},0);			
		}
	}
}
function showArea(obj,completa_callback){
	$(obj).find(".menu-area").css("visibility","visible");
	//obj.animate({height:"show"},"slow");
	obj.animate({height:"show"},{duration:0,complete:function(){
																   //$(obj).get(0).scrollIntoView(false);
																   if(typeof completa_callback != 'undefined' && completa_callback.constructor == Function){
																	   	completa_callback();
																	   }
																   }});
	
}
function hideArea(obj){
	$(obj).find(".menu-area").css("visibility","hidden");
	obj.animate({height:"hide"},{duration:0});//"slow");
}

/**
 * ��������������Դ��Ϣ
 */
function hide_global_from(){
	if ($('#S_Menu_13').length>0 && $('#S_Menu_13').attr("class").indexOf("selected") >= 0){ //����ѡ��״̬
		$('#S_Menu_13').removeClass("selected");
		hideArea($('#from_global_host'));
		
	}	
}

/**
 * ����ܲ�������ʱ��ʾ�����û����� �˷���Ҳ�������û����� 
 */
function show_global_from_by_vote_num(){
	areaInfo('global','from_global_host',document.getElementById('S_Menu_13'),null,true);	
}

//���json��ݵĳ���
function get_json_length(json_data){
	var i = 0;
	if(!json_data || json_data.constructor != Object){
		return i;
	}
	for(var pro in json_data){
		i++;
	}
	return i;
}

/**
 * �����Դ���ͺ���Դjson��ݻ��html����
 * @param string from_type ��Դ���� global������������Դ ��id��ĳ���𰸵�id ��ʶ���һ������Ĵ�
 * @param number question_index ��������
 * @param number answer_index ������
 * @return void
 */
function get_html_by_from_json_data(from_type,json_data){
	var html = '';
	//global ����������Դ��ĳ���𰸵���Դhtml�ṹ�е����
	if(from_type != 'global'){
		html += '<div class="box-area">';
	}
	//php�ӿڷ������Ϊ�գ�
	if(!json_data || (json_data.constructor == Array && json_data.length <=0)){
		html += '<div style="color:#aaa; padding:10px;">���޷������</div>';
		return html;
	}
	//*******************
	// ƴ����ǩ��menu����
	//*******************
	html += '<ul class="menu-area">';
	var index = 1;
	for(var pro in json_data){
		if(index == 1){// first
			html += '<li id="from_'+from_type+'_menu_'+index+'" class="fst">';	
		}else{
			html += '<li id="from_'+from_type+'_menu_'+index+'">';		
		}
		html += '<a href="javascript:void(0);" onfocus="this.blur();" title="��'+pro+'">��'+pro+'</a></li>';
		index ++;
	}
	html += '</ul>';
	//*******************
	// ƴ������(con)����
	//*******************
	var index = 1;
	for(var pro in json_data){
		//���ݿ�
		html += '<div id="from_'+from_type+'_con_'+index+'" style="display:none;">';
			//flash����
			html += '<div class="area-flash">';
                    html += '<div id="from_'+from_type+'_flash_div_'+index+'" align="center"></div>';
			html += '</div>';
			html += '<div class="area-flash-tip" id="from_'+from_type+'_flash_div_'+index+'_tip"></div>';//ѡ����ҵ���û�ռ40% 
					
		html += '</div>';
		index++;
	}  

	if(from_type != 'global'){
		html += '</div>';
	}
	return html;
}

/**
 * �Զ�̬ƴ������Դhtml�����ڵ�js����ִ�к��� �Ա���innerHTML js���벻ִ������
 * @param string from_type ��Դ���� global������������Դ ��id��ĳ���𰸵�id ��ʶ���һ������Ĵ�
 * @param object/array json_data php�ӿڷ��ص����
 * @return boolean
 */
function eval_function(from_type,json_data){
	if(!from_type || !json_data || (json_data.constructor==Array && json_data.length <= 0)){
		return false;
	}
	//ִ��fusioncharts write js code
	var index = 1;
	for(var pro in json_data){
		window['from_'+from_type+'_fc_'+index] = new FusionCharts("http://www.sinaimg.cn/dy/fusioncharts/v3/flash/Pie3D.swf?PBarLoadingText=Loading&XMLLoadingText=Loading&ParsingDataText=Loading","from_"+from_type+"_flash_"+index, "620", "200", "0", "0");
		//window['from_'+from_type+'_fc_'+index] = new FusionCharts("http://www.sinaimg.cn/dy/deco/2009/0903/survey/Pie3D.swf","from_"+from_type+"_flash_"+index, "620", "200", "0", "0");
		var xml = get_chart_xml_by_json_data(from_type,json_data[pro], index);
		//alert(xml)
		window['from_'+from_type+'_fc_'+index].addParam('wmode','transparent');
		window['from_'+from_type+'_fc_'+index].setDataXML(xml);
		window['from_'+from_type+'_fc_'+index].render("from_"+from_type+"_flash_div_"+index);
		//׷��area-flash-tip, e.g :ѡ����ҵ���û�ռ40% 
		document.getElementById('from_'+from_type+'_flash_div_'+index+'_tip').innerHTML = get_area_flash_tip(pro,json_data[pro]);
		index++;
	}                   
	//ִ����Դ��ǩ�л�����
	var index = 1;
	window['from_'+from_type+'_SubShow'] = new SubShowClass("none",'onmousedown',0,'selected','');
	for(var pro in json_data){
		window['from_'+from_type+'_SubShow'].addLabel("from_"+from_type+"_menu_"+index,"from_"+from_type+"_con_"+index);
		index++;
	}
	return true;
}

/**
 * ���ͳ����ݲ��������ʾ
 * @param string item_name ��������� ���� ��ҵ ���� ��
 * @param json item_data ĳ�������json���
 * @return string
 */
function get_area_flash_tip(item_name,item_data){
	if(item_name == '����'){//������ʾ
		return '';	
	}
	tip = "ѡ��"+item_name +"���û�ռ";
	percent = '';
	if(item_data && item_data['δ֪'] && item_data['δ֪']['percent']){
		percent = parseFloat(item_data['δ֪']['percent']);
		percent = 100 - percent;
		percent = percent.toFixed(1);
		percent += '%';
	}else{
		percent = '100%';	
	}	
	return tip + percent;
}

/**
 * ���json data ƴ��fusioncharts��xml
 * @param string from_type ��Դ���� global������������Դ ��id��ĳ���𰸵�id ��ʶ���һ������Ĵ�
 * @param object/array json_data php�ӿڷ��ص�json���
 * @param number index ��Դ��Ŀ���� ���簴���� ��ѧ��
 * @return boolean
 */
function get_chart_xml_by_json_data(from_type, item_data, index){
	if(!from_type || !index || !item_data || (item_data.constructor==Array && item_data.length <= 0)){
		return '<chart></chart>';
	}
	xml = '';
	//���ڱ�ͼ
	xml += "<chart baseFontSize='12' numberSuffix='%25' showAboutMenuItem='0' decimals='1' chartTopMargin='0' chartBottomMargin='0' caption='' showValues='1' formatNumberScale='0' bgAlpha='0' logoURL='http://www.sinaimg.cn/dy/deco/2009/0903/survey/wmark_pie3.png' logoPosition='TR' startingAngle='60'>";
	
	var subindex = 1;
	for(var pro in item_data){
		//���Ҫ��ʾ��toolText
		var toolText = pro;
		toolText = toolText.replace(/\'/g,'');
		toolText.cn_substr(0,30,'...');
		toolText = addslashes(toolText);
		toolText += ' (' + item_data[pro].percent + '%)';
	
		if(subindex != 1){
			xml +="<set label='"+subindex+"' value='"+item_data[pro].amount+"' toolText='" + toolText + "' displayValue='" + toolText + "' />"; //link='javascript:selectit2(\""+from_type+"\","+index+","+subindex+")'
			
		}else{
			xml +="<set label='"+subindex+"' value='"+item_data[pro].amount+"' toolText='" + toolText + "' displayValue='" + toolText + "' isSliced='1' />"; //link='javascript:selectit2(\""+from_type+"\","+index+","+subindex+")'
		}
		
		subindex++;
	}	
	xml +="</chart>";
	return xml;
}

/**
 * ˢ��ҳ��
 * @param boolean bol �ӷ������ϼ���ҳ���� true����  false���� Ĭ��ֵΪfalse
 * @return void
 */
function window_refresh(bol){
	bol = !!bol;
	location.reload(bol);
}

//init
try{	
	$(document).ready(function(){
		//��ʼ�����������
		animateBar();
	});
}catch(e){}