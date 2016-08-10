var fileCount = 0;
var addedFiles = 0;
var fileLimit = 3;
console.log(projectBaseUrl);
$(document).ready(function($) {
    var uploader = $('#questions-0').fineUploader({
        request: {
        endpoint: projectBaseUrl + "uploads/ajax_photos"
    },
    validation: {
        allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
        sizeLimit: 10 * 1024 * 1024 
    },
    text: {
        uploadButton: 'Add Photos'
    }
    }).on('submit', function(event, id, fileName) {
        fileCount ++;
        if(fileCount > fileLimit) {
            $('#questions-0 .qq-upload-button').hide();
            $('#questions-0 .qq-upload-drop-area').hide();
            return false;
        }
    }).on('cancel', function(event, id, fileName) {
        fileCount --;
        if(fileCount <= fileLimit) {
            $('#questions-0 .qq-upload-button').show();
        }
    }).on('complete', function(event, id, fileName, responseJSON) {
        if (responseJSON.success) {
            addedFiles ++;
            if(addedFiles >= fileLimit) {
                $('#questions-0 .qq-upload-button').hide();
                $('#questions-0 .qq-upload-drop-area').hide();
            }
            // append filename to the textarea
            insertAtCaret('question_body', '<img class="img-responsive" src="'+ projectBaseUrl +'uploads/questions/'+responseJSON.filename+'">');
            $('#question_form_photos').append('<input type="hidden" name="data[photos][]" value="'+responseJSON.filename+'">');
        }
    });

    function insertAtCaret(areaId,text) {
        var txtarea = document.getElementById(areaId);
        var scrollPos = txtarea.scrollTop;
        var strPos = 0;
        var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 
            "ff" : (document.selection ? "ie" : false ) );
        if (br == "ie") { 
            txtarea.focus();
            var range = document.selection.createRange();
            range.moveStart ('character', -txtarea.value.length);
            strPos = range.text.length;
        }
        else if (br == "ff") strPos = txtarea.selectionStart;

        var front = (txtarea.value).substring(0,strPos);  
        var back = (txtarea.value).substring(strPos,txtarea.value.length); 
        txtarea.value=front+text+back;
        strPos = strPos + text.length;
        if (br == "ie") { 
            txtarea.focus();
            var range = document.selection.createRange();
            range.moveStart ('character', -txtarea.value.length);
            range.moveStart ('character', strPos);
            range.moveEnd ('character', 0);
            range.select();
        }
        else if (br == "ff") {
            txtarea.selectionStart = strPos;
            txtarea.selectionEnd = strPos;
            txtarea.focus();
        }
        txtarea.scrollTop = scrollPos;
    }
    

    // $('#questions-0').fineUploader({
    //     request: {
    //         endpoint: projectBaseUrl + "uploads/ajax_photos"
    //     },
    //     text: {
    //         uploadButton: 'Add Photos'
    //     },
    //     validation: {
    //         allowedExtensions: ['jpg', 'jpeg', 'gif', 'png'],
    //         sizeLimit: 10 * 1024 * 1024,
    //         itemLimit: 2
    //     },
    //     multiple: true,
    //     autoUpload: false
    // }).on('complete', function(event, id, fileName, response) {
    //     // $('#UserAvatar').val(response.filename);
    //     // $('#item-avatar').attr('src', response.avatar);
    // });	

	$(document).on('click', '#default_category, .category', function() {
		$("#left_category_list li a").removeClass('current_category');
		$("#left_category_list li").removeClass('current_li');
		if (!$(this).hasClass('current_category')) {
			$(this).addClass('current_category');
			$(this).parent().addClass('current_li');
		}
        var elem = $(this);
		$.post(projectBaseUrl + "questions/ajax_home", {cat_id: $(this).attr('cat-id')}, function(data, status){
			if(status) {
				$("#content_wrapper").html(data);
                if (projectBaseUrl != window.location) {
                  window.history.pushState({path:projectBaseUrl},'',projectBaseUrl);
                }
                if (elem.attr('cat-id') != 0) {
                    $("#poll_cat").val(elem.attr('cat-id'));
                    $("#poll_cat").html(elem.text()+' <i class="fa fa-caret-down"></i>');
                    $("#hidden_poll_category_id").val(elem.attr('cat-id'));
                    $("#ques_cat").val(elem.attr('cat-id'));
                    $("#ques_cat").html(elem.text()+' <i class="fa fa-caret-down"></i>');
                    $("#hidden_question_category_id").val(elem.attr('cat-id'));
                }
			}
	    });
	});

	// pagination function
	$(document).on('click', 'ul.custom_pagination li a', function(e){
		e.preventDefault();
		// alert('hi');
		// return;
		var cat_id = $("ul#middle_category_list li a.current_category").attr('cat-id');
		var page_no = parseInt($(this).text());
		$.post(projectBaseUrl + "questions/ajax_home", {cat_id: cat_id, page_no: page_no}, function(data, status){
			if(status) {
				$("#content_wrapper").html(data);
			}
	    });
	});

	// select category 
	$(document).on('click', 'form .select-category', function(e) {
		// var cat_id = $("ul#middle_category_list li a.current_category").attr('cat-id');
		// var page_no = parseInt($(this).text());
		e.preventDefault();
		$('form').find('.select-category').removeClass('category_append_to');
		$(this).addClass('category_append_to');
		$("#martexModal #martexModalLabel").html('Choose A Category');
		$("#martexModalBody").html('');
		//return;
		$.post(projectBaseUrl + "categories/ajax_categories", {}, function(data, status){
			if(status) {
				$("#martexModalBody").html(data);
				$("#martexModal").modal('show');
			}
	    });
	});

	// selected category
	$(document).on('click', '#accordion_category a', function(e) {
		$('#accordion_category').find('.panel-heading').removeClass('selected_category');
 		$(this).parent().parent().addClass('selected_category');
 	});

 	// category_choosed
 	$(document).on('click', '#category_choosed', function(e) {
 		e.preventDefault();
 		var element = $('#accordion_category').find('.selected_category').children().children();
 		var cat_id = $(element).attr('href');
 		var result = cat_id.split('_');
 		console.log(result);
 		var cat_name = $(element).text() + ' <i class="fa fa-caret-down"></i>';
 		$('form .category_append_to').html(cat_name);
 		$('form .category_append_to').val(result[1]);
 		$('form .category_append_to').next().val(result[1]);
 		$("#martexModal").modal('hide');
 	});


    // report abuse
    $(document).on('click', '.wrong_category', function(e) {
    	e.preventDefault();
    	$("#martexModalBody").html('');
    	var ques_id = $(this).attr('ques-id');
    	var html = '<input type="hidden" id="report_quest_id" value="'+ques_id+'">';
    	html += '<div class="form-group"><label for="comment">Comment:</label><textarea class="form-control" rows="5" id="comment"></textarea></div>';
    	html += '<div class="form-group" id="report_error"></div>';
    	html += '<div class="form-group"><input type="button" id="submit_report" value="Submit"></div>';
    	//var html = '<textarea id="report_text"></textarea><input type="hidden" id="report_quest_id" value="'+ques_id+'"><input type="button" id="submit_report" value="Submit">';
    	$("#martexModal #martexModalLabel").html('Are you sure to report wrong as category or abuse?');
		$("#martexModalBody").html(html);
		$("#martexModal").modal('show');
    });

    // report submit
    $(document).on('click', '#submit_report', function(e) {
    	e.preventDefault();
    	$('#report_error').hide();
    	var ques_id = $("#report_quest_id").val();
    	var comment = $("#comment").val();
    	if (comment == '') {
    		errorMsg('Write something to continue', 'report_error');
    		return false;
    	}
    	$.post(projectBaseUrl + "questions/ajax_abuse", {ques_id: ques_id, comment: comment}, function(data, status){
			if(status) {
				var json = $.parseJSON(data);
				if (json.result == 1) {
					window.location.reload();
				} else {
					window.location.href = projectBaseUrl + "member";
				}
			}
	    });
    });   

    // question create form submit
    $('#question_form').on('submit', function(e){
    	e.preventDefault();
    	$('#question_create_error').hide();
    	// validate value
    	if ($('#hidden_question_category_id').val() == '') {
    		errorMsg('Category is required', 'question_create_error');
    		return;
    	} 
    	if ($('#q_title').val() == '') {
    		errorMsg('Question title is required', 'question_create_error');
    		return;
    	} 

        var url = window.location.href;
        var elem = 'question_form';
    	console.log($(this).serialize());
    	$.post(projectBaseUrl + "questions/ajax_create", $(this).serialize(), function(data, status){
			if(status) {
				var json = $.parseJSON(data);
				if (json.result == 1) {
                    if ((url.indexOf('questions/view') > -1) || (url.indexOf('polls/view') > -1)) {
                        window.location.href = projectBaseUrl;
                    } else {
                        $('#q_title').val('');
                        $('#question_body').val('');
                        $('#question_form_photos').html('');
                        fileCount = 0;
                        addedFiles = 0;
                        $('.qq-upload-list').empty();
                        //$('.qq-upload-success').html('');
                        $('#questions-0 .qq-upload-button').show();
                        console.log(uploader);
                        getHtmlData(json.question_id);
                    }
					//window.location.reload();
                    //window.location.href = projectBaseUrl + "?cat_id=c81e728d9d4c2f636f067f89cc14862c";
				} else if (json.result == 3) {
                    var html = '<div class="modal-footer"><button type="button" class="btn btn-danger" id="yes_confirmed">Confirmed</button><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>';
                    html += '<input type="hidden" id="form_element" value="'+elem+'"></div>';
                    $("#martexModal #martexModalLabel").html('Your question contain censor words. Question will not be published. Do you want to still continue?');
                    $("#martexModalBody").html(html);
                    $("#martexModal").modal('show');
                } else {
					window.location.href = projectBaseUrl + "member";
				}
			}
	    });
    });

    // confirm form submission with censor word
    $(document).on('click', '#yes_confirmed', function(e) {
        $("#martexModal").modal('hide');
        var elem = $('#form_element').val();
        $("#" + elem).append('<input type="hidden" name="data[yes_confirmed]" value="1">');
        $("#" + elem).submit();
    });

    // poll create form submit
    $('#poll_form').on('submit', function(e){
    	e.preventDefault();
    	$('#poll_create_error').hide();
    	// validate value
    	if ($('#hidden_poll_category_id').val() == '') {
    		errorMsg('Category is required', 'poll_create_error');
    		return;
    	} 
    	if ($('#p_title').val() == '') {
    		errorMsg('Question title is required', 'poll_create_error');
    		return;
    	} 

    	var validate_error = false;
    	$(".poll-option").each(function(index, value){
    		if (index >= 2) {
    			return;
    		}
    		if ($(this).val() == '') {
    			errorMsg('At least 2 options are required', 'poll_create_error');
    			validate_error = true;
    			return;
    		}
		});

		if (validate_error) return;

        var url = window.location.href; 

        var elem = 'poll_form';
    	console.log($(this).serialize());
    	$.post(projectBaseUrl + "questions/ajax_create", $(this).serialize(), function(data, status){
			if(status) {
				var json = $.parseJSON(data);
				if (json.result == 1) {
                    if ((url.indexOf('questions/view') > -1) || (url.indexOf('polls/view') > -1)) {
                        window.location.href = projectBaseUrl;
                    } else {
                        $('#p_title').val('');
                        $(".poll-option").each(function(index, value){
                            if (index < 2) {
                                $(this).val('');
                            } else {
                                $(this).closest('.form-group').remove();
                            }
                        });
                        getHtmlData(json.question_id);
                    }
				} else if (json.result == 3) {
                    var html = '<div class="modal-footer"><button type="button" class="btn btn-danger" id="yes_confirmed">Confirmed</button><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>';
                    html += '<input type="hidden" id="form_element" value="'+elem+'"></div>';
                    $("#martexModal #martexModalLabel").html('Your poll contain censor words. Poll will not be published. Do you want to still continue?');
                    $("#martexModalBody").html(html);
                    $("#martexModal").modal('show');
                } else {
					window.location.href = projectBaseUrl + "member";
				}
			}
	    });
    });

    // add_more_option poll
    $(document).on('click', '#add_more_option', function(e) {
    	var option_count = $('.poll-option').length;
    	if (option_count >= 6) {
    		errorMsg('Sorry, you can add maximum 6 options', 'poll_create_error');
    		return;
    	}
    	var html = '<div class="form-group"><label>Option '+(option_count+1)+'</label><input type="text" name="data[QuestionOption]['+option_count+'][text]" class="form-control poll-option"></div>';
    	$('#poll_options').append(html);
    });

    // cast vote
    //$('.vote_form').on('submit', function(e){
    $(document).on('click', '.cast-vote', function(e) {
    	e.preventDefault();
    	console.log($(this).closest('form').serialize());
    	var vote = $('input:radio:checked', $(this).closest('form')).val();
 		if(typeof vote === 'undefined'){
		    return;
		}
		var result_element = $(this).closest('.poll-section');
		$.post(projectBaseUrl + "questions/ajax_vote_cast", $(this).closest('form').serialize(), function(data, status){
			if(status) {
				$(result_element).html('');
				$(result_element).html(data);
			}
	    });
    });

    // view result of vote view_result
    $(document).on('click', '.view_result', function(e) {
        $(this).closest('form').next().toggle();
    });
    // after vote
    $(document).on('click', '.after_view_result', function(e) {
        $(this).next().toggle();
    });

    // search form submit
    $(document).on('submit', '#search_form', function(e) {
        e.preventDefault();
        // validate value
        if ($('#search_text').val() == '') {
            // do nothing
            return;
        } 
        $.post(projectBaseUrl + "questions/ajax_search", $(this).serialize(), function(data, status){
            if(status) {
                if (projectBaseUrl != window.location) {
                  window.history.pushState({path:projectBaseUrl},'',projectBaseUrl);
                }
                $("#content_wrapper").html(data);
            }
        });
    });

    // search pagination function
    $(document).on('click', 'ul.search_pagination li a', function(e){
        e.preventDefault();
        // alert('hi');
        // return;
        var page_no = parseInt($(this).text());
        $.post(projectBaseUrl + "questions/ajax_search", {page_no: page_no}, function(data, status){
            if(status) {
                $("#content_wrapper").html(data);
            }
        });
    });


    //// Delete question
    // $(document).on('click', '.delete-question', function(e) {
    //     e.preventDefault();
    //     $("#martexModalBody").html('');
    //     var ques_id = $(this).attr('ques-id');
    //     var html = '<input type="hidden" id="delete_quest_id" value="'+ques_id+'">';
    //     html += '<input type="button" id="confirm-delete-question" value="Yes">';
    //     $("#martexModal #martexModalLabel").html('Are you sure to report wrong as category or abuse?');
    //     $("#martexModalBody").html(html);
    //     $("#martexModal").modal('show');
    // });

    $(document).on('click', '.delete-question', function(e) {
        e.preventDefault();
        var ques_id = $(this).attr('ques-id');
        var element = $(this);
        $.post(projectBaseUrl + "questions/ajax_question_delete", {ques_id: ques_id}, function(data, status){
            if(status) {
                var json = $.parseJSON(data);
                if (json.result == 1) {
                    element.closest('li').remove();
                } else if(json.result == 0) {
                    alert(json.message);
                } else {
                    window.location.href = projectBaseUrl + "member";
                }
            }
        });
    });

    // delete own answer
    $(document).on('click', '.delete-answer', function(e) {
        e.preventDefault();
        var answer_id = $(this).attr('answer-id');
        var element = $(this);
        $.post(projectBaseUrl + "questions/ajax_answer_delete", {answer_id: answer_id}, function(data, status){
            if(status) {
                var json = $.parseJSON(data);
                if (json.result == 1) {
                    element.closest('li').remove();
                } else if(json.result == 0) {
                    alert(json.message);
                } else {
                    window.location.href = projectBaseUrl + "member";
                }
            }
        });
    });

});

function getHtmlData(question_id) {
    $.post(projectBaseUrl + "questions/ajax_single_question", {question_id : question_id}, function(data, status){
        if(status) {
            $("#middle_content_list").prepend(data);
        }
    });
}