/***Validate */
window.validation = function(e, id) {
        // function validation(e, id) {
        e.preventDefault();
        var valfield = id + " input," + id + " select," + id + " textarea";
        $(".error,.raderror,.chkerror").remove();
        var valid = true;
        var radname = "";
        var checkname = "";
        $(valfield).css("border-color", "#ccc");
        $(valfield).not(':disabled').each(function(index) {
            if ($(this).closest('.novalidation').length) {
                return;
            }
            var name = $(this).attr("name");
            if (typeof name !== typeof undefined && name !== false) {
                if ($(this).attr("type") == "file") {
                    var err = '';
                    var file = $(this).val();
                    if ($(this).hasClass("required")) {
                        if (file == "") {
                            valid = false;
                            err = $(this).attr("data-empty");
                        }
                    }
                    if (file != '') {

                        var datadimension = $(this).attr("data-dimension");
                        if (typeof datadimension !== typeof undefined && datadimension !== false) {
                            var dataid = $(this).attr("data-id");
                            var width = $('#' + dataid).width();
                            var height = $('#' + dataid).height();
                            var checkwidth = parseFloat(width) * parseFloat(datadimension);
                            if (parseFloat(checkwidth) != parseFloat(height)) {
                                valid = false;
                                err = $(this).attr("data-dimension-err");
                            }
                        }
                        var a = (this.files[0].size);
                        var datasize = $(this).attr("data-size");
                        if (typeof datasize !== typeof undefined && datasize !== false) {
                            if (a > datasize) {
                                valid = false;
                                err = $(this).attr("data-err");
                            };
                        } else {
                            if (a > 1000000) {
                                valid = false;
                                err = $(this).attr("data-err");
                            };
                        }
                        var fileExtension = file.replace(/^.*\./, '');
                        if ($(this).attr('name') == 'photo' || $(this).attr('name') == 'image' || $(this).attr('name') == 'image[]') {
                            if (fileExtension != 'jpeg' && fileExtension != 'jpg' && fileExtension != 'png' && fileExtension != 'webp') {
                                valid = false;
                                err = "File Type not allowed";
                            }
                        }
                    }
                    if (err != '') {
                        $(this)
                            .closest(".imgdiv")
                            .find(".showerror")
                            .after("<p class='error'>" + err + "</p>");
                    }
                }
                if ($(this).hasClass("required")) {
                    if ($(this).attr("type") == "checkbox") {
                        if (checkname != "" && checkname == $(this).attr("name")) {
                            return;
                        }
                        checkname = $(this).attr("name");
                        if (!$("input[name='" + checkname + "']").is(":checked")) {
                            if ($(this).hasClass('noerrortext')) {
                                $(this).next('label.errortext').css('color', 'red');
                                valid = false;
                                return;
                            }
                            valid = false;
                            var err = $(this).attr("data-empty");
                            $(this).after("<p class='chkerror'>" + err + "</p>");
                            $(this).css("border-color", "red");
                        }
                    } else if ($(this).attr("type") == "radio") {
                        if (radname != "" && radname == $(this).attr("name")) {
                            return;
                        }
                        radname = $(this).attr("name");
                        var isChecked = $("input[name='" + radname + "']:checked").val() ?
                            true :
                            false;
                        if (isChecked == false) {
                            valid = false;
                            var err = $(this).attr("data-empty");
                            $(this).after("<p class='raderror'>" + err + "</p>");
                            $(this).css("border-color", "red");
                        }
                    } else if ($(this).attr("type") != "file") {
                        if ($(this).is("textarea")) {
                            var val = $(this).val();
                            val = $("<div/>").html(val).text();
                        } else
                            var val = $(this).val();
                        if (val == "") {
                            valid = false;
                            var err = $(this).attr("data-empty");
                            $(this).after("<p class='error'>" + err + "</p>");
                            $(this).css("border-color", "red");
                        }
                    }
                }
                if ($(this).is("textarea") || ($(this).is("input") && ($(this).attr('type') == 'text' || $(this).attr('type') == 'input' || $(this).attr('type') == 'number') || $(this).attr('type') == 'password')) {
                    var datamin = $(this).attr("data-min");
                    if (typeof datamin !== typeof undefined && datamin !== false) {
                        if ($(this).is("textarea")) {
                            var val = $(this).val();
                            val = $("<div/>").html(val).text();
                        } else
                            var val = $(this).val();
                        if (val != '') {
                            var min = $(this).attr('data-min');
                            if (min > val.length) {
                                valid = false;
                                var err = $(this).attr("data-lengtherr");
                                $(this).after("<p class='error'>" + err + "</p>");
                                $(this).css("border-color", "red");
                            }
                        }
                    }
                    var datamax = $(this).attr("data-max");
                    if (typeof datamax !== typeof undefined && datamax !== false) {
                        if ($(this).is("textarea")) {
                            var val = $(this).val();
                            val = $("<div/>").html(val).text();
                        } else
                            var val = $(this).val();
                        if (val != '') {
                            var max = $(this).attr('data-max');
                            if (max < val.length) {
                                valid = false;
                                var err = $(this).attr("data-lengtherr");
                                $(this).after("<p class='error'>" + err + "</p>");
                                $(this).css("border-color", "red");
                            }
                        }
                    }
                }
                if ($(this).attr("type") == "email") {
                    var email = $(this).val();
                    if (email != "") {
                        var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
                        if (!filter.test(email)) {
                            valid = false;
                            var err = $(this).attr("data-err");
                            $(this).after("<p class='error'>" + err + "</p>");
                            $(this).css("border-color", "red");
                        }
                    }
                } else if ($(this).attr("data-regx")) {
                    var regx = $(this).attr("data-regx");
                    var val = $(this).val();
                    if (val != "") {
                        if (!val.match(regx)) {
                            valid = false;
                            var err = $(this).attr("data-err");
                            $(this).after("<p class='error'>" + err + "</p>");
                            $(this).css("border-color", "red");
                        }
                    }
                }
                if ($(this).hasClass("cpassword")) {
                    var pass = $(".password").val();
                    var cpassword = $(".cpassword").val();
                    if (pass != cpassword) {
                        valid = false;
                        var err = $(this).attr("data-err");
                        $(this).after("<p class='error'>" + err + "</p>");
                        $(this).css("border-color", "red");
                    }
                }
            }
        });
        if (valid == false) {
            if ($(".error").length) {
                $('.error:first').closest('.hidden').removeClass('hidden');
                $('html, body').animate({ scrollTop: $('.error:first').offset().top - 200 }, 'slow');
            }
        }
        return valid;
    }
    /* End Validate */

