import '../css/cover.css';
import '../css/resume.css';
import '../css/contact.css';



$(document).ready(function(){

    $(window).scroll(function() {
        if ($(this).scrollTop() > 1) {
            $(".header").css({"background-color": "rgb(0, 0, 0, .45)", "backdrop-filter": "saturate(125%) blur(10px)"});
            $(".logo").css({"width": "200"});
        }
        else {
            $(".header").css({"background-color": "", "backdrop-filter": ""});
            $(".logo").css({"width": "250"});
        }
        $("#cover-background").css({"background-position":"left " +($(window).scrollTop()*.07) + "px"})
    });


    $('#contact-form').on('submit', function(e) {
        e.preventDefault();

        let $button = $(this).find("button[type='submit']");
        $button.prop("disabled",true).append("<i class='ml-1 fa fa-spinner fa-spin'></i>");

        $.ajax({
            type: $(this).attr('method'),
            url : $(this).attr('action'),
            data: $(this).serialize()
        }).done(function( status ) {
            if (status > 0){
                $("#contact-form").append( '<div class="form-text text-success">Message delivered</div>');
            } else {
                $("#contact-form").append( '<div class="form-text text-danger">An error occured. Sorry for the incovenience.</div>' );
            }
            $button.prop("disabled",false)
            $(".fa-spinner").remove();
        });
    });

});


