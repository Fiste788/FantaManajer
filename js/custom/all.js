$(document).ready(function(){
	$("#menu").css("top","-" + $("#menu").height() + "px");
	//$("select, input, textarea").uniform();
	$("#messaggioContainer").effect("pulsate", { times: 2 }, 1000, function(){
		$(".messaggio").hover(function () {
			$(this).fadeTo("fast",0.2);
		},function () {
			$(this).fadeTo("fast",1);
		});
	});
	$(".messaggio").click(function () {
		$(this).fadeOut("slow");
	});
	$("#debugShow").click(function(){
		$("#debug").slideToggle();
	});
	$("#click-menu").toggle(
	function(event){
		$("#menu").animate({right:'0px',top:'0px'},'slow');
		$("#topRightBar").attr("title","Nascondi menu");
	},
	function(event){
		$("#menu").animate({right:'-' + $("#menu").width() + 'px',top:'-' + $("#menu").height() + 'px'},'slow');
		$("#topRightBar").attr("title","Mostra menu");
	});
});
;
(function($){function countdown(el,options){var calc=function(target,current){var current=current||new Date();if(current>=target){return true}var o={};var remain=Math.floor((target.getTime()-current.getTime())/1000);o.days=Math.floor(remain/86400);remain%=86400;o.hours=Math.floor(remain/3600);remain%=3600;o.minutes=Math.floor(remain/60);remain%=60;o.seconds=remain;o.years=Math.floor(o.days/365);o.months=Math.floor(o.days/30);o.weeks=Math.floor(o.days/7);return o};var getWeek=function(date){var onejan=new Date(date.getFullYear(),0,1);return Math.ceil((((date-onejan)/86400000)+onejan.getDay())/7)};var options=$.extend({date:new Date(),modifiers:[],interval:1000,msgFormat:'%d [day|days] %hh %mm %ss',msgNow:'Now !'},options);var tokens={y:new RegExp('\\%y(.+?)\\[(\\w+)\\|(\\w+)\\]','g'),M:new RegExp('\\%M(.+?)\\[(\\w+)\\|(\\w+)\\]','g'),w:new RegExp('\\%w(.+?)\\[(\\w+)\\|(\\w+)\\]','g'),d:new RegExp('\\%d(.+?)\\[(\\w+)\\|(\\w+)\\]','g'),h:new RegExp('\\%h(.+?)\\[(\\w+)\\|(\\w+)\\]','g'),m:new RegExp('\\%m(.+?)\\[(\\w+)\\|(\\w+)\\]','g'),s:new RegExp('\\%s(.+?)\\[(\\w+)\\|(\\w+)\\]','g')};var formatToken=function(str,token,val){return(!tokens[token])?'':str.match(/\[|\]/g)&&(str.replace(tokens[token],val+'$1'+((parseInt(val,10)<2)?'$2':'$3'))||'')||str.replace('%'+token,val)};var format=function(str,obj){var o=str;o=formatToken(o,'y',obj.years);o=formatToken(o,'M',obj.months);o=formatToken(o,'w',obj.weeks);o=formatToken(o,'d',obj.days);o=formatToken(o,'h',obj.hours);o=formatToken(o,'m',obj.minutes);o=formatToken(o,'s',obj.seconds);return o};var update=function(){var date_obj=calc(cd.date);var str="";if(date_obj===true){cd.stop();clearInterval(cd.id);$(cd.el).html(options.msgNow);return true}else{if(date_obj.days!=0){if(date_obj.days==1)str=str+'<span class="number">'+date_obj.days+'</span> giorno ';else str=str+'<span class="number">'+date_obj.days+'</span> giorni '}if(date_obj.hours!=0||date_obj.days!=0){if(date_obj.hours==1)str=str+'<span class="number">'+date_obj.hours+'</span> ora ';else str=str+'<span class="number">'+date_obj.hours+'</span> ore '}if(date_obj.minutes==1)str=str+'<span class="number">'+date_obj.minutes+'</span> minuto ';else str=str+'<span class="number">'+date_obj.minutes+'</span> minuti ';if(date_obj.days==0){if(date_obj.seconds==1)str=str+'<span class="number">'+date_obj.seconds+'</span> secondo ';else str=str+'<span class="number">'+date_obj.seconds+'</span> secondi '}$(cd.el).html(str)}};var apply_modifiers=function(modifiers,date){if(modifiers.length===0){return date}var modifier_re=/^([+-]\d+)([yMdhms])$/;var conversions={s:1000,m:60*1000,h:60*60*1000,d:24*60*60*1000,M:30*24*60*60*1000,y:365*24*60*60*1000};var displacement=0;for(var i=0,n=modifiers.length;i<n;++i){var match=modifiers[i].match(modifier_re);if(match!==null){displacement+=parseInt(match[1],10)*conversions[match[2]]}}return new Date(date.getTime()+displacement)};var cd={id:setInterval(update,options.interval),el:el,start:function(){return new countdown($(this.el),options)},stop:function(){return clearInterval(this.id)},date:apply_modifiers(options.modifiers,options.date)};$(el).data('countdown',cd);update();return $(el).data('countdown')}$.fn.countdown=function(args){if(this.get(0))return new countdown(this.get(0),args)}})(jQuery);
$('#countdown div').countdown({
	msgFormat: '<span class="number">%d</span> [giorno|giorni], <span class="number">%h</span> [ora|ore] <span class="number">%m</span> [minuto|minuti] e <span class="number">%s</span> [secondo|secondi]',
	date: d,
	msgNow:'Tempo scaduto'
});
