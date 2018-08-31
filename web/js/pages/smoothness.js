$(document).on('ready pjax:success', function(){
	$("tbody").sortable({
        items: "tr",
        cursor: 'move',
        opacity: 0.6,
        update: function() {
            sendOrderToUpdate();
        }
    });
	
	function sendOrderToUpdate()
	{
		var order = $("tbody").sortable("serialize");
		var currPage = $("#currPage").val();
		var per_page = $("#per_page").val();
		$('#showLoader').show();
		$.ajax({
			url : 'updateposition',
			data : order+"&page="+currPage+"&per_page="+per_page,
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
				$.pjax.reload({container:'#nodes-grid', timeout: 2000, scrollTo:0});
				//$("tbody").sortable();
				$('#showLoader').hide();
			}
			
		});
	}
});


	