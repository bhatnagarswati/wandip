$(document).ready(function () {
	$('#is_free').on('change', function () {
		console.log($(this).val());
		if ($(this).val() == 0) {
			$('#delivery_cost').fadeIn();
		} else {
			$('#delivery_cost').fadeOut();
		}
	});
	$('.select2').select2({
		placeholder: 'Select'
	});
	$('.table').DataTable({
		'info': false,
		'paging': false,
		'searching': true,
		'columnDefs': [
			{
				'orderable': false, 'targets': -1
			}
		],
		'sorting': []
	});

	// Function to show selected image by user to upload.
	$(".showPic").change(function (e) {

		var nextimg = $(this).parent('label').next('img');
		for (var i = 0; i < e.originalEvent.srcElement.files.length; i++) {
			var file = e.originalEvent.srcElement.files[i];
			var img = document.createElement("img");
			var reader = new FileReader();
			reader.onloadend = function () {
				img.src = reader.result;
				$(nextimg).attr('src', img.src);
			}
			reader.readAsDataURL(file);

		}
	});


	/***** Add more locations for routes ***/

	var max_fields = 10; //maximum input boxes allowed
	var wrapper = $(".input_fields_wrap"); //Fields wrapper
	var add_button = $(".add_field_button"); //Add button ID

	var x = 0;  //initlal text box count
	$(add_button).click(function (e) { //on add input button click
		e.preventDefault();
		if (x < max_fields) { //max input box allowed
			x++;  //text box increment
			var addnum = 1;
			var keyIncrement = $('#keyIncrement').val();
			keyIncrement = parseInt(keyIncrement) + parseInt(addnum);
			$(wrapper).append('<div><input type="text" value="" class="form-control autocomplete required" id="locations" data_val="' + keyIncrement + '" name="locations[' + keyIncrement + '][location]"><input type="hidden" value=""  name="locations[' + keyIncrement + '][city]" id="locations_' + keyIncrement + '_city"><input type="hidden" value="" id="locations_' + keyIncrement + '_locationLat"  name="locations[' + keyIncrement + '][locationLat]"><input type="hidden" value="" id="locations_' + keyIncrement + '_locationLong"  name="locations[' + keyIncrement + '][locationLong]"><a href="#" class="remove_field">Remove</a></div>'); //add input box
			$('#keyIncrement').val(keyIncrement);

			initialize();
		}
	});

	$(wrapper).on("click", ".remove_field", function (e) { //user click on remove text
		e.preventDefault(); $(this).parent('div').remove(); x--;
	});
 
	$('.datepicker').datepicker({
		autoclose: true,
		dateFormat: 'yy-mm-dd',
		todayHighlight: true
	});

	$('.timepicker').timepicker({

		timeFormat: 'h:mm p',
		interval: 60,
	    /*minTime: '10',
	    maxTime: '6:00pm',
	    defaultTime: '11',
	    startTime: '10:00',*/
		dynamic: false,
		dropdown: true,
		scrollbar: true
	});

		// Ck editor enable for pages
		if($("#fullDescription"). length){
			CKEDITOR.replace('fullDescription',
			{
				height: '400px',
			});
		}

		if($("#shortDescription"). length){
		
			CKEDITOR.replace('shortDescription',
			{
				height: '200px',
			});	
		}

		$('form').on('keyup keypress', function(e) {
		var keyCode = e.keyCode || e.which;
		if (keyCode === 13) { 
			e.preventDefault();
			return false;
		}
		});

 
});
