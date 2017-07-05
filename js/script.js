/*----------------------------- Home Background Fit to Window -----------------------*/

if($.browser.device != (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()))){	

		function fit(){
			var altura = $(window).height();
			$('#HeadSlider').height(altura);
			$('#HeadSlider .flexslider').height(altura);
			$('#HeadSlider .flex-viewport').height(altura);
			$('.slide').height(altura);
		}	
		
		setInterval(function(){myTimer()}, 500);
			
		function myTimer(){
			if($('#HeadSlider').hasClass('slider') ){
				fit();
			}
			if($('#HeadSlider .flex-viewport').hasClass('flex-viewport') ){
				fit();
			}
			if($('.slide').hasClass('slide') ){
				fit();
			}
		}
		
}

$(document).ready(function(){
	
$("html, body").animate({ scrollTop: 0 });

/* --------------------------------------- Loader ---------------------------------*/	

	setInterval(function(){loader()}, 1000);
	
	function loader(){
			
			$('#loading').addClass('fadeOut');
			$('.pace').addClass('fadeOut');
			
			}	
	setInterval(function(){loader2()}, 1500);
	
	function loader2(){
			
			$('#loading').remove();
			$('.pace').remove();
			
			} 
/* --------------------------------------- Parallax ---------------------------------*/

   $window = $(window);
        $('div[data-type="background"]').each(function() {
            var $scroll = $(this);

            $(window).scroll(function() {
                var yPos = -($window.scrollTop() / $scroll.data('speed'));
                var coords = '50% ' + yPos + 'px';
                $scroll.css({ backgroundPosition: coords });
            });
        });	  		
		   
/* --------------------------------- Smooth Scrolling ---------------------------*/

	$('#menu a, footer #menu-footer a ').click(function(e){
		var scrollAnchor = $(this).attr('href');
		e.preventDefault();
		$('html, body').animate({
			scrollTop: $(scrollAnchor).offset().top
		}, 900);
		
	});
	
/*-------------------------- LangMenu --------------------*/	
			
			$("#LangBtn").click(function(){
					$("#LangMenuWrapper").slideDown();
					$("#LangBtn").addClass('rotate');
			});
			
			// Hide All Dropdown	
			$('html').click(function() {
			$("#LangMenuWrapper").slideUp();
			$("#LangBtn").removeClass('rotate');
			});
			
			$("#LangBtn").click(function(event){
				 event.stopPropagation();
				 });
	
/* --------------------------------------- Go Top --------------------------------*/

	$("#gotoup").hide();
	
	 $(function() {
        $(window).scroll(function() {
			  
            if ($(this).scrollTop() > 87) {
               
		   			$('header').addClass("HeadFit");
						$('#gotoup').fadeIn();
                	$("#gotoup").removeClass("hideDown");
                	$("#gotoup").addClass("slideUp");

		    
            } else {

		    			 $('header').removeClass("HeadFit");
						 $('#gotoup').fadeOut();
                   $("#gotoup").removeClass("slideUp");
                   $("#gotoup").addClass("hideDown");
            }
				
				 if ($(this).scrollTop() > 200) {
					 
					 $('#circular-animation').addClass('start-anim');
					 
					 }					 
					
        });
		  
/* --------------------------------------- Circular Animation --------------------------------*/
	
			$('#circular-animation').hover(function() {
					$(this).removeClass('start-anim');
	  			});
	    
        // scroll body to 0px on click
        $('#gotoup a').click(function() {
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
    });
	 
/* --------------------------------------- Nav ico --------------------------------*/	 
	 
		$('#nav-ico').click(function() {
				
						$('#menu-mobile').toggleClass('up down');
						$('#menu-mobile.down').slideDown();
						$('#menu-mobile.up').slideUp();
					
				});
		 
				 // Hide All Dropdown	
				$('html').click(function() {
				
					$('#menu-mobile').slideUp();
			
				
				});
				$('#nav-ico').click(function(event){
					 event.stopPropagation();
			
			 });
			 
			 $('#LoginWrapper').click(function() {
				  $(this).removeClass('wrong');
				 });
		   	
});