$('body').on('click', '.editable .edit', function() {
    var that = $(this);
    that.closest('.editable').find('.element').addClass('hidden');
    that.closest('.editable').find('.inputfield').removeClass('hidden');
});

$('body').on('click', '.editable .close', function() {
    var that = $(this);
    that.closest('.editable').find('.inputfield .field').each(function(index, value) {
        // console.log(`div${index}: ${this.id}`);
        var val = $(this).val();
        if (val != '') {
            if ($(this).hasClass('linkfield')) {
                that.closest('.editable').find('.element').attr('href', val);
            } else {
                that.closest('.editable').find('.element').html(val + '<i class="fas fa-edit edit"></i>');
            }
        }
    });
    that.closest('.editable').find('.inputfield').addClass('hidden');
    that.closest('.editable').find('.element').removeClass('hidden');
});



function ajaxSubmission(e, id, url) {
    $(id).find('.submitBtn').addClass('d-none');
    e.preventDefault();
    $('.error').remove();
    var base_url = $('#rootfolder').val();
    $(id).find('.submitBtn').prop('disabled', true);
    
    $('#ajaxmessage').html('');
    var result = $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: base_url + '/'+ url,
        type: "POST",
        datatype: "json",
        data: new FormData($(id)[0]),
        contentType: false,
        cache: false,
        processData: false,
        async: false,
        success: function(data) {
            /* data = JSON.parse(data); */
            $(id).find('.submitBtn').prop('disabled', false);
            $(id).find('.submitBtn').removeClass('d-none');
            if(data.status=='0'){
                if(data.errors){
                    $.each(data.errors, function(key, value) {
                        key = key.replace(".", "");
                        var keyid = "#" + key + "-error";
                        var error_lable = '<label class="error w-100 text-center" for="' + key + '" style="">'+ value +'</label>';
                        if(url=='businesslicenseform.php'){
                            if(key=='g-recaptcha-response')
                                $(id).find(keyid).html(error_lable);
                            else
                                $(id).prepend(error_lable);
                        }
                        else{
                            $(id).find(keyid).html(error_lable);
                        }
                    });
                }
                else if(data.message){
                    if($('#ajaxmessage').length){
                        var html ='<p class="error mb-0">'+data.message+'</p>';
                        $('#ajaxmessage').html(html);
                    }
                }
                if(typeof grecaptcha !== 'undefined')
                    grecaptcha.reset();
                return false;
            }
            if(data.status=='1'){
                if(data.submission){
                    $(id).submit();
                    return false;
                }
                if(url!='checklicense.php')
                {
                    var html ='<div class="alert alert-success" role="alert">'+data.message+'</div>';
                    $(id).prepend(html);
                    setTimeout(function(){ $('.alert').remove(); }, 5000);
                    $(id).trigger("reset");
                }
                var scrolltop = 0;
                if(url=='businesslicenseform.php'){
                    $("#step1Content").fadeIn("slow");
                    $("#step3Content").hide();
                    
                    $(".step3").removeClass("active");
                    $(".step1").addClass("active");
                    scrolltop = 400;
                }
                else if(url=='careerSubmission.php'){
                    $('.file-upload-wrapper').attr('data-text','No File Selected');
                    $('.filter-option-inner-inner').text('Select');
                }
                else if(url=='newslettersubmission.php'){
                    scrolltop = '';
                }                
                if(scrolltop!='')
                {
                    $("body,html").animate({
                        scrollTop : scrolltop    
                    }, 400);
                }
                if(typeof grecaptcha !== 'undefined')
                    grecaptcha.reset();
            }
            if(url=='checklicense.php'){
                if(data.status=='1'){
                    if($('#ajaxmessage').length){
                        var html ='<p class="text-success">License Found</p><p class="mb-0">'+data.message+'</p>';
                        $('#ajaxmessage').html(html);
                    }
                }
                else if(data.status=='2'){
                    if($('#ajaxmessage').length){
                        var html ='<p class="error">License Expired</p><p class="mb-0">'+data.message+'</p>';
                        $('#ajaxmessage').html(html);
                    }
                }
            }
            if(url=='admin/licence/update' || url=='ubopasswordupdate.php')
            {
                location.reload();
            }
        },
        error: function(xhr, status, error) {
           /*  // grecaptcha.reset(); */
            $(id).find('.submitBtn').prop('disabled', false);
            $(id).find('.submitBtn').removeClass('d-none');
            var result = eval("(" + xhr.responseText + ")");
            $.each(result.errors, function(key, value) {
                var keyname = key;
                key = key.replace(".", "");
                var id = "#" + key + "-error";
                var error_lable = '<label id="' + key + '-error" class="error" for="' + key + '" style="">'+ value +'</label>';
                $('[name="' + keyname + '"]').after(error_lable);
            });                
            return true;
        }
    });
}

$('body').on('click','.ajaxpagination .pagntn .btn',function(e){
    e.preventDefault();
    var dataurl = $(this).attr('data-url');
    if (typeof dataurl !== 'undefined' && dataurl !== false) {
        var result = $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: dataurl,
            type: "GET",
            datatype: "html",
            success: function(data) {
                $('#designModal .modal-body').html(data);
            },
            error: function(xhr, status, error) {
    
            }
        });
    }
});
