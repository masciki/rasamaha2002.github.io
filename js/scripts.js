(function(d){var g={type:"html",content:"",url:"",ajax:{},ajax_request:null,closeOnEsc:!0,closeOnOverlayClick:!0,clone:!1,overlay:{block:void 0,tpl:'<div class="arcticmodal-overlay"></div>',css:{backgroundColor:"#000",opacity:0.6}},container:{block:void 0,tpl:'<div class="arcticmodal-container"><table class="arcticmodal-container_i"><tr><td class="arcticmodal-container_i2"></td></tr></table></div>'},wrap:void 0,body:void 0,errors:{tpl:'<div class="arcticmodal-error arcticmodal-close"></div>',autoclose_delay:2E3,ajax_unsuccessful_load:"Ошибка"},openEffect:{type:"fade",speed:400},closeEffect:{type:"fade",speed:400},beforeOpen:d.noop,afterOpen:d.noop,beforeClose:d.noop,afterClose:d.noop,afterLoading:d.noop,afterLoadingOnShow:d.noop,errorLoading:d.noop},j=0,e=d([]),m={isEventOut:function(a,b){var c=!0;d(a).each(function(){d(b.target).get(0)==d(this).get(0)&&(c=!1);0==d(b.target).closest("HTML",d(this).get(0)).length&&(c=!1)});return c}},f={getParentEl:function(a){var b=d(a);return b.data("arcticmodal")?b:(b=d(a).closest(".arcticmodal-container").data("arcticmodalParentEl"))?b:!1},transition:function(a,b,c,e){e=void 0==e?d.noop:e;switch(c.type){case "fade":"show"==b?a.fadeIn(c.speed,e):a.fadeOut(c.speed,e);break;case "none":"show"==b?a.show():a.hide(),e()}},prepare_body:function(a,b){d(".arcticmodal-close",a.body).unbind("click.arcticmodal").bind("click.arcticmodal",function(){b.arcticmodal("close");return!1})},init_el:function(a,b){var c=a.data("arcticmodal");if(!c){c=b;j++;c.modalID=j;c.overlay.block=d(c.overlay.tpl);c.overlay.block.css(c.overlay.css);c.container.block=d(c.container.tpl);c.body=d(".arcticmodal-container_i2",c.container.block);b.clone?c.body.html(a.clone(!0)):(a.before('<div id="arcticmodalReserve'+c.modalID+'" style="display: none" />'),c.body.html(a));f.prepare_body(c,a);c.closeOnOverlayClick&&c.overlay.block.add(c.container.block).click(function(b){m.isEventOut(d(">*",c.body),b)&&a.arcticmodal("close")});c.container.block.data("arcticmodalParentEl",a);a.data("arcticmodal",c);e=d.merge(e,a);d.proxy(h.show,a)();if("html"==c.type)return a;if(void 0!=c.ajax.beforeSend){var k=c.ajax.beforeSend;delete c.ajax.beforeSend}if(void 0!=c.ajax.success){var g=c.ajax.success;delete c.ajax.success}if(void 0!=c.ajax.error){var l=c.ajax.error;delete c.ajax.error}var n=d.extend(!0,{url:c.url,beforeSend:function(){void 0==k?c.body.html('<div class="arcticmodal-loading" />'):k(c,a)},success:function(b){a.trigger("afterLoading");c.afterLoading(c,a,b);void 0==g?c.body.html(b):g(c,a,b);f.prepare_body(c,a);a.trigger("afterLoadingOnShow");c.afterLoadingOnShow(c,a,b)},error:function(){a.trigger("errorLoading");c.errorLoading(c,a);void 0==l?(c.body.html(c.errors.tpl),d(".arcticmodal-error",c.body).html(c.errors.ajax_unsuccessful_load),d(".arcticmodal-close",c.body).click(function(){a.arcticmodal("close");return!1}),c.errors.autoclose_delay&&setTimeout(function(){a.arcticmodal("close")},c.errors.autoclose_delay)):l(c,a)}},c.ajax);c.ajax_request=d.ajax(n);a.data("arcticmodal",c)}},init:function(a){a=d.extend(!0,{},g,a);if(d.isFunction(this))if(void 0==a)d.error("jquery.arcticmodal: Uncorrect parameters");else if(""==a.type)d.error('jquery.arcticmodal: Don\'t set parameter "type"');else switch(a.type){case "html":if(""==a.content){d.error('jquery.arcticmodal: Don\'t set parameter "content"');break}var b=a.content;a.content="";return f.init_el(d(b),a);case "ajax":if(""==a.url){d.error('jquery.arcticmodal: Don\'t set parameter "url"');break}return f.init_el(d("<div />"),a)}else return this.each(function(){f.init_el(d(this),d.extend(!0,{},a))})}},h={show:function(){var a=f.getParentEl(this);if(!1===a)d.error("jquery.arcticmodal: Uncorrect call");else{var b=a.data("arcticmodal");b.overlay.block.hide();b.container.block.hide();d("BODY").append(b.overlay.block);d("BODY").append(b.container.block);b.beforeOpen(b,a);a.trigger("beforeOpen");if("hidden"!=b.wrap.css("overflow")){b.wrap.data("arcticmodalOverflow",b.wrap.css("overflow"));var c=b.wrap.outerWidth(!0);}e.not(a).each(function(){d(this).data("arcticmodal").overlay.block.hide()});f.transition(b.overlay.block,"show",1<e.length?{type:"none"}:b.openEffect);f.transition(b.container.block,"show",1<e.length?{type:"none"}:b.openEffect,function(){b.afterOpen(b,a);a.trigger("afterOpen")});return a}},close:function(){if(d.isFunction(this))e.each(function(){d(this).arcticmodal("close")});else return this.each(function(){var a=f.getParentEl(this);if(!1===a)d.error("jquery.arcticmodal: Uncorrect call");else{var b=a.data("arcticmodal");!1!==b.beforeClose(b,a)&&(a.trigger("beforeClose"),e.not(a).last().each(function(){d(this).data("arcticmodal").overlay.block.show()}),f.transition(b.overlay.block,"hide",1<e.length?{type:"none"}:b.closeEffect),f.transition(b.container.block,"hide",1<e.length?{type:"none"}:b.closeEffect,function(){b.afterClose(b,a);a.trigger("afterClose");b.clone||d("#arcticmodalReserve"+b.modalID).replaceWith(b.body.find(">*"));b.overlay.block.remove();b.container.block.remove();a.data("arcticmodal",null);d(".arcticmodal-container").length||(b.wrap.data("arcticmodalOverflow")&&b.wrap.css("overflow",b.wrap.data("arcticmodalOverflow")))}),"ajax"==b.type&&b.ajax_request.abort(),e=e.not(a))}})},setDefault:function(a){d.extend(!0,g,a)}};d(function(){g.wrap=d(document.all&&!document.querySelector?"html":"body")});d(document).bind("keyup.arcticmodal",function(a){var b=e.last();b.length&&b.data("arcticmodal").closeOnEsc&&27===a.keyCode&&b.arcticmodal("close")});d.arcticmodal=d.fn.arcticmodal=function(a){if(h[a])return h[a].apply(this,Array.prototype.slice.call(arguments,1));if("object"===typeof a||!a)return f.init.apply(this,arguments);d.error("jquery.arcticmodal: Method "+a+" does not exist")}})(jQuery);

