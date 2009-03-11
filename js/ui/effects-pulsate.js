/*
 * jQuery UI Effects Pulsate 1.7
 *
 * Copyright (c) 2009 AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * http://docs.jquery.com/UI/Effects/Pulsate
 *
 * Depends:
 *	effects.core.js
 */
(function($){$.effects.pulsate=function(o){return this.queue(function(){var el=$(this);var mode=$.effects.setMode(el,o.options.mode||'show');var times=o.options.times||5;var duration=o.duration?o.duration/2:$.fx.speeds._default/2;if(mode=='hide')times--;if(el.is(':hidden')){el.css('opacity',0);el.show();el.animate({opacity:1},duration,o.options.easing);times=times-2}for(var i=0;i<times;i++){el.animate({opacity:0},duration,o.options.easing).animate({opacity:1},duration,o.options.easing)};if(mode=='hide'){el.animate({opacity:0},duration,o.options.easing,function(){el.hide();if(o.callback)o.callback.apply(this,arguments)})}else{el.animate({opacity:0},duration,o.options.easing).animate({opacity:1},duration,o.options.easing,function(){if(o.callback)o.callback.apply(this,arguments)})};el.queue('fx',function(){el.dequeue()});el.dequeue()})}})(jQuery);
