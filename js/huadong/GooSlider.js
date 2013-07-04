/*滑动条定义--GooSlider类*/
//sliderDiv :要被绑定的已被JQUERY封装的DOM对象，必须要有其ID
//property  :JSON变量，SLIDER的详细参数设置
function GooSlider(sliderDiv,property){
	this.$div=sliderDiv;
	this.$id=this.$div.attr("id");
	//保存传入参数
	this.$minValue=property.minValue;
	this.$maxValue=property.maxValue;
	this.$length=property.length;
	this.$step=(this.$maxValue-this.$minValue)/this.$length; //每一个像素代表的单位值
	this.$direction="h";
	if(property.direction){
		this.$direction=property.direction;
	}
	this.$snapMode=false;
	if(property.snapMode){
		this.$snapMode=property.snapMode;
	}
	this.$autoUpdate=false;
	if(property.autoUpdate){
		this.$autoUpdate=property.autoUpdate;
	}

	this.$div.addClass("Slider");
	if(this.$direction=="h") this.$div.css({width:(this.$length+14)+"px"});
	else if(this.$direction=="v") this.$div.css({height:(this.$length+14)+"px",float:"left"});
	//(重新)设置刻度的单位值，如果参数为NULL或者0时，则表示取消刻度显示
	this.setCalStep=function(cal){
		this.$calStep=cal;
		cal=this.$div.children(".cal_"+this.direction);
		if(cal.length>0)
			cal.remove();
		temp="<div class='cal_"+this.$direction+"'>";
		num=this.$length/this.$calStep;
		for(i=0;i<num;i++){
			if(this.$direction=="h") temp+="<b style='margin-right:"+(this.$calStep-1)+"px'></b>";
			else if(this.$direction=="v") temp+="<b style='margin-bottom:"+(this.$calStep-1)+"px'></b>";
		}
		temp+="<b></b></div>";
		this.$div.prepend(temp);	
	};
	this.$calStep=0;
	if(property.calStep){
		this.setCalStep(property.calStep);
	}
	if(this.$direction=="h"){
		temp="<div class='bar'><div class='blankBar_"+this.$direction+"' style='width:"+(this.$length+12)+"px'><div class='fill'></div></div>";
		temp+="<div id='cursor_"+this.$id+"' class='cursor_"+this.$direction+"' style='top:0px;left:0px;'></div></div>";
	}
	else{
		temp="<div class='bar'><div class='blankBar_"+this.$direction+"' style='height:"+(this.$length+12)+"px'><div class='fill'></div></div>";
		temp+="<div id='cursor_"+this.$id+"' class='cursor_"+this.$direction+"' style='top:"+this.$length+"px;left:0px;'></div></div>";
	}
	this.$div.append(temp);
	this.$fill=null;
	//设置是否填充，如果参数为NULL或者FALSE，则为否
	this.setFill=function(fill){
		if(fill)	this.$fill=this.$div.children(".bar").children("div:eq(0)").children("div:eq(0)");
		else this.$fill=null;
	};
	if(property.fill){
		this.setFill(true);
	}
	//设定值，并将游标滑动至相关地方
	this.setValue=function(value){
	  if(value&&value>=this.$minValue&&value<=this.$maxValue){
		var wide=value/this.$step;
		if(this.$direction=="h"){
			if(this.$fill) this.$fill.css({width:(wide+6)+"px"});
			$("#cursor_"+this.$id).css("left",wide+"px");
		}
		if(this.$direction=="v"){
			if(this.$fill) this.$fill.css({height:(wide+6)+"px",top:(this.$length-wide+6)+"px"});
			$("#cursor_"+this.$id).css("top",(this.$length-wide)+"px");
		}
	  }
	  this.$input.val(value||0);
	  this.$input.change();
	};
	//设置是否显示TIP标签,如果参数为NULL或者FALSE，则为否(或者是取消当前控件的TIP显示功能)
	this.setShowTip=function(tip){
		this.$div.children(".tip").remove();
		if(tip){
			this.$tipUnit=tip;//设置标签中显示的单位，如果没有单位，则传入空字符串“”
			this.$div.append("<div class='tip'></div>");
		}
		else{
			this.$tipUnit=null;
		}
	};
	this.setShowTip(property.showTip);
	//设置初始化隐藏INPUT
	this.$input=null;
	if(property.inputName){
		this.$input=$("<input type='hidden' id='"+(property.inputId? property.inputId:this.$id+"_input")+"' name='"+property.inputName+"'/>");
	}
	else
		this.$input=$("<input type='hidden' id='"+(property.inputId? property.inputId:this.$id+"_input")+"'/>");
	this.$div.append(this.$input);
	this.setValue(property.initValue||this.minValue);

	$("#cursor_"+this.$id).bind("mousedown",
					  {temp:this.$direction,
					   length:this.$length,
					   fill:this.$fill,
					   input:this.$input,
					   auto:this.$autoUpdate,
					   snap:this.$snapMode,
					   step:this.$step,
					   tipUnit:this.$tipUnit,
					   tip:this.$div.children(".tip")
					   },
	function(e){
    	if(!e) e = window.event;   //如果是IE
		var temp=e.data.temp;
		var length=e.data.length;
		var pos;
		var inthis=$(this);
		var fill=e.data.fill;
		var step=e.data.step;
		var auto=e.data.auto;
		var input=e.data.input;
		var tipUnit=e.data.tipUnit;
		var tip=e.data.tip;
		var lastValue=input.val();//保存上一个值
		if(temp=="h"){
			oldP=parseInt(inthis.attr("offsetLeft")); 
			pos = e.clientX - oldP;
		}
		else{
			oldP=parseInt(inthis.attr("offsetTop")); 
			pos =  e.clientY - oldP;
		}
		if(e.data.tipUnit!=null) {//如果要显示TIP
			var abso=getElCoordinate(this);
			tip.html(input.val()+tipUnit);
			tip.css("display","block");
			var tipTop=abso.top-22,tipLeft=abso.left+7-(tip.attr("offsetWidth")/2);
			tip.css({left:tipLeft+"px",top:tipTop+"px"});
		}
		$(this).removeClass();
		$(this).addClass("cursor_"+temp+"_active");
		var snap=e.data.snap? (e.data.snap/step) : null;		
    	document.onmousemove = function(e){
			if(!e) e = window.event;//如果是IE
			var value=null,no=false;
			if(temp=="h"){
				var p=e.clientX - pos;
				if(p<0)p=0;
				else if(p>length)p=length;
				if(snap!=null&&p%snap>0){
					p=p-p%snap;
					if(p<oldP) p+=snap;
					no=true;
				}
    			inthis.css({left:p + "px"});
				if(fill) fill.css({width:(p+6)+"px"});
				if(auto){
					value=p*step;
					input.val(value);
					if(value!=lastValue)input.change();
				}
				if(tipUnit){
					var abso=getElCoordinate(inthis.context);
					tip.html((p*step)+tipUnit);
					tip.css({left:(abso.left+7-tip.attr("offsetWidth")/2)+"px"});
				}
			}
			else if(temp=="v"){
				var p=e.clientY - pos;
				if(p<0)p=0;
				else if(p>length)p=length;
				if(snap!=null&&p%snap>0){
					p=p-p%snap;
					if(p<oldP) p+=snap;
					no=true;
				}
    			inthis.css("top",p + "px");
				if(fill) fill.css({height:(length-p+6)+"px","top":(p+6)+"px"});
				if(auto){
					value=(length-p)*step;
					input.val(value);
					if(value!=lastValue)input.change();
				}
				if(tipUnit){
					var abso=getElCoordinate(inthis.context);
					tip.html(((length-p)*step)+tipUnit);
					tip.css({top:(abso.top-22)+"px",left:(abso.left+7-tip.attr("offsetWidth")/2)+"px"});
				}
			}
			lastValue=value;
			oldP=p;
		};
		document.onmouseup = function(e){
			inthis.removeClass();
			inthis.addClass("cursor_"+temp);
			if(tipUnit) tip.css("display","none");
			if(input&&!auto){
				if(temp=="h") input.val(parseInt(inthis.css("left"))*step);
				else input.val((length-parseInt(inthis.css("top")))*step);
				input.change();
			}
			document.onmousemove = null;
			document.onmouseup=null; 
		};
	});
	this.$div.children(".bar").children("div:eq(0)").bind("mousedown",
					{id:this.$id,
					 direct:this.$direction,
					 length:this.$length,
					 fill:this.$fill,
					 input:this.$input,
					 snap:this.$snapMode,
					 step:this.$step
					},
	function(e){
		if(!e) e = window.event;   //如果是IE
		var direct=e.data.direct;
		var length=e.data.length;
		var cursor=$("#cursor_"+e.data.id);
		var fill=e.data.fill;
		var snap=e.data.snap/e.data.step;
		if(direct=="h"){
			p = e.clientX-getElCoordinate(this).left-7;
			if(p<0)p=0;
			else if(p>length)p=length;
			if(snap!=null&&p%snap>0){
				p=p-p%snap;
			}
			cursor.animate({left:p + "px"},"fast");
			if(fill) fill.animate({width:p+"px"},"fast");
			e.data.input.val(p*e.data.step); e.data.input.change();
		}
		else{
			p = e.clientY-getElCoordinate(this).top-7;
			if(p<0)p=0;
			else if(p>length)p=length;
			if(snap!=null&&p%snap>0){
				p=p-p%snap+snap;
			}
			cursor.animate({top:p + "px"},"fast");
			if(fill) fill.animate({height:(length-p+6)+"px",top:(p+6)+"px"},"fast");
			e.data.input.val(p*e.data.step); e.data.input.change();
		}
		
	});
	//清掉这个SLIDER对象
	this.cleanSlider=function(){
		this.$div.remove();
	};
	//绑定当INPUT的值改变时，触发的事件,FN参数为一个外部函数,如果参数为NULL，则表示取消事件的绑定
	this.bindValueChange=function(fn){
		this.$input.change(fn);
	};
	//设置游标拖动时是否更新VALUE值，如果参数为NULL或者FALSE，则为否
	this.setAutoUpdate=function(auto){
		this.$autoUpdate=auto;
	};
	//设置SNAP模式下的步进数值，如果参数为NULL，则表示取消SNAP模式
	this.setSnapMode=function(snap){
		this.$snapMode=snap;
	};
}
//获取一个DIV的绝对坐标的功能函数
function getElCoordinate(dom) {
  var t = dom.offsetTop;
  var l = dom.offsetLeft;
  while (dom.offsetParent) {
	dom=dom.offsetParent;
    t += dom.offsetTop;
    l += dom.offsetLeft;
  }; return {
    top: t,
    left: l
  }
}
//将此类的构造函数加入至JQUERY对象中
jQuery.extend({
	createGooSlider: function(sliderDiv,property) {
		return new GooSlider(sliderDiv,property);
  }
}); 