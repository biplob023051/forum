$(document).ready(function($) {	
    // question create form submit
    $('#answer_form').on('submit', function(e){
    	e.preventDefault();
    	$('#answer_create_error').hide();
    	// validate value
    	if ($('#answer_question_id').val() == '') {
    		errorMsg('Something went wrong, please reload the page', 'answer_create_error');
    		return;
    	} 

    	if ($('#answer_body').val() == '') {
    		errorMsg('Answer body is required', 'answer_create_error');
    		return;
    	} 
    	console.log($(this).serialize());
    	$.post(projectBaseUrl + "questions/ajax_answer", $(this).serialize(), function(data, status){
			if(status) {
                $('#answer_body').val('');
				$("#all_answers").append(data);
			}
	    });
    });

    $(document).on('click', '.like', function(e) {
        e.preventDefault();
        var type = $(this).attr('type');
        var answer_id = $(this).attr('ans-id');
        var current_count = parseInt($(this).find('.related-count').text());
        
        if(typeof type === 'undefined'){
            alert('Something went wrong, please reload the page to continue');
            return;
        }
        if(typeof answer_id === 'undefined'){
            alert('Something went wrong, please reload the page to continue');
            return;
        }
        var elem = $(this);
        $.post(projectBaseUrl + "questions/ajax_like", {type: type, answer_id: answer_id}, function(data, status){
            if(status) {
                var json = $.parseJSON(data);
                if (json.result == 1) {
                    if (json.added == 1) {
                        $(elem).find('.related-count').html(current_count+1);
                    } else {
                        $(elem).find('.related-count').html(current_count-1);
                    }
                } else if (json.result == 2) {
                    alert(json.message);
                } else {
                    window.location = projectBaseUrl + "member";
                }
            }
        });
        //$(this).closest('.poll-section').children(":first").toggle();
    });

    $(document).on('click', '.interest', function(e) {
        e.preventDefault();
        var question_id = $(this).attr('ques-id');
        
        if(question_id == ''){
            alert('Something went wrong, please reload the page to continue');
            return;
        }
        var elem = $(this);
        var current_count = parseInt($(this).find('.interest-count').text());
        $.post(projectBaseUrl + "questions/ajax_interest", {question_id: question_id}, function(data, status){
            if(status) {
                var json = $.parseJSON(data);
                if (json.result == 1) {
                    if (json.added == 1) {
                        $(elem).find('.interest-count').html(current_count+1);
                    } else {
                        $(elem).find('.interest-count').html(current_count-1);
                    }
                } else if (json.result == 2) {
                    alert(json.message);
                } else {
                    window.location = projectBaseUrl + "member";
                }
            }
        });
        //$(this).closest('.poll-section').children(":first").toggle();
    });

    $(document).on('click', '.bookmark', function(e) {
        e.preventDefault();
        var question_id = $(this).attr('ques-id');
        
        if(question_id == ''){
            alert('Something went wrong, please reload the page to continue');
            return;
        }
        var elem = $(this);
        $.post(projectBaseUrl + "questions/ajax_bookmark", {question_id: question_id}, function(data, status){
            if(status) {
                var json = $.parseJSON(data);
                if (json.result == 1) {
                    $(elem).removeClass('bookmark');
                    $(elem).attr('disabled', true);
                    $(elem).attr('ques-id', true);
                } else if (json.result == 2) {
                    alert(json.message);
                } else {
                    window.location = projectBaseUrl + "member";
                }
            }
        });
        //$(this).closest('.poll-section').children(":first").toggle();
    });

    // pagination function
    $(document).on('click', 'ul.answer_pagination li a', function(e){
        e.preventDefault();
        // alert('hi');
        // return;
        var question_id = $('#answer_question_id').val();
        var page_no = parseInt($(this).text());
        $.post(projectBaseUrl + "questions/ajax_answer_view", {question_id: question_id, page_no: page_no}, function(data, status){
            if(status) {
                $("#all_answers").html(data);
            }
        });
    });

    $(document).on('click', '.admin-reply-form', function(e) {
        e.preventDefault();
        $(this).parent().next().toggle();
    });

    $(document).on('click', '.admin-reply-submit', function(e) {
        e.preventDefault();
        var elem = $(this);
        var reply_body = elem.prev().val();
        var answer_id = elem.prev().prev().val();
        if (answer_id == '') {
            alert('Something went wrong, please reload the page');
            return;
        }
        if (reply_body == '') {
            errorMsg('Reply body is required', 'reply_create_error_' + answer_id);
            return;
        } 
        $.post(projectBaseUrl + "questions/ajax_answer_reply", {answer_id: answer_id, reply_body: reply_body}, function(data, status){
            if(status) {
                elem.prev().val('');
                elem.parent().next().append(data);
            }
        });
    });

});
