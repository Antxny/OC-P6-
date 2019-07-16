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
	var imagesPreview = function(input, placeToInsertImagePreview) {

        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }

    };

    $('.images-upload').on('change', function() {
        $('.gallery').empty();
        imagesPreview(this, 'div.gallery');
    });
});