@extends('admin.layouts.app')
@push('stylesheets')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" />
    <link rel="stylesheet" href="{{ asset('vendor/menu') }}/css/bootstrap-iconpicker.min.css">
    <style>
        #categoryList,
        #pageList {
            max-height: 229px;
            overflow: hidden;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row contentTopMrg">
            <div class="col-12">
                <div class="card customCard">

                    <div class="card-body pt-2">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-primary mb-3">
                                    <div class="card-header bg-primary text-white">Add Menu</div>
                                    <div class="card-body">
                                        <form id="frmEdit" class="form-horizontal">
                                            <div class="form-group">
                                                <label for="text">Text</label>
                                                <div class="">
                                                    <input type="text" class="form-control item-menu required"
                                                        name="text" id="text" placeholder="Text"
                                                        data-empty="Enter Text">
                                                    {{-- <div class="input-group-append">
                                                    <button type="button" id="myEditor_icon"
                                                        class="btn btn-outline-secondary"></button>
                                                </div> --}}
                                                </div>
                                                <input type="hidden" name="icon" class="item-menu">
                                            </div>
                                            <div class="form-group">
                                                <label for="href">URL</label>
                                                <input type="text" class="form-control item-menu required" id="href"
                                                    name="href" placeholder="URL" data-empty="Enter URL">
                                            </div>
                                            <div class="form-group">
                                                <label for="target">Target</label>
                                                <select name="target" id="target" class="form-control item-menu">
                                                    <option value="_self">Self</option>
                                                    <option value="_blank">Blank</option>
                                                    <option value="_top">Top</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="title">Tooltip</label>
                                                <input type="text" name="title" class="form-control item-menu"
                                                    id="title" placeholder="Tooltip">
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" id="btnUpdate" class="btn btn-primary" disabled><i
                                                class="fas fa-sync-alt"></i> Update</button>
                                        <button type="button" id="btnAdd" class="btn btn-success"><i
                                                class="fas fa-plus"></i> Add</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h5 class="float-left">Menu</h5>
                                        <div class="float-right">

                                            <?php $titleurl = strtok($title, '_'); ?>

                                            <a href="{{ URL('admin/menu?menu=' . $titleurl) }}"
                                                class="btn btn-warning @if (strpos($title, '_') !== false) active @endif">Edit
                                                English</a>
                                            <a href="{{ URL('admin/menu?menu=' . $titleurl . '_ar') }}"
                                                class="btn btn-warning @if (strpos($title, '_ar') == false) active @endif">Edit
                                                Arabic</a>
                                            <a href="{{ URL('admin/menu?menu=' . $titleurl . '_ru') }}"
                                                class="btn btn-warning @if (strpos($title, '_ru') == false) active @endif">Edit
                                                Russian</a>
                                            <button id="btnOutput" type="button" class="btn btn-success"><i
                                                    class="fas fa-check-square"></i>
                                                Save</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <ul id="myEditor" class="sortableLists list-group">
                                        </ul>
                                    </div>
                                </div>
                                <div class="card" style="display:none;">

                                    <div class="card-body">
                                        <form action="{{ URL('admin/menu/store') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="title" value="{{ $title }}">
                                            <div class="form-group">
                                                <textarea id="out" class="form-control" cols="50" rows="10" name="menu"></textarea>
                                            </div>
                                            <input type="submit" value="Submit" style="display:none;" id="menusubmit">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php $menu = !empty($menu) ? $menu->content : '{}'; ?>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/menu') }}/jquery-menu-editor.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/menu') }}/js/iconset/fontawesome5-3-1.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/menu') }}/js/bootstrap-iconpicker.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#pageInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#pageList li").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            $("#categoryInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#categoryList li").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
        jQuery(document).ready(function() {
            /* =============== DEMO =============== */
            /* // menu items */
            var arrayjson = '<?php echo $menu; ?>';
            var iconPickerOptions = {
                searchText: "Buscar...",
                labelHeader: "{0}/{1}"
            };
            /* // sortable list options */
            var sortableListOptions = {
                placeholderCss: {
                    'background-color': "#cccccc"
                }
            };

            var editor = new MenuEditor('myEditor', {
                listOptions: sortableListOptions,
                iconPicker: iconPickerOptions
            });
            editor.setForm($('#frmEdit'));
            editor.setUpdateButton($('#btnUpdate'));
            editor.setData(arrayjson);
            var str = editor.getString();
            $("#out").text(str);

            $('#btnOutput').on('click', function() {
                var str = editor.getString();
                $("#out").text(str);
                $('#menusubmit').click();
            });

            $("#btnUpdate").click(function(e) {
                var valid = validation(e, '#frmEdit');
                if (valid == true) {
                    editor.update();
                }
            });

            $('#btnAdd').click(function(e) {
                var valid = validation(e, '#frmEdit');
                if (valid == true) {
                    editor.add();
                }
            });
            $('#btnAddPage').click(function() {
                $("input[name=page]:checked").each(function() {
                    var text = $(this).attr('data-name');
                    var url = $(this).attr('data-url');
                    $('#text').val(text);
                    $('#href').val(url);
                    $('#btnAdd').click();
                });
                $('input[name=page]:checked').prop("checked", false);
            });
            $('#btnAddCategory').click(function() {
                $("input[name=category]:checked").each(function() {
                    var text = $(this).attr('data-name');
                    var url = $(this).attr('data-url');
                    $('#text').val(text);
                    $('#href').val(url);
                    $('#btnAdd').click();
                });
                $('input[name=category]:checked').prop("checked", false);
            });
            /* ====================================== */

        });
    </script>
@endpush
