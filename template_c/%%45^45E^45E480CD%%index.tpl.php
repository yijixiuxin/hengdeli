<?php /* Smarty version 2.6.26, created on 2013-06-27 06:10:47
         compiled from index.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<td width="202" height="167"  valign="top" ><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "left.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
		<td valign="top">
		<?php if ($this->_tpl_vars['action'] == 'index'): ?>
<?php echo '
<style>
.normal-table{ border-left:1px solid #000;border-top:1px solid #000; margin:10px auto;}
.normal-table td{border-bottom:1px solid #000;border-right:1px solid #000;}
div.indexneirong p{margin:5px 0;}
.intro-qitem{text-align:left; padding-left:5px;}
.intro-answer{text-align:left; padding-left:5px;}
</style>
'; ?>

			<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
				<tr>
					<td width="10" class="paget3">&nbsp;</td>
					<td class="indexbiaoti"><strong>盛时和尚时神秘顾客检测自动化报告系统</strong></td>
				</tr>
				<tr>
					<td height="3" colspan="2" background="images/line.gif"></td>
				</tr>
			</table>
			
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<div class="indexneirong" style="line-hight:1.5em;">
						<p><strong>一、项目介绍</strong></p>
						<p>盈联公司受亨得利集团委托对全国盛时和尚时门店进行神秘顾客调查工作，本项目旨在有效地督促加强各门店销售顾问的专业性、提高门店管理能力，从而提升所有门店的营运水平和竞争力。</p>
						<p>检测范围：全国23个区域，127家门店。2013年度共检测四期，每期实际检查门店，可能会根据区域运营情况相应增删。</p>
						<p>
							<table class="normal-table" cellspacing="0" cellpadding="1">
								<tr>
									<td style="width:70px;">区域</td><td>北京</td><td>成都</td><td>福建</td><td>哈尔滨</td><td>杭州</td><td>杭州新宇</td><td>合肥</td><td>江西</td><td>昆明</td><td>南京</td><td>南宁</td><td>青岛</td><td>上海</td><td>沈阳</td><td>苏州</td><td>太原</td><td>天津</td><td>温州</td><td>乌鲁木齐</td><td>无锡新宇</td><td>武汉</td><td>郑州</td><td>重庆</td>
								</tr>
								<tr>
									<td>门店数量</td><td>15</td><td>4</td><td>5</td><td>2</td><td>1</td><td>5</td><td>6</td><td>1</td><td>4</td><td>2</td><td>3</td><td>6</td><td>9</td><td>3</td><td>9</td><td>5</td><td>9</td><td>12</td><td>3</td><td>6</td><td>8</td><td>5</td><td>4</td>
								</tr>
							</table>
						</p>
						
						<p><strong>二、系统介绍</strong></p>
						<p>《盛时和尚时神秘顾客检测自动化报告系统》（以下简称《系统》）为盈联 “零售行业服务营销文化提升管理系统2.0”的客户定制化版本。该系统适用于零售、服务等窗口行业服务营销文化质量提升研究咨询。通过该系统客户方可在线获取相关研究咨询的成果。盈联开发该系统的初衷为与客户紧密协作、提高效率。紧密协作——委托方可以通过在线平台零距离参与项目进展整个过程，并且可以实时查看最原始的调查记录及实证；提高效率——不论是委托方受托方还是被访者只要通过各类终端就可以参与到项目当中。本系统结果呈现分为两种形式：集团高层阅读版本（简称高层阅读报告）和各区域阅读版本（简称区域阅读报告）。</p>
						
						<p><strong>三、指标与算法</strong></p>
						<p>1、指标体系</p>
						<p>每个门店均按照统一的三级指标体系进行量化评价，一级指标为总分，二级指标（即模块）包括“门店环境、员工形象、欢迎和接近顾客、了解需求与产品介绍、产品展示与试戴、回应异议和礼貌道别”7项，三级指标（即检测指标）为各二级指标下设的检测细项共计57项。三级指标体系见下表。</p>
						<p>
							<table class="normal-table" cellspacing="0" cellpadding="1">
								<tr>
									<td rowspan="2">总分</td>
									<td rowspan="2">模块</td>
									<td colspan="3">检测指标</td>
								</tr>
								<tr>
									<td>测评内容</td><td>评分说明</td><td style="width:40px;">分值</td>
								</tr>
								<tr>
									<td rowspan="65">门店每期成绩（100分）</td>
								</tr>
								<tr>
									<td rowspan="13">门店环境（14分）</td>
								</tr>
								<tr><td class="intro-qitem">门店招牌是否干净无损？</td><td>是（1分） 否（0分）</td><td>1</td></tr>
								<tr>
									<td class="intro-qitem">门店橱窗是否干净（无灰尘、污渍、指印）？</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">店门是否干净（无灰尘、污渍、指印）？</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">店内地面是否干净（无明显污渍、脚印）？</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">店内墙面是否干净明亮（无污渍、指印等）</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">店内柜台是否干净（无灰尘、污渍、指印）？</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">店内柜台上的物品是否干净、整洁有序</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">柜台内部是否干净</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">柜台内手表及道具是否干净，无缺损</td>
									<td>是（2分） 否（0分）</td>
									<td>2</td>
								</tr>
								<tr>
									<td class="intro-qitem">柜台中手表陈列是否展示角度整齐，无歪斜或倾倒</td>
									<td>是（2分） 否（0分）</td>
									<td>2</td>
								</tr>
								<tr>
									<td class="intro-qitem">柜台中的手表包膜是否美观，无气泡或破损</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">产品价格标签是否按规定摆放整齐且价格明显易见</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								
								<tr>
									<td rowspan="14">仪容仪表/精神状态（16分）</td>
								</tr>
								<tr><td class="intro-qitem">销售顾问是否统一穿着工服，并保持平整/干净</td><td>是（2分） 否（0分）</td><td>2</td></tr>
								<tr>
									<td class="intro-qitem">销售顾问是否统一穿着黑色皮鞋且皮鞋干净无灰尘</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">女销售顾问是否穿着统一颜色丝袜 </td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">女销售顾问是否头发干净，发型整齐（留海不遮眼睛,短发或将长发束起或盘发，无夸张染发或挑染，不使用夸张发夹，无怪异发型）</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">女销售顾问是否淡妆上岗（至少可以看到唇彩、口红等）</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">男销售顾问无明显胡须，头发干净，发型整齐</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">销售顾问不留长指甲、指甲干净，可涂透明或者接近肤色的淡色指甲油（不可涂彩色指甲油）</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">销售顾问头部佩戴饰品是否符合规范（耳环左右最多各一只（左右对称），只能是耳钉款式；男销售顾问不可佩戴耳环及耳钉）</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">销售顾问颈部佩戴饰品是否符合规范</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">销售顾问手部佩戴饰品是否符合规范（最多可佩戴一枚素圈或内镶钻戒指、一块手表，手上不可佩戴手镯等其他饰物）</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">销售顾问是否口气清新且无嚼口香糖或其它食物的现象</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">进行销售时，销售顾问是否保持良好的精神状态，站姿是否正确（不插兜、不叉腰、不扶靠柜台、墙壁等）</td>
									<td>是（2分） 否（0分）</td>
									<td>2</td>
								</tr>
								<tr>
									<td class="intro-qitem">销售顾问在店内无闲聊、大声喧哗、嬉笑等不良行为（如聊天、照镜子、吃东西、打哈欠、伸懒腰等） </td>
									<td>是（2分） 否（0分）</td>
									<td>2</td>
								</tr>
								
								<tr>
									<td rowspan="6">欢迎和接近顾客（11分）</td>
								</tr>
								<tr>
									<td class="intro-qitem">入店后，不管店内是否繁忙，是否都有销售顾问主动向您微笑问好</td>
									<td class="intro-answer">销售顾问主动向我微笑问好，感觉真诚亲切—3分； <br/>销售顾问向我问好，但没有微笑，感觉机械式的应付—1分； <br/>没人向我微笑问好—0分；</td>
									<td>3</td>
								</tr>
								<tr>
									<td class="intro-qitem">入店后，不管店内是否繁忙，是否都有销售顾问询问您是否需要帮助</td>
									<td class="intro-answer">主动询问是否需要帮助 / 店内繁忙，销售顾问主动知会我稍等—2分； <br/>未主动询问帮助/未知会我稍等—0分</td>
									<td>2</td>
								</tr>
								<tr>
									<td class="intro-qitem">当您表示先随便看看，销售顾问是否仍在旁给您关注?</td>
									<td class="intro-answer">销售顾问亲切地表示，有问题您再叫我，并在一旁随时关注—2分； <br/>销售顾问未有任何表示，只是在一旁关注—1分； <br/>未给予关注—0分</td>
									<td>2</td>
								</tr>
								<tr>
									<td class="intro-qitem">在您寻求帮助时，是否立即给予您回应</td>
									<td class="intro-answer">是（如销售顾问繁忙，请其他销售顾问接待我或请我稍等）—2分； <br/>否—0分； </td>
									<td>2</td>
								</tr>
								<tr>
									<td class="intro-qitem">当您环顾店铺时，销售顾问的态度是否亲切自然（没有流露出打量您或是势利的表情）</td>
									<td class="intro-answer">面带微笑，亲切自然，令人感到舒适—2分； <br/>态度一般，没有微笑，不舒适也不反感—1分； <br/>表情僵硬，令人反感 —0分</td>
									<td>2</td>
								</tr>
								
								<tr>
									<td rowspan="8">了解需求与产品介绍（17分）</td>
								</tr>
								<tr><td class="intro-qitem">问候之后，销售顾问是否通过主动沟通向您询问基本的购买需求或动机</td>
									<td class="intro-answer">主动询问您的购买需求—2分； <br/>没有任何发问—0分</td><td>2</td></tr>
								<tr>
									<td class="intro-qitem">在您讲话时，销售顾问是否认真倾听并做出相应的回应</td>
									<td class="intro-answer">认真倾听且回应及时—2分； <br/>不耐烦/没有回应/不理解我的需求—0分</td>
									<td>2</td>
								</tr>
								<tr>
									<td class="intro-qitem">除了您自己选择的腕表或销售顾问首先向您推荐的腕表之外，销售顾问是否主动介绍其它腕表让您作比较</td>
									<td class="intro-answer">销售顾问主动推荐其它腕表供我作选择，并给予了我合理的建议—3分； <br/>销售顾问除首次给我推荐腕表之后，没有推荐其它腕表，表现不够积极—1分； <br/>至始至终未推荐任何产品—0分&nbsp; </td>
									<td>3</td>
								</tr>
								<tr>
									<td class="intro-qitem">介绍每一款产品时，销售顾问是否清晰告知您品牌名称或所属系列名称？</td>
									<td>是（2分） 否（0分）</td>
									<td>2</td>
								</tr>
								<tr>
									<td class="intro-qitem">当您对某款手表产生兴趣后，销售顾问是否进一步详细介绍了该款手表的具体情况（如产地、品牌、材质、款式等），并联系以上信息把带给您的实在的优势及好处讲解给您听？</td>
									<td class="intro-answer">销售顾问结合我的需求，详细专业地介绍了该产品，以及带给我的优势与好处-3分； <br/>销售顾问较详细地介绍了产品，但未联系带给我的优势与好处-2分；<br/>销售顾问只是简单地介绍了产品-1分； <br/>销售顾问未介绍产品的相关信息，也说不出该产品的特点-0分。</td>
									<td>3</td>
								</tr>
								<tr>
									<td class="intro-qitem">在为您介绍产品时，销售顾问是否语速适中，表达清晰？</td>
									<td class="intro-answer">销售顾问语速适中，表达清晰，向我清楚介绍产品信息—2分； <br/>销售顾问语速过快或过慢或表达不清晰，我根本未听清楚—0分</td>
									<td>2</td>
								</tr>
								<tr>
									<td class="intro-qitem">销售顾问最后推荐的产品是否符合您的预期或引发了您的购买欲望？</td>
									<td class="intro-answer">该产品非常符合我此次的购买需求，我有很强的购买欲望—3分； <br/>一般，该产品让我感觉还可以，但不是最适合我的—1分； <br/>不好，销售顾问所推荐的产品和我所需要的差距较大/销售顾问未推荐任何产品—0分</td>
									<td>3</td>
								</tr>
								
								<tr>
									<td rowspan="13">产品展示与试戴（21分）</td>
								</tr>
								<tr>
									<td class="intro-qitem">销售顾问是否鼓励您感受或试戴腕表？</td>
									<td class="intro-answer">积极主动，邀请我成功试戴—2分； <br/>只是口头邀请试戴但未采取进一步实际行动，或在我拒绝时未作进一步鼓励—1分； <br/>没有鼓励试戴—0分 </td>
									<td>2</td>
								</tr>
								<tr>
									<td class="intro-qitem">若有空位，销售顾问是否请您坐下？并同时照顾到您同行的伙伴及随身物品，让人感觉舒服？</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">销售顾问在为您展示产品时是否佩戴手套并且手套干净无破损？</td>
									<td class="intro-answer">佩戴手套且手套干净—2分<br/>手套不干净（破损）/未佩戴手套—0分</td>
									<td>2</td>
								</tr>
								<tr>
									<td class="intro-qitem">销售顾问在为您展示产品时是否使用托盘并且托盘干净无破损？</td>
									<td class="intro-answer">使用托盘并且托盘干净—2分<br/>托盘不干净（破损）/&nbsp; 未使用托盘—0分&nbsp; </td>
									<td>2</td>
								</tr>
								<tr>
									<td class="intro-qitem">销售顾问一次同时为您展示的产品是否不超过3块？</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">销售顾问每次为您介绍和展示产品时，是否保持吊牌或价签始终挂在或贴在产品上？</td>
									<td>是（1分） 否（0分）</td>
									<td>1</td>
								</tr>
								<tr>
									<td class="intro-qitem">试戴时，销售顾问是否主动帮您试戴，且佩戴动作熟练流畅，避免碰及手部配饰？/如您为异性选购腕表，销售顾问是否自己主动试戴或请同事帮忙试戴给您观看效果？</td>
									<td class="intro-answer">销售顾问主动帮助试戴，手法熟练，并避免碰及我手部的其它配饰/我戴有手表时协助我解下一只手表，避免两只手表同时展示/我为异性选购腕表，销售顾问自己主动试戴或请同事帮忙试戴并请我观看效果—2分 <br/>销售顾问帮助试戴，但手法生疏（如戴反等）/我戴有手表时，未帮我解下，一起试戴—1分  <br/>未主动帮助试戴/未自己试戴或请同事帮忙试戴—0分</td>
									<td>2</td>
								</tr>
								<tr>
									<td class="intro-qitem">试戴时，销售顾问是否有邀请您观看效果（如提供镜子）？并适当给予意见？</td>
									<td class="intro-answer">邀请观看效果，并给予了我适当的意见，让我感觉舒服—2分 <br/>邀请观看效果，但未给予任何意见—1分 <br/>未关注我的试戴效果—0分</td>
									<td>2</td>
								</tr>
								<tr>
									<td class="intro-qitem">在您试戴的过程中，销售顾问是否关切您的感受？</td>
									<td class="intro-answer">关注我的感受，能询问的进一步需求，并给予相应的意见—2分 <br/>完全没有关注我的感受—0分 </td>
									<td>2</td>
								</tr>
								<tr>
									<td class="intro-qitem">当您在几个表款之间犹豫不决时，销售顾问是否专业地为您推荐适合您的产品？</td>
									<td class="intro-answer">是，销售顾问可以提出专业的建议，帮我作出抉择—2分  <br/>一般，销售顾问给了我一些建议，但是感觉不能令我信服—1分  <br/>否，销售顾问只是看着我，没有任何建议—0分</td>
									<td>2</td>
								</tr>
								<tr>
									<td class="intro-qitem">在整个服务过程中，销售顾问是否不加解释毫无理由地留您一个人在柜台前？</td>
									<td class="intro-answer">未留我一人在柜台前； <br/>或在发生其他紧急事情时，向我解释并致歉后离开； <br/>在事情处理完后及时返回给予我服务—2分 毫无理由地留我一人在柜台前—0分</td>
									<td>2</td>
								</tr>
								<tr>
									<td class="intro-qitem">在整个服务过程中，销售顾问是否始终亲切、耐心的态度并给予您鼓励？</td>
									<td class="intro-answer">销售顾问自始自终亲切耐心服务—2分 <br/>销售顾问看起来不耐烦、急躁，不耐心、不亲切—0分</td>
									<td>2</td>
								</tr>
								
								<tr>
									<td rowspan="5">回应异议（12分）</td>
								</tr>
								<tr><td class="intro-qitem">当您提出异议或疑问时，销售顾问的态度如何？</td>
									<td class="intro-answer">面带微笑，耐心聆听，对我的感受表示理解—3分 <br/>没有流露出负面的情绪，但也未能对我的感受表达理解—1分 <br/>流露出不满/不屑/不理睬，或是与我争辩—0分</td><td>3</td></tr>
								<tr>
									<td class="intro-qitem">当您对推荐的产品流露出不满意时，销售顾问的举措是？</td>
									<td class="intro-answer">耐心地询问不满意的原因，并给予了调整意见/给予的回应打消了我的疑虑—3分； <br/>没有询问不满意的原因，只是一味地推荐之前的产品—1分； <br/>未向我做进一步的了解或推荐/不理睬—0分</td>
									<td>3</td>
								</tr>
								<tr>
									<td class="intro-qitem">当您提出异议或疑问后，销售顾问的解答或回应技巧如何？</td>
									<td class="intro-answer">回应真诚，专业，解答令我信服—3分<br/> 回应敷衍/解释不清，不够令我信服—1分 <br/>没有回应，或与我争辩—0分</td>
									<td>3</td>
								</tr>
								<tr>
									<td>当您问及亨得利或所访门店与其它零售商相比的情况时，销售顾问的表现如何</td>
									<td class="intro-answer">自信、积极地向我展示公司/门店的优势，如企业规模、良好信誉、售后服务等—3分； <br/>作了解释，但令我不够信服—1分； <br/>贬低其它同行/答不出/无所谓企业的荣誉感—0分</td>
									<td>3</td>
								</tr>
								
								<tr>
									<td rowspan="5">礼貌道别（9分）</td>
								</tr>
								<tr><td class="intro-qitem">当您表示暂不购买时，销售顾问是否依然保持友好？</td>
									<td class="intro-answer">很好，仍旧保持友好—2分 <br/>不好，在我表示暂不购买后，销售顾问的脸色很难看—0分</td><td>2</td></tr>
								<tr>
									<td class="intro-qitem">当您已流露出较明确的购买意向，但表示此次暂不购买后，销售顾问是否把我视为潜在顾客，采取销售动作，进一步争取销售机会？ </td>
									<td class="intro-answer">销售顾问对我的购买意向很重视，且做了全部三项动作—3分 <br/>销售顾问做了两项动作—2分 <br/>销售顾问只做了一项动作—1分 销售顾问不在乎我，未对我作任何的争取—0分</td>
									<td>3</td>
								</tr>
								<tr>
									<td class="intro-qitem">销售顾问是否在您还未离开柜台时就急于去忙其他工作？</td>
									<td class="intro-answer">在我离店前，至始至终关注我—2分 <br/>在我离开前，就急于去忙其他工作—0分</td>
									<td>2</td>
								</tr>
								<tr>
									<td class="intro-qitem">销售顾问是否向您友好道别？</td>
									<td class="intro-answer">销售顾问微笑向我道别，感谢我的到访，并邀请再次光临—2分 销售顾问只是象征性的道别（比如点头示意等）—1分 没有人向我道别—0分 </td>
									<td>2</td>
								</tr>
							</table>
						</p>
						
						<p>2、每期门店得分率算法</p>
						<p>　检测指标（A1）：该检测指标实际得分/该检测指标满分值</p>
						<p>　模块（A2）：模块所包含的全部检测指标实际得分之和/该模块满分值</p>
						<p>　总分(A3)：该门店所有检测指标实际得分之和</p>
						<p>3、每期区域得分率算法</p>
						<p>　检测指标(B1)：该区域内所有门店的A1之和/该区域门店数量</p>
						<p>　模块(B2)：该区域所有门店的A2之和/该区域门店数量</p>
						<p>　总分(B3)：该区域所包含门店的A3之和/该区域门店数量</p>
						<p>4、	每期全国成绩算法</p>
						<p>　检测指标(C1)：所有区域的B1之和/集团门店数量</p>
						<p>　模块(C2)：所有区域的B2之和/集团门店数量</p>
						<p>　总分(C3)：所有门店的A3之和/集团门店数量</p>
						
						<p><strong>四、专用名词解释</strong></p>
						<p>1、 中位数：统计学名词，是指将统计总体当中的各个变量值按大小顺序排列起来，形成一个数列，处于变量数列中间位置的变量值就称为中位数。当变量值的项数N为奇数时，处于中间位置的变量值即为中位数；当N为偶数时，中位数则为处于中间位置的2个变量值的平均数。中位数是以它在所有标志值中所处的位置确定的全体单位标志值的代表值，不受分布数列的极大或极小值影响，从而在一定程度上提高了中位数对分布数列的代表性。</p>
						<p>2、 众数：统计学名词，在统计分布上具有明显集中趋势点的数值，代表数据的一般水平（众数可以不存在或多于一个）。众数是样本观测值在频数分布表中频数最多的那一组的组中值。</p>
						<p>3、 算术平均数、中位数、众数的区别：三者都是平均指标，表示总体分布的集中趋势。算术平均数是一种数值平均数，它表示的是总体标志总量与总体总量的对比关系；中位数、众数是一种位置平均数。中位数是一个统计总体或分布数列中处于中等水平。</p>
						<p>4、 本期服务细节短板：指本期所有检测指标中，得分居于最后10位的检测指标。</p>
						<p>5、 上期服务细节短板：指上期所有检测指标中，得分居于最后10位的检测指标。</p>
						<p>6、	本期表现不佳区域：指本期总分小于集团平均分的区域。</p>
						<p>7、	导致表现不佳的具体服务细节短板：指以本期不佳区域为统计母体，被挖掘出来的集团得分率排名最后10位的检测指标。</p>
						<p>8、	上期表现不佳的区域：指上期总分低于集团平均分的区域。</p>
						<p>9、	 上期表现达标的区域：指上期总分同时满足大于85分且不低于平均分的区域。</p>
						<p>10、 上期支持达标的服务细节亮点：以上期达标的区域为统计母体，挖掘出上期集团得分率排名最前10位的服务细节亮点（检测指标）。</p>
						
						<p class="indexneirong2">
							盈联咨询集团<br>
							盈联公共事务研究事业群<br>
							盈联商务智能研究院
						</p>
					</div>
				</td>
			</tr>
			</table>
		<?php elseif ($this->_tpl_vars['action'] == 'about'): ?>
			<table width="100%" border="0" cellpadding="2" cellspacing="2">
				<tr>
					<td width="10" class="paget3">&nbsp;</td>
					<td class="indexbiaoti"><strong>盈联介绍</strong></td>
				</tr>
				<tr>
					<td height=3 colspan="2" background="images/line.gif"></td>
				</tr>
			</table>
			
			<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr valign="top">
				<td height=188>
					<p class="indexneirong" >盈联在新加坡，日本东京，中国北京设有服务公司，主要面向亚太及全球新兴市场客户，提供利益相关者研究与咨询服务。盈联具有MSPA、ESOMAR、AMA及中国国家统计局甲级涉外调查许可资质。 </p>
					<p class="indexneirong" >&nbsp;</p>
					<p class="indexneirong" >盈联中国在上海、杭州、广州、呼和浩特、南京、贵阳、香港设有办事处。盈联在深圳设有一个商务智能研究院（STAWIN BI），盈联建有50人坐席的CATI（电脑辅助电话访问）中心，可同步满足不同语种被访对象的全球化调查执行委托项目。E clear是盈联的全球在线调查服务品牌，向客户提供商业信息采集服务。</p>
					<p class="indexneirong" >&nbsp;</p>
					<p class="indexneirong" >盈联主要服务金融、电信、IT和能源四大行业委托客户。服务营销文化咨询，是盈联的利益相关者研究的核心，该类咨询包括如为中行、建行、农行提供的网点神秘人调查，为中行、建行、农行提供的服务质量提升咨询辅导，为渣打、花旗百货大楼提供的品牌满意度跟踪研究等诸多课题。</p>
					<p class="indexneirong" >&nbsp;</p>
					<p class="indexneirong" >神秘顾客研究事业部是盈联最核心的部门之一，在全国共有5000名神秘顾客，他们经过严格系统的培训后，从事百货大楼网点、奢侈品专柜、汽车4S店、加油站、IT专卖店、运营商营业厅的暗访调作；我们有专业的暗访所需摄录器材，整合商务智能研究院的开发资源，我们为百货大楼量身开发《百货大楼网点服务营销文化提升系统》，该系统实现了调查成果解读的工具化、多媒体化、网络化。</p>
				</td>
			</tr>
			
			<tr valign="top">
				<td height=10>
					<table width="563" border="1" align="center" cellpadding="1" cellspacing="1">
					<tr align="center">
						<td width="555"><img src="images/Stawin Research & Consulting Co.jpg" width="555" height="355" ></td>
					</tr>
					<tr>
						<td>盈联(STAWIN)是亚太区神秘顾客协会正式会员</td>
					</tr>
					</table>
				</td>
			</tr>
			</table>
		<?php else: ?>
			<p>&nbsp;</p>
		<?php endif; ?>
		</td>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>