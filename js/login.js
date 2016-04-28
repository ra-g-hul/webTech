$(function() {
    $('#login-form-link').click(function(e) {
		$("#login").delay(100).fadeIn(100);
 		$("#signup").fadeOut(100);
		$('#register-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});
	$('#register-form-link').click(function(e) {
		$("#signup").delay(100).fadeIn(100);
 		$("#login").fadeOut(100);
		$('#login-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});

});

    