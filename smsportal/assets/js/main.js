jQuery(function($) {
    "use strict";

    $("body").on("click", ".btn-broadcast", function(e) {
        $(".broadcast-modal").addClass("active");
    });
	
	
	$("body").on("click", ".checkagent", function(e) {
        $(".checkstatus-modal").addClass("active");
    });

    $("body").on("click", ".groupModalTrigger", function(e) {
        $(".group-modal").addClass("active");
    });

    $("body").on("click", ".close-modal", function(e) {
		
	
        $(".modal").removeClass("active");
		closebr();
    });
	
	
	$("body").on("click", ".creatagent", function(e) {
        $(".creatagent-modal").addClass("active");
    });
	
	
	$("body").on("click", ".setreplay", function(e) {
        $(".setreplay-modal").addClass("active");
    });

    $('body').on("click", function (e) {
	
        if ($(e.target).closest('.modal-content, .btn-broadcast, .groupModalTrigger , .creatagent , .setreplay, .checkagent').length === 0) {
            $(".modal").removeClass("active");
			closebr();
        }
    });
	
	$('#add_num_manu').on('click', function () {
		$('.man_wrapper').show();
	});
	
	$('#add_more').on('click', function () {
		$('.name_wrapper').append('<div class="form-group"><input class="form-control" type="text" name="pname[]" placeholder="Name"><span class="alertMsg pname" style="display: none;">Name is required</span></div>');
		$('.phone_wrapper').append('<div class="form-group"><input value="03" class="form-control phone" type="text" name="pphone[]" placeholder="Phone"><span class="alertMsg pphone" style="display: none;">Phone is required</span></div>');
		$('.phone').mask('03999999999', {placeholder:''});
	});

});




function closebr()
{
	
	  $("#forid002").prop("checked", true).trigger("click");
	  $('.selectWrapper').hide();
	  $('.addNumbers').show();
	 // $( ".btn-lightgreen" ).removeClass( "loading" );
	 // $('.bclooo').trigger('click');
	  $("#groupd").val('');
	  $("#commasepratedn").val('');
	  $("#tex").val("");
	  $('.texscc').hide();
	  $('.texsp').hide();
}