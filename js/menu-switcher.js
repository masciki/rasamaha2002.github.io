$(document).ready(function () {
	
	$('a.Inactive').click( function () {
			
		$('a.Active').removeClass('Active').addClass('Inactive');	
		$('span.Active').removeClass('Active').addClass('Inactive')
		
	  	}); 
	   
	$('header ul li').click( function () {
	
		$(this).find('a').removeClass('Inactive').addClass('Active');	
		$(this).find('span').removeClass('Inactive').addClass('Active');	
		
	});
	
    $(document).on("scroll", onScroll);
    
    //smoothscroll
    $('a[href^="#"]').on('click', function (e) {
        e.preventDefault();
        $(document).off("scroll");
        
        $('a').each(function () {
            $(this).removeClass('Active');
				
        })
		  
        $(this).addClass('Active');
      
        var target = this.hash,
            menu = target;
        $target = $(target);
        $('html, body').stop().animate({
            'scrollTop': $target.offset().top+2
        }, 500, 'swing', function () {
            window.location.hash = target;
            $(document).on("scroll", onScroll);
        });
    });
});

function onScroll(event){
    var scrollPos = $(document).scrollTop();
    $('header ul li a').each(function () {
        var currLink = $(this);
        var refElement = $(currLink.attr("href"));
        
        if (refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
			  
            $('header ul li a').removeClass("Active");
				
            currLink.addClass("Active");
				
				$(this).parent('span').addClass('Active');
				
        }
        else{
            currLink.removeClass("Active");
				$(this).parent('span').removeClass('Active');
				
        }
    });
	 
}
