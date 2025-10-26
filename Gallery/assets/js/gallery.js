var root = $('#rootfolder').val();

function refreshgallery() {
    if ($('#galleryfilterform').length) {
        var name = $('#galleryfilterform').find('[name=name]').val();
        var category = $('#galleryfilterform').find('[name=category]').val();
        var type = $('#galleryfilterform').find('[name=type]').val();
    } else {
        var name = '';
        var category = '';
        var type = '';
    }

    var result = $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: root + '/admin/gallery/ajaxload?name=' + name + '&category=' + category + '&type=' + type,
        type: "GET",
        datatype: "html",
        success: function(data) {
            $('#gallery').html(data);
        },
        error: function(xhr, status, error) {

        }
    });
}
$('body').on('click', '#gallerysubmit', function() {
    var result = $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: root + '/admin/gallery/add',
        type: "POST",
        datatype: "json",
        data: new FormData($('#galleryform')[0]),
        contentType: false,
        cache: false,
        processData: false,
        async: false,
        success: function(data) {
            if (data.success == 0) {
                var error = '<div class="alert alert-danger alert-dismissible fade show errormessage" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                $('.gallerymessage').html(error);
            } else {
                var success = '<div class="alert alert-success alert-dismissible fade show errormessage" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                $('.gallerymessage').html(success);
                refreshgallery();
                $('#galleryform')[0].reset();
                $('#choosefiletext').text('Choose File');
            }
        },
        error: function(xhr, status, error) {
        }
    });
});
$('body').on('click', '#addcategorybtn', function() {
    $('#galleryform').addClass('hidden');
    $('#gallerycategoryform').removeClass('hidden');
});
$('body').on('click', '#gallerycategorycancel', function() {
    $('#gallerycategoryform').addClass('hidden');
    $('#galleryform').removeClass('hidden');
});
$('body').on('click', '#showgalleryupload', function() {
    $('#galleryfilterform').addClass('hidden');
    $('#galleryform').removeClass('hidden');
});
$('body').on('click', '#gallerysearchbtn', function() {
    $('#galleryform').addClass('hidden');
    $('#galleryfilterform').removeClass('hidden');
});
$('body').on('click', '#gallerycategorysubmit', function() {
    var result = $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: root + '/admin/gallery/addcategory',
        type: "POST",
        datatype: "json",
        data: new FormData($('#gallerycategoryform')[0]),
        contentType: false,
        cache: false,
        processData: false,
        async: false,
        success: function(data) {
            if (data.success == 0) {
                var error = '<div class="alert alert-danger alert-dismissible fade show errormessage" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                $('.gallerymessage').html(error);
            } else {
                var success = '<div class="alert alert-success alert-dismissible fade show errormessage" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                $('.gallerymessage').html(success);
                var result = $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: root + '/admin/gallery/ajaxloadcategory',
                    type: "GET",
                    datatype: "html",
                    success: function success(data) {
                        $('.gallerycategory option').remove();
                        $('.gallerycategory').append(data);
                    },
                    error: function error(xhr, status, _error4) {}
                });
                refreshgallery();
                $('#galleryform')[0].reset();
                $('#gallerycategoryform')[0].reset();
                $('#choosefiletext').text('Choose File');
                $('#gallerycategoryform').addClass('hidden');
                $('#galleryform').removeClass('hidden');
            }
        },
        error: function(xhr, status, error) {
        }
    });
});
$('body').on('click', '.imgdelete', function() {
    var href = $(this).attr('data-href');
    var result = $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: href,
        type: "GET",
        datatype: "json",
        async: false,
        success: function(data) {
            var success = '<div class="alert alert-success alert-dismissible fade show errormessage" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
            $('.gallerymessage').html(success);
            refreshgallery();
            hidegalleryImageDelete();
        },
        error: function(xhr, status, error) {
        }
    });
});
$('body').on('change', '#inputGroupFile01', function() {
    var input = this;
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#choosefiletext').text(input.files[0].name);
            var filename = input.files[0].name;
            filename = filename.split('.')[0];
            $('#filename').val(filename);
        }

        reader.readAsDataURL(input.files[0]);
    } else {
        $('#choosefiletext').text('Choose File');
        $('#filename').val('');
    }
});
$('body').on('click', '#loadmoregallery', function() {
    var name = $('#galleryfilterform').find('[name=name]').val();
    var category = $('#galleryfilterform').find('[name=category]').val();
    var type = $('#galleryfilterform').find('[name=type]').val();
    var limit = parseInt($('#gallerylimit').val()) + 12;
    var result = $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: root + '/admin/gallery/ajaxload?limit=' + limit + '&name=' + name + '&category=' + category + '&type=' + type,
        type: "GET",
        datatype: "html",
        success: function success(data) {
            $('#gallerylimit,#loadmoregallery').remove();
            $('#gallery').append(data);
        },
        error: function error(xhr, status, _error4) {}
    });
});
window.hidegalleryImage = function() {    
    $('.galleryImage').modal('hide');
    $('#galleryModal').css('overflow', 'auto');
};
window.hidegalleryImageDelete = function() {
    $('.galleryImageDelete').modal('hide');
    $('#galleryModalDelete').css('overflow', 'auto');
};

$('body').on('keyup focus', '.suggestioninput', function() {
    var that = $(this);
    var value = that.val();
    var url = that.attr('suggestion-url');
    if (value != '') {
        that.closest('.suggestiondiv').find('.suggestionresult').removeClass('hidden');
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: root + url,
            type: "POST",
            datatype: "html",
            data: { 'value': value },
            success: function(data) {
                that.closest('.suggestiondiv').find('.suggestionresult').html(data);
            }
        });
    } else {
        that.closest('.suggestiondiv').find('.suggestionresult').addClass('hidden');
    }
});
$('body').on('click', '.suggestionresult li', function() {
    var value = $(this).text();
    $(this).closest('.suggestiondiv').find('.suggestioninput').val(value);
});
$('body').on('click', '.suggestionclose', function() {
    $(this).closest('.suggestiondiv').find('.suggestionresult').addClass('hidden');
});
$(document).click(function(e) {
    if (!($(e.target).hasClass('suggestionresult') || $(e.target).parents(".suggestionresult").length || $(e.target).hasClass('suggestioninput'))) {
        $('.suggestionresult').addClass('hidden');
    }
});

$('body').on('click','.altimagebtn',function(){
    var _this = $(this);
    _this.closest('.imageselect').find('.altimageinputdiv').toggleClass('hidden');
    if(_this.closest('.imageselect').find('.altimageinputdiv').hasClass('hidden'))
    {
        _this.closest('.imageselect').find('.altimageinput').val('');
        _this.closest('.imageselect').find('.altimageinput').trigger('change');
    }
});

$('body').on('change','.altimageinput',function(){
    var _this = $(this);
    var val = _this.val();
    var image = _this.closest('.imageselect').find('.galleryinputfield').val();
    var imagear = image.split('|');
    if(val!=''){
        if(imagear[0]!='')
            var newimage = imagear[0]+'|'+val;
    }
    else{
        var newimage = imagear[0];
    }
    _this.closest('.imageselect').find('.galleryinputfield').val(newimage);
});