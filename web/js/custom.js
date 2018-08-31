$(document).ready(function() {

	// Show load on blur of textbox
	$('input').blur(function() {
		var id=$(this).attr('id');
		$('.form-horizontal').on('ajaxBeforeSend', function (event, jqXHR, settings) {
			$('#'+id).addClass('spinner');
			// Activate waiting label
			}).on('ajaxComplete', function (event, jqXHR, textStatus) {
			// Deactivate waiting label
			$('#'+id).removeClass('spinner');
		});
	});

	// Show load on button click
	$('button').click(function() {
		$('.form-horizontal').on('ajaxBeforeSend', function (event, jqXHR, settings) {
			$('.ajax').addClass('spinner');
		// Activate waiting label
		}).on('ajaxComplete', function (event, jqXHR, textStatus) {
		// Deactivate waiting label
			$('.ajax').removeClass('spinner');
		});
	});

	// Set Id of current record that needs to be deleted
	/*$('.confirm-delete').on('click', function(e) {alert('****');
		$('#id').val($(this).attr("data-id"));
		//$('.model-delete').modal('toggle');
 	});*/


});
// need to reinitialize modal popup trigger action due to pjax
$(document).on('ready pjax:complete', function() {
    // Set Id of current record that needs to be deleted
	$('.confirm-delete').on('click', function(e) {
		$('#id').val($(this).attr("data-id"));
		if ($(this).attr("data-url")) {
			$("#header-search-forms").attr("action", $(this).attr("data-url"));
		}
		$('.model-delete').modal('show');
 	});

	$('[data-toggle=tooltip]').tooltip({
            animation: 'fade'
     });
	 $('[data-toggle=popover]').popover({
            container: 'body',
			html : true,
     });

	// Send Email Notification
	$('.send-mail').on('click', function(e) {
		e.preventDefault();
		var contributor_id =$(this).attr("data-contributor");
		var survey_id =$('#survey_id').val();
		var quarter_id =$('#quarter_id').val();
		$('#showLoader').show();
		$('#showLoader').removeClass('hideOption');
		$.ajax({
			url : 'sendnotificationmail',
			data : {contributor_id : contributor_id, survey_id : survey_id , quarter_id : quarter_id},
			type:'POST',
			dataType : 'json',
			success: function(res) {
				if(res.status == 'success') {
					$('#success').html(res.message);
					$('.success').removeClass('hideOption');
					$('.success').fadeIn();
					$('.success').fadeOut(3000);

				} else {
					$('.error').fadeIn();
					$('#error').html(res.message);
					$('.error').fadeOut(3000);
				}
				$.pjax.reload({container:'#contributor-grid', timeout: 2000, scrollTo:0});
				//$("tbody").sortable();
				$('#showLoader').hide();
			}

		});
	});

	// Enable /disable button as per checkbox is checked/unchecked
	$('.email-notify').on('click', function(e) {
		var selectedRows = $('#w0').yiiGridView('getSelectedRows');
		if(selectedRows == "") {
			$('.email-notify').prop('disabled', true);
			return false;
		} else {
			$('.email-notify').prop('disabled', false);
			return true;
		}

	});

	// Enable /disable delete button as per checkbox is checked/unchecked
	//also show alertbox
	$('.del-btn').on('click', function(e) {
		var selectedRows = $('#w0').yiiGridView('getSelectedRows');
		if(selectedRows == "") {
			$('.del-btn').prop('disabled', true);
			return false;
		} else {
			$('.del-btn').prop('disabled', false);
			$('#modaldeleteall').modal('show');
			return false;
		}

	});

	//Submit form if user select yes to delete records
	$('.del-yes').on('click', function(e) {
		$("#delete-all-form").submit();
		return true;
	});


	//Enable/disable button for email notification & delete all
	$('.chkcontributor, .select-on-check-all').on('change', function(e) {
	var selectedRows = $('#w0').yiiGridView('getSelectedRows');//alert(selectedRows);
		if(selectedRows == "") {
			$('.email-notify').prop('disabled', true);
			$('.del-btn').prop('disabled', true);
		} else {
			$('.email-notify').prop('disabled', false);
			$('.del-btn').prop('disabled', false);

		}
	});

        //check/uncheck all for distributable
        $('.select-on-check-all-box').click(function() {
            var selector = $(this).is(':checked') ? ':not(:checked)' : ':checked';
            $('#w1 input[type="checkbox"]' + selector).each(function() {
                $(this).trigger('click');
            });
             if ($('.chkdistribute:checked').length != 0) {
                if(selector == ':not(:checked)') {
                    $('.distribute-btn').prop('disabled', false);
                } else {
                    $('.distribute-btn').prop('disabled', true);
                }
            }

        });

        $('.chkdistribute').on('click', function() {
             if ($('.chkdistribute:checked').length != 0) {
                  $('.distribute-btn').prop('disabled', false);
             } else {
                $('.distribute-btn').prop('disabled', true);
            }
        });

	// Button to open popup to set deadline
	$('.set-dealine').on('click', function(e) {
		var quarterId = $('#quarter_id').val();
		$('#quarterId').val(quarterId);
		$("#err-deadline").hide();
		$('.model-deadline').modal('show');
	});

	// save deadline
	$('#save-deadline').on('click', function(e) {
		if($("#deadline").val() == "") {
			$('#err-deadline').removeClass('hideOption');
			$("#err-deadline").fadeIn('slow');
			$("#err-deadline").html("Deadline field is required.");
			return false;
		} else
		{
			$("#err-deadline").fadeOut(3000);
			return true;
		}
	});

	// Open a popup to close the survey
	$(".close-survey").on('click', function(e) {
		var quarterId = $('#quarter_id').val();
		$('#quartId').val(quarterId);
		$('.model-close-survey').modal('show');
	});

	// Open a popup to close the survey
	$(".exclude-answer").on('click', function(e) {
		var data_field_id = $(this).attr('data-id');
		var included = $(this).attr('data-included');
		$('#data_field_id').val(data_field_id);
		$('#included').val(included);
		if(included == 0) {
			$("#msg").html("Are you sure to include this answer?");
			$("#include_confirm").html("Include Confirm");
			$("#exclude_reason").addClass('hideOption');
		}
		$('#modalexclude').modal('show');
	});

	// validations to exluce answer
	$('.include_answer').on('click', function(e) {
	if($("#included").val() == 1) {
		if($("#exclude_reason").val().trim() == "") {
			$('#err-reason').removeClass('hideOption');
			$("#err-reason").fadeIn('slow');
			$("#err-reason").html("Exclude reason field is required.");
			return false;
		} else
		{
			$("#err-reason").fadeOut(3000);
			return true;
		}
	} else {
		return true;
	}
	});

	// Show survey changes on popup
	$(".view-changes").on('click', function(e) {
		$('#showLoader').removeClass('hideOption');
		var elementId = $(this).closest('tr').data('key');
		$.ajax({
			url : 'viewchanges',
			data : {audit_id : elementId},
			type:'POST',
			dataType : 'json',
			success: function(res) {
				$('#mod_date').html(res.modified_on);
				$('#tbody').html(res.changes);
				$('.model-audit').modal('show');

				//$("tbody").sortable();
				$('#showLoader').hide();
			}

		});
	});



	// data column field type change
	$("#datafieldtemplates-field_type").on("change", function() {
		var currVal = $(this).val();
		if(currVal == 'selection') {
			$(".option-class").show();
		} else {
			$(".option-class").hide();
		}
	});

	// Edit data column popup
	$(document).on('click', '.showModalButton', function(){
		$('#modal').addClass('modal-success');
		 $('#modal').modal('show')
                    .find('#modalContent')
                    .load($(this).attr('value'));
		document.getElementById('modalHeader').innerHTML = '<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h4 class="modal-title">' + $(this).attr('title') + '</h4>';
	});



	//Change order of column in asc
	$(".moveuplink").click(function() {
		if( $(this).data('order') >= $(this).data('total'))  {
		var td1 = $("#dataColumns").find('td:eq(2)'); // indices are zero-based here
    	var td2 = $("#dataColumns").find('td:last');
		td2.detach().insertBefore(td1);
		td1.detach().insertAfter($("#dataColumns").find('td:last'));
		//$(this).parents(".sectionsid").insertAfter($("#dataColumns").find('td:eq(2)'));
		} else {
			$(this).parents(".sectionsid").insertAfter($(this).parents(".sectionsid").next());
		}
		sendOrderToServer();


    });

   //Change order of column in desc
    $(".movedownlink").click(function() {
		//$(this).parents(".sectionsid").insertAfter($("#dataColumns").find('td:eq(4)'));
		if($(this).data('order') == 1) {
		var td1 = $("#dataColumns").find('td:eq(2)'); // indices are zero-based here
    	var td2 = $("#dataColumns").find('td:last');
		td1.detach().insertAfter(td2);
		td2.detach().insertBefore($("#dataColumns").find('td:eq(2)'));
		} else {
        $(this).parents(".sectionsid").insertBefore($(this).parents(".sectionsid").prev());
		}
        sendOrderToServer();
    });

	function sendOrderToServer()
	{
		var order = $("#dataColumns").sortable("serialize");
		$.ajax({
			url : 'updateorder',
			data : order,
			dataType : 'json',
			type:'POST',
			success: function(res) {
				if(res.status == 'success') {
					$('#success').html(res.message);
					$('.success').removeClass('hideOption');
					$('.success').fadeIn();
					$('.success').fadeOut(3000);

				} else {
					$('.error').fadeIn();
					$('#error').html(res.message);
					$('.error').fadeOut(3000);
				}
				$.pjax.reload({container:'#output-table', timeout: 2000, scrollTo: $('#output-table').offset().top});
				//$("tbody").sortable();
				$('#showLoader').hide();
			}

		});
	}

	// enable /disable nodes for output table
	$('.enabledisable').click(function() {
		$('#showLoader').show();
		var column = $(this).data("column");
		var node = $(this).data("node");
		var excluded = $(this).data("excluded");
		$.ajax({
			url : 'checkexclusion',
			data : {column: column , node :node, excluded : excluded},
			dataType : 'json',
			type:'POST',
			success: function(res) {
				if(res.result == 'success') {
					$('#success').html(res.message);
					$('.success').removeClass('hideOption');
					$('.success').fadeIn();
					$('.success').fadeOut(3000);

				} else {
					$('.error').fadeIn();
					$('#error').html(res.message);
					$('.error').fadeOut(3000);
				}
				$.pjax.reload({container:'#output-table', timeout: 2000, scrollTo: $('#output-table').offset().top});
				$('#showLoader').hide();
			}

		});

	});

	// Show /hide return column field if its GIY
	$('._filters').on('change',function() {
		var col_id = $(this).attr("data-retColumn");
		if($(this).val() == 'Gross Income Yields') {
			$('#return_column'+col_id).removeClass('hideOption');
		} else {
			$('#return_column'+col_id).addClass('hideOption');
		}
	});

	//Show/hide column based on additional column value
	$('#outputtables-output_column').on('change', function() {
		if($(this).val() == 'n') {
			$('#column-difference').addClass('hideOption');
			$('#column-n').removeClass('hideOption');
		} else if($(this).val() == 'Difference'){
			$('#column-n').addClass('hideOption');
			$('#column-difference').removeClass('hideOption');
		} else {
			$('#column-n').addClass('hideOption');
			$('#column-difference').addClass('hideOption');
		}
	});

	//show confirmation popup for survey completion
	$('.confirm-completion').on('click', function(e) {
		$('#surveyId').html($(this).attr("data-id"));
		$('#contributorId').html($(this).attr("data-cont-id"));
		$('#modalcompletesurvey').modal('show');
    });

	$(".complete-yes").on('click', function(e) {
		 e.preventDefault();
		 $('#showLoader').show();
		var surveyId = $('#surveyId').html();
		var contId = $('#contributorId').html();
		$('#modalcompletesurvey').modal('hide');
		$.ajax({
			url : $(this).attr('href'),
			data : {surveyId: surveyId},
			dataType : 'json',
			type:'POST',
			success: function(res) {
				if(res.result == 'success') {
					$('#contributor_id').val(contId);
					$('#survey_id').val(surveyId);
					$('#modalcomment').modal('show');

				} else {
					$('.error').fadeIn();
					$('#error').html(res.message);
					$('.error').fadeOut(3000);
				}
				$('#showLoader').hide();
			}

		});
	});

});
$(document).on({
	ajaxError: function(jqXHR, ajaxSettings, thrownError) {
		$('#showLoader').hide();
		$('.error').fadeIn();
		$('#error').html('Something went wrong while requesting the data! Please try again');
		$('.error').fadeOut(3000);
	}
});

