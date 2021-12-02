(function($){
  $(function(){

    $('.button-collapse').sideNav();
    
    Materialize.showStaggeredList('#nav-mobile');

    $(".side-link a").click(function(e) {
        e.preventDefault();
        $(".side-link a").removeClass('active-side-link');
        $(this).addClass('active-side-link');

        var elemToScroll = $(e.target).attr('link');
        $('html, body').animate({
            scrollTop: $(elemToScroll).offset().top
        }, 500);
    });

    $("#contact_form_submit").click(function(e){
        e.preventDefault();
        var name = $("#contact_name").val();
        var email = $("#contact_email").val();
        var msg = $("#contact_message").val();
		var recaptcha_response = $("#recaptcha_response").val();

        $.ajax({
          type: "POST",
          url: "contact.php",
          data: {
            'name':name,
            'email':email,
            'message':msg,
			'recaptcha_response': recaptcha_response,
          },
          success: function(data){
            if((JSON.parse(data)).status == "failed"){
	      $("#contact-failed").openModal();
            } else {
              $("#contact_name").val("");
              $("#contact_email").val("");
              $("#contact_message").val("");
              $("#contact-success").openModal();
            }
   	  },
        });
    });

    var copyrightText = "© " + (new Date).getFullYear() + " Niels van der Schaaf";
    $("#copyright-txt").html(copyrightText);

  }); // end of document ready
})(jQuery); // end of jQuery name space