$(document).ready(function() {
	$('.login_link').click(function() {
		$('#login_modal').arcticmodal({
			closeOnEsc: true,
			closeOnOverlayClick: true,
			openEffect: {
				type: 'none'
			},
			closeEffect: {
				type: 'none'
			},
			overlay: {
				css: {
					background: '#000',
					opacity: .6
				}
			},
			afterOpen: function(data, el) {
				$('#login_modal input[type="text"]').focus();
			}
		});
	});
	calcthis();
	$('.calculator #percent').change(function () {
		calcthis();
	});
});


function calcthis(a) { 
	if($('.calculator').length > 0) {
		var perc = document.getElementById("percent").value;
		var planperc=new Array(0,0,0,0,0,0,0);
		var depo = document.getElementById("deposit").value;
		if (perc == "perc1") {planperc=Array(116 , 116 , 116 , 116 , 116 , 116, 116); min=3; max=100;};
		if (perc == "perc2") {planperc=Array(146.5 , 146.5 , 146.5 , 146.5 , 146.5 , 146.5, 146.5); min=100; max=1000;};
		if (perc == "perc3") {planperc=Array(190 , 190 , 190 , 190 , 190 , 190, 190); min=1000; max=3000;};
		if (perc == "perc4") {planperc=Array(124.5 , 124.5 , 124.5 , 124.5 , 124.5 , 124.5, 124.5); min=3000; max=5000;};
		if (perc == "perc5") {planperc=Array(140 , 140 , 140 , 140 , 140 , 140, 140); min=5000; max=10000;};
		if (depo < min) {
			document.getElementById("inpvar1").innerHTML = "Error!";
			document.getElementById("inpvar2").innerHTML = "Error!";
			$('#info_modal').arcticmodal({
				closeOnEsc: true,
				closeOnOverlayClick: true,
				openEffect: {
					type: 'none'
				},
				closeEffect: {
					type: 'none'
				},
				overlay: {
					css: {
						background: '#000',
						opacity: .6
					}
				},
				beforeOpen: function(data, el) {
					$('.info_modal .info_text .text').append('Минимальный депозит по этому плану составляет $'+min);
					$('.calculator input[type="text"]').val(min);
					calcthis();
				},
				afterClose: function(data, el) {
					$('.info_modal .info_text .text').empty();
				}
			});
		}
		else if (depo > max) {
			document.getElementById("inpvar1").innerHTML = "Error!";
			document.getElementById("inpvar2").innerHTML = "Error!";
			$('#info_modal').arcticmodal({
				closeOnEsc: true,
				closeOnOverlayClick: true,
				openEffect: {
					type: 'none'
				},
				closeEffect: {
					type: 'none'
				},
				overlay: {
					css: {
						background: '#000',
						opacity: .6
					}
				},
				beforeOpen: function(data, el) {
					$('.info_modal .info_text .text').append('Максимальный депозит по этому плану составляет $'+max);
					$('.calculator input[type="text"]').val(max);
					calcthis();
				},
				afterClose: function(data, el) {
					$('.info_modal .info_text .text').empty();
				}
			});
		}
		else {
			document.getElementById("inpvar1").innerHTML = planperc[0];
			document.getElementById("inpvar2").innerHTML = planperc[0] * depo / 100;	
			if ( depo >200) {
				document.getElementById("inpvar1").innerHTML = planperc[1];
				document.getElementById("inpvar2").innerHTML = planperc[1] * depo / 100;
				if ( depo > 500) {
					document.getElementById("inpvar1").innerHTML = planperc[2];
					document.getElementById("inpvar2").innerHTML = planperc[2] * depo / 100;
					if ( depo > 1000) {
						document.getElementById("inpvar1").innerHTML = planperc[3];
						document.getElementById("inpvar2").innerHTML = planperc[3] * depo / 100;
						if ( depo > 2500) {
							document.getElementById("inpvar1").innerHTML = planperc[4];
							document.getElementById("inpvar2").innerHTML = planperc[4] * depo / 100;
							if ( depo > 5000) {
								document.getElementById("inpvar1").innerHTML = planperc[5];
								document.getElementById("inpvar2").innerHTML = planperc[5] * depo / 100;
								if ( depo > 10000) {
									document.getElementById("inpvar1").innerHTML = planperc[6];
									document.getElementById("inpvar2").innerHTML = planperc[6] * depo / 100;
								}
							}
						}
					}
				}
			}
		}
	}
}