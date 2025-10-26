<script src="{{ asset('vendor/gallery/js/gallery.js') }}"></script>
<script>
    var galleryinput = "<?php echo $name; ?>";
    var root = $('#rootfolder').val();
    function refreshgallerymodal(type='image',category=''){
        var result = $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: root+'/admin/gallery/popup?type='+type+'&allowed=image,mp4&category='+category,
            type: "GET",
            datatype: "html",
            success: function(data) {
                $('#gallerymodalbody').html(data);
            },
            error: function(xhr, status, error) {
                
            }
        });
    }
    $('body').on('click', '.imgbtn', function() {
        var type = $(this).closest('.imageselect').find('.galleryinputfield').attr('data-type');
        var category = $(this).closest('.imageselect').find('.galleryinputfield').attr('data-category');
        if (!(typeof type !== typeof undefined && type !== false)) {
           type = 'image';
        }
        if (!(typeof category !== typeof undefined && category !== false)) {
           category = '';
        }
        refreshgallerymodal(type,category);
        $('.imageselect').removeClass('active');
        $(this).closest('.imageselect').addClass('active');
        $('#galleryModal').modal('show');
    });
    $('body').on('click', '.removeimagebtn', function() {
        $(this).closest('.imageselect').find('.galleryinputfield').val('');
        $(this).closest('.imageselect').find('.altimageinput').val('');
        $(this).closest('.imageselect').find('.altimageinputdiv').addClass('hidden');
        $(this).closest('.imageselect').find('.video').addClass('hidden');
        $(this).closest('.imageselect').find('img').removeClass('hidden');
        $(this).closest('.imageselect').find('img').attr('src',root+'/image/placeholder.jpg');
        $(this).closest('.imageselect').find('.galleryinputfield').trigger("change");
    });
    $('body').on('click', '.imgselect', function() {
        var type = $(this).attr('data-type');
        $('#galleryModal').modal('hide');
        var image = $(this).attr('data-image');
        if($('.imageselect.active').find('.altimageinput').val()){
          var alt = $('.imageselect.active').find('.altimageinput').val();
          $('.imageselect.active').find('.galleryinputfield').val(image+'|'+alt);
        }
        else{
            $('.imageselect.active').find('.galleryinputfield').val(image);
        }        
        $('.imageselect.active').find('.galleryinputfield').trigger("change");
        if(type=='image'){
            $('.imageselect.active video').addClass('hidden');
            $('.imageselect.active img').attr('src', root + '/gallery/' + image);
            $('.imageselect.active img').removeClass('hidden');
        }
        else if(type=='mp4'){
            $('.imageselect.active img').addClass('hidden');
            $('.imageselect.active video').attr("src", root + '/gallery/' + image);
            $('.imageselect.active video').removeClass('hidden');
        }
        else if(type=='pdf'){
            $('.imageselect.active video').addClass('hidden');
            $('.imageselect.active img').attr('src', root + '/image/pdf.png');
            $('.imageselect.active img').removeClass('hidden');
        }
        else if(type.toString().toLowerCase()=='docx'){
            $('.imageselect.active video').addClass('hidden');
            $('.imageselect.active img').attr('src', root + '/image/document.png');
            $('.imageselect.active img').removeClass('hidden');
        }
    });
</script>