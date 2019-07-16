$(document).ready(function() {
	$(".scrolled").hide();
	setInterval(function(){
		$(".arrow").effect("bounce", { direction:'down', times:2 }, 1000);
	}, 4000);
	$(".arrow").click(function() {
		$(".scrolled").fadeIn(500);
	    $([document.documentElement, document.body]).animate({
	        scrollTop: $(".tricks").offset().top - 66
	    }, 1000);
	});
});