//
function change_capture(capture_level, id)
{
	$.ajax({
		url : 'changecapturelevel',
		data : {capture_level : capture_level, id : id},
		type:'POST',
		dataType : 'json',
		success: function(res) {
			if(res.result == 'success') {
					$('#success').html(res.message);
					$('.success').removeClass('hideOption');
					$('.success').fadeIn();
					$('.success').fadeOut(3000);

			} else {
					$('.error').fadeIn();
					$('#error').html(res.message);
					$('.error').fadeOut(3000);
			}
			$.pjax.reload({container:"#output-table" , timeout: 2000, scrollTo: $('#output-table').offset().top});
		}
	});
}

function change_include_exclude(is_included, id)
{
	$.ajax({
		url : 'changeincluded',
		data : {is_included : is_included, id : id},
		type:'POST',
		dataType : 'json',
		success: function(res) {
			if(res.result == 'success') {
					$('#success').html(res.message);
					$('.success').removeClass('hideOption');
					$('.success').fadeIn();
					$('.success').fadeOut(3000);

			} else {
					$('.error').fadeIn();
					$('#error').html(res.message);
					$('.error').fadeOut(3000);
			}
			$.pjax.reload({container:"#output-table" , timeout: 2000, scrollTo: $('#output-table').offset().top});
		}
	});
}


function remove_property(property_id, contributor_id, elem)
{

	$.ajax({
		url : '../removenode/'+property_id,
		data: {contributor_id: contributor_id},
		type:'GET',
		dataType : 'json',
		success: function(res) {
			if(res.result == 'success') {
					$(elem).parent().parent().parent().parent().remove()
					$('#success').html(res.message);
					$('.success').removeClass('hideOption');
					$('.success').fadeIn();
					$('.success').fadeOut(3000);

			} else {
					$('.error').fadeIn();
					$('#error').html(res.message);
					$('.error').fadeOut(3000);
			}
			//$.pjax.reload({container:"#output-table" , timeout: 2000, scrollTo: $('#output-table').offset().top});
		}
	});
}
