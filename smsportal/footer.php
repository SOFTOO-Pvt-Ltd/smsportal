

</body>
<script src="assets/js/jquery-3.1.1.min.js"></script>
<script src="assets/js/moment.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<script src="assets/js/main.js"></script>
<script src="assets/js/jquery.maskedinput.js"></script>

<script src="assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="assets/js/bootstrap-select.js"></script>
<script src="assets/js/bootstrap-multi-select.js"></script>
<script src="assets/js/my.js"></script>
<script type="text/javascript">
$(function() {
    $('.multiselect-ui').multiselect({
        includeSelectAllOption: true,
        onChange: function() {
        	var total_memebers = 0;
	        $.each($(".multiselect-container input[type='checkbox']:checked"), function(){            
	            var temp = $('#groupd option[value="'+$(this).val()+'"]').attr('data-memcount');
	            if (!isNaN(temp)) {
	            	total_memebers = parseInt(total_memebers) + parseInt(temp);
	            }
	        });
	        var text_phr = '0 member';
	        if (total_memebers == 1) {
	        	var text_phr = '1 member';
	        } else if (total_memebers > 1) {
	        	text_phr = total_memebers + ' memebrs';
	        }
	        $('#total_recep').text(text_phr);
	    }
    });
    $('#datetimepicker1').datetimepicker();
});
</script>
</html>