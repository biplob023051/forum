
jQuery(document).ready(function($){
	
	$.ajaxSetup({
		cache: false,
		beforeSend :function(){
			$("#busy-indicator").fadeIn();
		}
	});
	$(document).ajaxComplete(function(){
		$("#busy-indicator").fadeOut();
	});

	$.fn.equalHeight=function() {
		var tallest = 0;
		var group =$(this);
		group.css({'height':'auto'});
		group.each(function() {
			var thisHeight = $(this).height();
			if(thisHeight > tallest) {
				tallest = thisHeight;
			}
		});
		group.height(tallest);
	}

	$.fn.equalWidth=function() {
		var widest = 0;
		var group =$(this);
		//group.css({'height':'auto'});
		group.each(function() {
			var thisWidth = $(this).innerWidth();
			if(thisWidth > widest) {
				widest = thisWidth;
			}
		});
		group.width(widest);
	}

	$('#wrapper').css('min-height', ($(window).outerHeight()-$('#footer').outerHeight()));
	$.fn.resizeAll = function(){
		$('#footer,#wrapper').css({"height":"auto"});
		var windowHeight = $(window).outerHeight();
		var pageHeight = $('#wrapper').outerHeight();
		var footerHeight = $('#footer').outerHeight();
		if(windowHeight>(pageHeight+footerHeight)){
			$('#wrapper').css('min-height', (windowHeight-footerHeight));
		}
		$('#mainmenu li > a').equalHeight();
	}
	$().resizeAll();

	$(window).bind('load',function(){
		$().resizeAll();
	});

	$(window).bind('resize',function(){
		var resizeId;
		clearTimeout(resizeId);
		resizeId = setTimeout(function(){
			$().resizeAll();
		}, 500);
	});

	if("onorientationchange" in window){
		window.addEventListener("orientationchange", function() {
			$().resizeAll();
		}, false);
	}

});

function errorMsg(msg, element){
	$('#' + element).show();
	$('#' + element).html('<div class="alert alert-danger"><a class="close" data-dismiss="alert" href="#">&times;</a><i class="fa fa-ban"></i><span id="errorMessage"> '+msg+'</span></div>');
}