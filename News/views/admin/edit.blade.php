@extends('admin.layouts.app')

@push('stylesheets')
<style>
    .settingsdiv .settingbtn.breadcrumb {
        display: none;
    }

</style>
@if (config('news.view.' . $slug))
<style>
    #editdiv {
        display: none;
    }

    .transparent:after {
        content: '';
        position: absolute;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        background: #ffffffc2;
        z-index: 999999;
    }

    #viewdiv {
        position: absolute;
        top: 100px;
        right: 20px;
        width: 85%;
    }

    .vieweditbtn {
        color: #ff0000;
        background: #fff;
        cursor: pointer;
        float: right;
        z-index: 999999;
        position: relative;
    }

</style>
@endif
@switch($slug)
@case('home')
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/home.css') }}">
@case('patient')
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/patient.css') }}">
@case('dhca-governance-laws')
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/regulations.css') }}">
@endswitch
<link rel="stylesheet" type="text/css" href="{{ asset('css/gallery.css') }}">
@endpush
@section('content')
<div class="container-fluid" id="editdiv">
    @include('Gallery::admin.inc.alert')
    <div class="row">
        <div class="col-12">
            <form action="{{ URL('admin/news/update?lang=' . app()->getLocale()) }}" method="post" id="newsform">
                @csrf
                <div class="card customCard mt-4 mb-5" id="titlecard">
                    <!-- Card header -->
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                            <h5 class="text-white text-capitalize pl-3 mb-0">Edit News</h5>
                            <a href="{{ URL('admin/news/add') }}" class="btn btn-default mb-0 mr-3 btn-sm text-white">ADD NEW NEWS</a>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <div class="settingsdiv">
                            <span class="badge badge-secondary settingbtn bg-primary text-white" data-toggle="modal" data-target="#slugModal">
                                Edit Slug
                            </span>
                            <span class="badge badge-secondary settingbtn @if ($locale == 'en') active @endif" onClick="window.location.href='{{ url()->current() }}'">
                                English
                            </span>
                            <span class="badge badge-secondary settingbtn @if ($locale == 'ar') active @endif" onClick="window.location.href='{{ url()->current() }}?lang=ar'">
                                Arabic
                            </span>
                            <span class="badge badge-secondary settingbtn @if ($locale == 'ru') active @endif" onClick="window.location.href='{{ url()->current() }}?lang=ru'">
                                Russian
                            </span>
                        </div>
                        <div class="form-group">
                            @include('Gallery::admin.inc.mediainput', [
                            'name' => 'image',
                            'edit' => true,
                            'value' => $news['image'] ?? '',
                            'class' => 'required',
                            'attr' => 'data-empty=Select&nbsp;Image',
                            'sizemessage' => '600 x 400',
                            'showalt' => true,
                            ])
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control required" id="title" placeholder="Enter Title" name="title" value="{{ $news['title'] ?? '' }}" data-empty="Enter Title" data-max="250" data-lengtherr="Maximum 250 characters allowed" />
                        </div>
                        <div class="form-group @if (config('news.hidecontent.' . $slug)) hidden @endif hidden">
                            <label for="content">Content</label>
                            {{-- <textarea class="form-control" id="content" name="content"
                                placeholder="Enter Content" data-empty="Enter Content" data-allowed="news">{{$news['content'] ?? ''}}</textarea> --}}
                            <textarea class="form-control" id="content" name="content" placeholder="Enter Content" data-empty="Enter Content">{{ $news['content'] ?? '' }}</textarea>
                        </div>
                        <div class="form-group hidden">
                            <label for="title">Category</label>
                            <br>
                            <?php
                                    $categories = isset($news['category']) && $news['category'] != null ? json_decode($news['category'], true) : [];
                                    ?>
                            @foreach ($newscategory as $nkey => $nval)
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" value="{{ $nval['title'] }}" name="category[]" @if (in_array($nval['title'], $categories)) checked @endif>{{ $nval['title'] }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <div class="form-group">
                            <label for="title">Status</label>
                            <select class="form-control required" data-empty="Select Status" name="status" id="status">
                                <option value="">Select Status</option>
                                <option value="1" @if (isset($news['status']) && $news['status']=='1' ) selected @endif>Enable</option>
                                <option value="0" @if (isset($news['status']) && $news['status']=='0' ) selected @endif>Disable
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title">Publish Date</label>
                            <?php $puhdate = isset($news['publish_on']) && $news['publish_on'] != '' ? date('d-m-Y', strtotime($news['publish_on'] ?? '')) : ''; ?>
                            <input type="text" readonly class="form-control flatpicker required" value="{{ $puhdate }}" id="publish_on" placeholder="Enter Publish Date" name="publish_on" data-empty="Enter Publish Date" />
                        </div>
                        <input type="hidden" name="slug" value="{{ $slug }}" />
                    </div>
                </div>

                {{-- meta inputs --}}
                <div class="card customCard mt-4 mb-5" id="titlecard">
                    <div class="card-body pt-2">
                        <div class="form-group">
                            <label for="content">Meta Description</label>
                            <textarea class="form-control" name="meta_description" placeholder="Enter Meta Description" data-empty="Enter Meta Description">{{ $news['meta_description'] ?? '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="content">Meta Keywords</label>
                            <textarea class="form-control" name="meta_keywords" placeholder="Enter Meta Keywords" data-empty="Enter Meta Keywords">{{ $news['meta_keywords'] ?? '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="content">Analytics</label>
                            <textarea class="form-control" name="analytics" placeholder="Enter Analytics" data-empty="Enter Analytics">{{ $news['analytics'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                {{-- End meta inputs --}}

                @if (config('news.section.' . $slug))
                @foreach (config('news.section.' . $slug) as $k => $v)
                <div class="card customCard transparent">

                    <div class="card-body pt-2">



                        <div class="">
                            <div class="row clearfix">
                                <div class="col-md-12 table-responsive">
                                    <table id="tab_logic{{ $k }}" class="table table-bordered table-hover table-sortable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">{{ $v['title'] }}</th>
                                                @if (!config('news.section.' . $slug . '.' . $k . '.static'))
                                                <th class="text-center" style="border-top: 1px solid rgb(255, 255, 255); border-right: 1px solid rgb(255, 255, 255);width:100px;">
                                                </th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (
                                            (config('news.section.' . $slug . '.' . $k . '.static') && empty($newssections[$k])) ||
                                            !config('news.section.' . $slug . '.' . $k . '.static'))
                                            <tr id="{{ $k }}addr0" data-id="0" class="@if (!config('news.section.' . $slug . '.' . $k . '.static')) hidden novalidation @endif ">
                                                <td data-name="stitle">
                                                    @if (isset($v['blade']))
                                                    @include(
                                                    'News::admin.section.' .
                                                    $v['blade'],
                                                    ['k' => $k, 'firstrow' => true]
                                                    )
                                                    @else
                                                    @include(
                                                    'News::admin.section.fields',
                                                    ['k' => $k, 'firstrow' => true]
                                                    )
                                                    @endif
                                                </td>
                                                @if (!config('news.section.' . $slug . '.' . $k . '.static'))
                                                <td data-name="del"><button name="{{ $k }}del0" class="btn btn-danger row-remove"><i class="far fa-trash-alt"></i></button>
                                                    <span class="moveupbtn" title="Move Up"><i class="fas fa-arrow-alt-circle-up"></i></span>
                                                    <span class="movedownbtn" title="Move Down"><i class="fas fa-arrow-alt-circle-down"></i></span>
                                                </td>
                                                @endif
                                            </tr>
                                            @endif
                                            @if (isset($newssections[$k]))
                                            @foreach ($newssections[$k] as $sk => $sv)
                                            <tr id="{{ $k }}addr{{ $sk + 1 }}" data-id="{{ $sk + 1 }}" class="">
                                                <td data-name="{{ $k }}stitle">
                                                    @if (isset($v['blade']))
                                                    @include(
                                                    'News::admin.section.' .
                                                    $v['blade'],
                                                    ['k' => $k, 'edit' => true]
                                                    )
                                                    @else
                                                    @include(
                                                    'News::admin.section.fields',
                                                    ['k' => $k, 'edit' => true]
                                                    )
                                                    @endif
                                                </td>
                                                @if (!config('news.section.' . $slug . '.' . $k . '.static'))
                                                <td data-name="del"><a href="{{ URL('admin/news/sectiondelete/' . $sv['id']) }}" name="{{ $k }}del{{ $sk + 1 }}" class="btn btn-danger row-remove"><i class="far fa-trash-alt"></i></button></a>
                                                    <span class="moveupbtn" title="Move Up"><i class="fas fa-arrow-alt-circle-up"></i></span>
                                                    <span class="movedownbtn" title="Move Down"><i class="fas fa-arrow-alt-circle-down"></i></span>
                                                </td>
                                                @endif
                                            </tr>
                                            @endforeach
                                            @endif



                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if (!config('news.section.' . $slug . '.' . $k . '.static'))
                            <a id="add_row{{ $k }}" class="btn btn-primary pull-left text-white add_row" data-section="{{ $k }}">Add Row</a><input type="hidden" name="loopcount{{ $k }}" value="{{ count($newssections[$k] ?? []) }}" id="loopcount{{ $k }}">
                            @endif

                        </div>
                    </div>


                </div>
                <hr>
                @endforeach
                @endif
                <div class="d-flex submitbtndiv">
                    @include('admin.inc.updatenotes', ['form' => '#newsform'])
                    <a href="{{ URL('admin/news') }}" class="btn btn-success ml-3 mb-0">Back</a>
                    <button type="submit" class="btn btn-success  ml-3 mb-0" id="draftsubmitbtn">Save as
                        Draft</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('Gallery::admin.inc.gallerymodal')



<!-- Slug Modal -->
<div class="modal fade" id="slugModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <!-- Use modal-lg, modal-sm, or modal-xl -->
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Slug</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form method="post" action="{{ URL('admin/news/updateslug') }}">
                    @csrf
                    <div class="col-sm-4 float-left">
                        <div class="">

                            <input type="text" class="form-control" placeholder="Slug" name="slugchange" autocomplete="off" value="{{ $news['slug'] ?? '' }}" />
                            <input type="hidden" name="slug" value="{{ $slug }}" />
                        </div>
                    </div>


                    <div class="col-sm-6 float-left">

                        <div class="">
                            <button type="submit" class="btn btn-primary">Submit</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>&nbsp;&nbsp;

                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Learn More</button>
            </div> --}}

        </div>
    </div>
</div>
<!-- End Slug Modal -->
@endsection
@push('scripts')
@include('admin.inc.addsectionscript')
@include('Gallery::admin.inc.mediascript', ['name' => 'image'])
<script>
    var root = $('#rootfolder').val();
    var locale = '<?php echo app()->getLocale(); ?>';
    $('body').on('click', '#draftsubmitbtn', function(e) {
        e.preventDefault();
        var _this = $(this);
        _this.prop('disabled', true);
        var result = $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , url: root + '/admin/draftnews/update?fromnews&lang=' + locale
            , type: "POST"
            , datatype: "json"
            , data: new FormData($('#newsform')[0])
            , contentType: false
            , cache: false
            , processData: false
            , async: false
            , success: function(data) {
                _this.prop('disabled', false);
                window.location.href = data.redirect;
            }
            , error: function(xhr, status, error) {
                _this.prop('disabled', false);
                var result = eval("(" + xhr.responseText + ")");
                $.each(result.errors, function(key, value) {
                    var keyname = key;
                    key = key.replace(".", "");
                    var id = "#" + key + "-error";
                    var error_lable = '<label id="' + key + '-error" class="error" for="' +
                        key + '" style="">' + value + '</label>';
                    $('[name="' + keyname + '"]').after(error_lable);
                });
                return true;
            }
        });
    });

    function showedit(element, innerelement = '') {
        $('#viewdiv').css('display', 'none');
        $('#editdiv').css('display', 'block');
        if ($(element).hasClass('transparent')) {
            $(element).removeClass('transparent');
            $(element).attr("tabindex", -1).focus();
        } else {
            $(element).closest('.transparent').attr("tabindex", -1).focus();
            $(element).closest('.transparent').removeClass('transparent');
        }
        if (innerelement != '') {
            $(innerelement).attr("tabindex", -2).focus();
            $(innerelement).prevAll().addClass('transparent');
            $(innerelement).nextAll().addClass('transparent');
        }
    }

</script>
@if (isset($newssections) && count($newssections) == 0)
<script>
    $(document).ready(function() {
        $('#add_rowcontent').click();
    });

</script>
@endif
@switch($slug)
@case('home')
@include('admin.gallery.inc.home.script')
@case('under-excellence-awards')
@include('admin.gallery.inc.under-excellence-awards.script')
@case('under-recognition-awards')
@include('admin.gallery.inc.under-recognition-awards.script')
@case('awards-2018')
@include('admin.gallery.inc.awards-2018.script')
@case('awards-2019')
@include('admin.gallery.inc.awards-2019.script')
@case('awards-2020')
@include('admin.gallery.inc.awards-2020.script')
@endswitch
@endpush
@include('admin.inc.tinyscript')
@if (!empty($news) && config('news.view.' . $slug))
@include('News::admin.view.' . $slug)
@endif
