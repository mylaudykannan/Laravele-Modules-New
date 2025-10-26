@extends('admin.layouts.app')
@push('stylesheets')
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('css/gallery.css') }}"> --}}
@endpush

@section('content')

<div class="container-fluid">
    @include('Gallery::admin.inc.alert')
    <div class="row contentTopMrg">

        <div class="col-12">
            <form method="post" id="gallerycategoryform">
                @csrf
                <div class="card customCard">
                    <div class="card-body pt-2">
                        <div class="gallerymessage"></div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <input type="text" class="form-control required" id="category" placeholder="Enter Category"
                                name="category" data-empty="Enter Category" data-max="250"
                                data-lengtherr="Maximum 250 characters allowed" />
                        </div>
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-success pull-right" id="gallerycategorysubmit">Submit</button> <a
                    href="{{URL()->previous()}}" class="btn btn-success">Back</a>
            </form>
        </div>

    </div>
</div>
@endsection
@push('scripts')
<script>
    $('body').on('click', '#gallerycategorysubmit', function(e) {
        e.preventDefault();
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
                $('#gallerycategoryform')[0].reset();
                window.location.href= root + '/admin/gallery/category';
            }
        },
        error: function(xhr, status, error) {
            
        }
    });
});
</script>
@endpush