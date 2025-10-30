{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
<script>
    var rootfolder = $('#rootfolder').val();
    $(document).ready(function() {
        $(".add_row").on("click", function() {
            var section = $(this).attr('data-section');
            // Dynamic Rows Code

            // Get max row id and set new id
            var newid = 0;
            $.each($("#tab_logic" + section + " tr"), function() {
                if (parseInt($(this).data("id")) > newid) {
                    newid = parseInt($(this).data("id"));
                }
            });
            newid++;

            var tr = $("<tr></tr>", {
                id: "addr" + section + newid
                , "data-id": newid
            });
            $('#loopcount' + section).val(newid);
            // loop through each td and create new elements with name of newid
            $.each($("#tab_logic" + section + " tbody tr:nth(0) td"), function() {
                var td;
                var cur_td = $(this);

                var children = cur_td.children();

                // add new td and element if it has a nane
                if ($(this).data("name") !== undefined) {
                    td = $("<td></td>", {
                        "data-name": $(cur_td).data("name")
                    });

                    var c = $(cur_td).find($(children[0]).prop('tagName')).clone().val("");
                    // c.attr("name", $(cur_td).data("name") + newid);
                    console.log(c);
                    c.appendTo($(td));
                    $(td).find('input,textarea,select').each(function(index, value) {
                        var name = $(this).attr('name');
                        // alert(name);
                        $(this).attr('name', name + newid);
                    });
                    td.find('textarea').attr('id', 'tinyarea' + section + newid);

                    //append up nad down arrow
                    if ($(cur_td).data("name") == "del") {
                        td.append('<span class="moveupbtn" title="Move Up"><i class="fas fa-arrow-alt-circle-up"></i></span><span class="movedownbtn" title="Move Down"><i class="fas fa-arrow-alt-circle-down"></i></span>');
                    }

                    td.appendTo($(tr));

                } else {
                    td = $("<td></td>", {
                        'text': $('#tab_logic' + section + ' tr').length
                    }).appendTo($(tr));
                }
            });

            // add delete button and td
            /* 
            $("<td></td>").append(
                $("<button class='btn btn-danger glyphicon glyphicon-remove row-remove'></button>")
                    .click(function() {
                        $(this).closest("tr").remove();
                    })
            ).appendTo($(tr));
            */

            // add the new row
            $(tr).appendTo($('#tab_logic' + section));

            $(tr).find("td button.row-remove").on("click", function() {
                $(this).closest("tr").remove();
                var countval = $('#loopcount' + section).val();
                var newcount = parseInt(countval) - 1;
                $('#loopcount' + section).val(newcount);
            });
            /* tinymce.init({
                entity_encoding: 'raw',
                height: "500",
                forced_root_block: "",
                force_br_newlines: true,
                force_p_newlines: false,
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                content_css: rootfolder + "/tinymce/tinymce_custom.css",
                selector: '#tinyarea' + section + newid + ':not(.textarea)',
                end_container_on_empty_block: true,
                menubar: false,
                toolbar: true,
                verify_html: true,
                // content_style: "h1 { font-size: 14pt; font-family: Arial; font-weight: normal; }", 
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'lists',
                    'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'wordcount', 'visualblocks'
                ],
                toolbar: 'newdocumentcustom | linkcustom | previewcustom | fullscreencustom | customImageButton | customDesign | bgImage | customEleDesign | code | removeemptyline',
                valid_elements: '*[*]',
                setup: function(editor) {
                    editor.on('change', function() {
                        tinymce.triggerSave();
                    });
                    editor.ui.registry.addButton('removeemptyline', {
                        text: 'Remove Empty Lines',
                        icon: 'new-document',
                        context: 'newdocumentcustom',
                        onAction: function(_) {
                            removeEmptyline();
                        }
                    });
                    editor.ui.registry.addButton('newdocumentcustom', {
                        text: 'New document',
                        icon: 'new-document',
                        context: 'newdocumentcustom',
                        onAction: function(_) {
                            tinymce.get(editor.id).execCommand('mceNewDocument',
                                false);
                        }
                    });
                    editor.ui.registry.addButton('linkcustom', {
                        text: 'Link',
                        icon: 'link',
                        context: 'linkcustom',
                        onAction: function(_) {
                            tinymce.get(editor.id).execCommand('mceLink',
                                false);
                        }
                    });
                    editor.ui.registry.addButton('previewcustom', {
                        text: 'Preview',
                        icon: 'preview',
                        context: 'previewcustom',
                        onAction: function(_) {
                            tinymce.get(editor.id).execCommand('mcePreview',
                                false);
                        }
                    });
                    editor.ui.registry.addButton('fullscreencustom', {
                        text: 'Fullscreen',
                        icon: 'fullscreen',
                        context: 'fullscreencustom',
                        onAction: function(_) {
                            tinymce.get(editor.id).execCommand('mceFullScreen',
                                false);
                        }
                    });
                    editor.ui.registry.addButton('customImageButton', {
                        text: 'Image',
                        icon: 'image',
                        context: 'customImage',
                        onAction: function(_) {
                            $('.tinymcemedia .imgbtn').trigger('click');
                            $('#tinymce_item').val(editor.id);
                        }
                    });
                    editor.ui.registry.addButton('customDesign', {
                        text: 'Template',
                        icon: 'template',
                        context: 'customDesigns',
                        onAction: function(_) {
                            $('#tinymce_item').val(editor.id);
                            tinyDesign();

                        }
                    });
                    editor.ui.registry.addButton('customEleDesign', {
                        text: 'Design',
                        icon: 'template',
                        context: 'customEleDesigns',
                        onAction: function(_) {
                            $('#tinymce_item').val(editor.id);
                            tinyEleDesign();

                        }
                    });
                    editor.ui.registry.addMenuItem('customCopy', {
                        text: 'Copy',
                        context: 'customCopy',
                        onAction: function(_) {
                            $('#tinymce_item').val(editor.id);
                            getElePath();

                        }
                    });
                    editor.ui.registry.addButton('bgImage', {
                        text: 'Background Image',
                        icon: 'image',
                        context: 'bgImage',
                        onAction: function(_) {
                            $('.tinymceBgmedia .imgbtn').trigger('click');
                            $('#tinymce_item').val(editor.id);
                        }
                    });
                }
            }); */
            $('#tinyarea' + section + newid + ':not(.textarea)').summernote({
                placeholder: 'Hello ..!'
                , tabsize: 2
                , height: 200
                , toolbar: [
                    ['style', ['style']]
                    , ['font', ['bold', 'underline', 'clear']]
                    , ['color', ['color']]
                    , ['para', ['ul', 'ol', 'paragraph']]
                    , ['table', ['table']]
                    , ['insert', ['link', 'video']]
                    , ['view', ['fullscreen', 'codeview', 'help']]
                    , ['mybutton', ['replaceImage', 'design', 'bgImage']] // custom button
                ]
                , callbacks: {
                    onInit: function() {
                        var $editor = $(this).next('.note-editor').find('.note-editable');

                        $editor.on('click', 'img', function(e) {
                            e.preventDefault();

                            // Remove "selected" from all images in this editor
                            $editor.find('img').removeClass('selected');

                            // Add "selected" to clicked image
                            $(this).addClass('selected');
                        });
                    }

                }
                , buttons: {
                    replaceImage: function(context) {
                        var root = $('#rootfolder').val();
                        var ui = $.summernote.ui;

                        var button = ui.button({
                            contents: '<i class="fas fa-image"></i>'
                            , tooltip: 'Insert/Replace Image'
                            , click: function() {
                                var editorId = $(context.layoutInfo.note).attr('id');
                                $('#tinymce_item').val(editorId);

                                // 🔹 Save current cursor/selection before opening popup
                                context.invoke('editor.saveRange');

                                // open gallery popup
                                $('.tinymcemedia .imgbtn').trigger('click');

                                // listen once for change event
                                $('body').one('change', '#tinymce.galleryinputfield', function() {
                                    var newSrc = root + '/gallery/' + $(this).val();
                                    if (!newSrc) return;

                                    // ✅ Find Summernote editor editable area
                                    var $editor = $('#' + editorId);
                                    var $editable = $editor.next('.note-editor').find(
                                        '.note-editable');

                                    // ✅ Check if any image is selected
                                    var $selectedImg = $editable.find('img.selected');

                                    if ($selectedImg.length) {
                                        // 🔹 Replace selected image src
                                        $selectedImg.attr('src', newSrc);
                                        $selectedImg.removeClass('selected');
                                    } else {
                                        // 🔹 Restore cursor and insert at correct position
                                        context.invoke('editor.restoreRange');
                                        context.invoke('editor.focus');
                                        context.invoke('editor.insertImage', newSrc);
                                    }

                                    // 🔹 Trigger change so autosave works
                                    var content = $editor.summernote('code');
                                    $editor.trigger('summernote.change', content);
                                });
                            }
                        });

                        return button.render();
                    }
                    , design: function(context) {
                        var root = $('#rootfolder').val();
                        var ui = $.summernote.ui;

                        var button = ui.button({
                            contents: '<i class="fas fa-pen"></i>'
                            , tooltip: 'Insert/Replace Image'
                            , click: function() {
                                var editorId = $(context.layoutInfo.note).attr('id');
                                $('#tinymce_item').val(editorId);

                                // 🔹 Save current cursor/selection before opening popup
                                tinyEleDesign();
                            }
                        });

                        return button.render();
                    }
                    , bgImage: function(context) {
                        var root = $('#rootfolder').val();
                        var ui = $.summernote.ui;

                        var button = ui.button({
                            contents: '<i class="fas fa-image"></i>'
                            , tooltip: 'Change Background of Nearest .bgimage Element'
                            , click: function() {
                                var editorId = $(context.layoutInfo.note).attr('id');
                                $('#tinymce_item').val(editorId);

                                // Save current cursor range
                                context.invoke('editor.saveRange');

                                // Open your gallery popup
                                $('.tinymceBgmedia .imgbtn').trigger('click');

                                // Handle once when user selects an image
                                $('body').one('change', '#tinymceBg', function() {
                                    var filename = $(this).val();
                                    if (!filename) return;

                                    var fullSrc = root + '/gallery/' + filename;

                                    // Restore the cursor range so Summernote knows where we were
                                    context.invoke('editor.restoreRange');
                                    var range = context.invoke('editor.createRange');
                                    if (!range) return;

                                    // Find nearest ancestor with .bgimage class
                                    var $editable = $('#' + editorId).next('.note-editor').find('.note-editable');
                                    var $node = $(range.sc).closest('.bgimage', $editable);

                                    if ($node.length) {
                                        // ✅ Update background of the closest .bgimage element with !important
                                        var node = $node.get(0);
                                        node.style.setProperty('background-image', 'url(' + fullSrc + ')', 'important');
                                        node.style.setProperty('background-size', 'cover', 'important');
                                        node.style.setProperty('background-position', 'center center', 'important');
                                        node.style.setProperty('background-repeat', 'no-repeat', 'important');
                                    } else {
                                        // ⚠️ No .bgimage ancestor found — optional fallback
                                        alert('No .bgimage element found near the cursor.');
                                    }

                                    // Trigger change for autosave/form binding
                                    var $editor = $('#' + editorId);
                                    var content = $editor.summernote('code');
                                    $editor.trigger('summernote.change', content);
                                });
                            }
                        });

                        return button.render();
                    }
                }
            });
            // addflatpicker();
        });

        // Sortable Code
        var fixHelperModified = function(e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();

            $helper.children().each(function(index) {
                $(this).width($originals.eq(index).width())
            });

            return $helper;
        };

        /* $(".table-sortable tbody").sortable({
            helper: fixHelperModified
        }).disableSelection(); */

        /* $(".table-sortable thead").disableSelection(); */



        // $("#add_row").trigger("click");
    });


    /* $(document).on('click', '.note-editable img', function(e) {
  e.preventDefault();
  alert('dynamic image clicked!');
  
  // Example: highlight selected image
  $('.note-editable img').removeClass('selected');
  $(this).addClass('selected');
}); */

    $('body').on('click', '.moveupbtn', function() {
        var _this = $(this);
        var previd = _this.closest('tr').prev('tr').attr('id');
        var currentid = _this.closest('tr').attr('id');
        /*var current_html = $('#' + currentid).clone();
        $('#' + currentid).remove();
        $(current_html).insertBefore('#' + previd);*/

        $('#' + currentid).insertBefore('#' + previd);

        /* $('#' + currentid + ' .tinyarea').next('div[role="application"]').remove();
        // tinymce.remove('#' + currentid + ' .tinyarea');
        $('#' + currentid + ' .tinyarea').summernote('destroy'); */

        /* setTimeout(function() {
            initTinymce('#' + currentid + ' .tinyarea');
        }, 1000); */



        $.each($('#' + previd + ' input, #' + previd + ' select, #' + previd + ' textarea'), function() {
            var name = $(this).attr('name');
            if (!name) return; // Skip if name is undefined
            var nameid = name.substr(name.length - 1);
            var newnameid = parseInt(nameid) + 1;
            var namestring = name.slice(0, -1);
            var newname = namestring + newnameid;
            $(this).attr('name', newname);
        });

        $.each($('#' + currentid + ' input, #' + currentid + ' select, #' + currentid + ' textarea')
            , function() {
                var name = $(this).attr('name');
                if (!name) return; // Skip if name is undefined
                var nameid = name.substr(name.length - 1);
                var newnameid = parseInt(nameid) - 1;
                var namestring = name.slice(0, -1);
                var newname = namestring + newnameid;
                $(this).attr('name', newname);
            });
    });
    $('body').on('click', '.movedownbtn', function() {
        var _this = $(this);
        var nextid = _this.closest('tr').next('tr').attr('id');
        var currentid = _this.closest('tr').attr('id');
        /* var current_html = $('#' + currentid).clone();
        $('#' + currentid).remove(); */
        // $(current_html).insertAfter('#' + nextid);
        $('#' + currentid).insertAfter('#' + nextid);

        /* $('#' + currentid + ' .tinyarea').next('div[role="application"]').remove();
        tinymce.remove('#' + currentid + ' .tinyarea');

        setTimeout(function() {
            initTinymce('#' + currentid + ' .tinyarea');
        }, 1000); */

        $.each($('#' + nextid + ' input, #' + nextid + ' select, #' + nextid + ' textarea'), function() {
            var name = $(this).attr('name');
            if (!name) return; // Skip if name is undefined
            var nameid = name.substr(name.length - 1);
            var newnameid = parseInt(nameid) - 1;
            var namestring = name.slice(0, -1);
            var newname = namestring + newnameid;
            $(this).attr('name', newname);
        });
        $.each($('#' + currentid + ' input, #' + currentid + ' select, #' + currentid + ' textarea')
            , function() {
                var name = $(this).attr('name');
                if (!name) return; // Skip if name is undefined
                var nameid = name.substr(name.length - 1);
                var newnameid = parseInt(nameid) + 1;
                var namestring = name.slice(0, -1);
                var newname = namestring + newnameid;
                $(this).attr('name', newname);
            });
    });

    function replaceorder(current, change, table, near = '0') {
        if (near == '0')
            currenttr = table.find('tr[data-id=' + current + ']:not(.currenttr)');
        else
            currenttr = table.find('tr[data-id=' + current + ']');
        $(currenttr).find('[name*="' + current + '"]').each(function() {
            this.name = this.name.replace(current, change);
        });
        $(currenttr).find('[id*="' + current + '"]').each(function() {
            this.id = this.id.replace(current, change);
        });
        currenttr.attr('id', currenttr.attr('id').replace(current, change));
        currenttr.attr('data-id', currenttr.attr('data-id').replace(current, change));
        // currenttr.attr('data-pointslug',currenttr.attr('data-pointslug').replace(current, change));
        currenttr.find('.orderinput').val(change);
    }

    $('body').on('change', '.orderinput', function() {
        var _this = $(this);
        var table = $(this).closest('table');
        table.find('.currenttr').removeClass('currenttr');
        _this.closest('tr').addClass('currenttr');
        var val = _this.val();
        if (!$.isNumeric(val)) {
            alert('Enter Numeric Value');
            return false;
        }
        var total = _this.closest('.card').find('.add_row').siblings('input').val();
        if (parseInt(val) <= 0 || parseInt(val) > parseInt(total)) {
            alert('Enter number between 1 and ' + total);
            return false;
        }
        var current_data_id = _this.closest('tr').attr('data-id');
        replaceorder(current_data_id, val, table, '1');
        if (parseInt(current_data_id) < parseInt(val)) {
            for (var i = parseInt(current_data_id) + 1; i <= val; i++) {
                replaceorder(i, parseInt(i) - 1, table);
            }
        } else {
            for (var i = parseInt(current_data_id) - 1; i >= val; i--) {
                replaceorder(i, parseInt(i) + 1, table);
            }
        }

    });


    function initTinymce(element) {
        /* tinymce.init({
            entity_encoding: 'raw',
            height: "500",
            forced_root_block: "",
            force_br_newlines: true,
            force_p_newlines: false,
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,
            content_css: rootfolder + "/tinymce/tinymce_custom.css",
            selector: element,
            end_container_on_empty_block: true,
            menubar: false,
            toolbar: true,
            verify_html: true,
            // content_style: "h1 { font-size: 14pt; font-family: Arial; font-weight: normal; }",
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'lists',
                'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'wordcount', 'visualblocks'
            ],
            toolbar: 'newdocumentcustom | linkcustom | previewcustom | fullscreencustom | customImageButton | customDesign | bgImage | customEleDesign | code | removeemptyline',
            valid_elements: '*[*]',
            setup: function(editor) {
                editor.on('change', function() {
                    tinymce.triggerSave();
                });
                editor.ui.registry.addButton('removeemptyline', {
                    text: 'Remove Empty Lines',
                    icon: 'new-document',
                    context: 'newdocumentcustom',
                    onAction: function(_) {
                        removeEmptyline();
                    }
                });
                editor.ui.registry.addButton('newdocumentcustom', {
                    text: 'New document',
                    icon: 'new-document',
                    context: 'newdocumentcustom',
                    onAction: function(_) {
                        tinymce.get(editor.id).execCommand('mceNewDocument', false);
                    }
                });
                editor.ui.registry.addButton('linkcustom', {
                    text: 'Link',
                    icon: 'link',
                    context: 'linkcustom',
                    onAction: function(_) {
                        tinymce.get(editor.id).execCommand('mceLink', false);
                    }
                });
                editor.ui.registry.addButton('previewcustom', {
                    text: 'Preview',
                    icon: 'preview',
                    context: 'previewcustom',
                    onAction: function(_) {
                        tinymce.get(editor.id).execCommand('mcePreview', false);
                    }
                });
                editor.ui.registry.addButton('fullscreencustom', {
                    text: 'Fullscreen',
                    icon: 'fullscreen',
                    context: 'fullscreencustom',
                    onAction: function(_) {
                        tinymce.get(editor.id).execCommand('mceFullScreen', false);
                    }
                });
                editor.ui.registry.addButton('customImageButton', {
                    text: 'Image',
                    icon: 'image',
                    context: 'customImage',
                    onAction: function(_) {
                        $('.tinymcemedia .imgbtn').trigger('click');
                        $('#tinymce_item').val(editor.id);
                    }
                });
                editor.ui.registry.addButton('customDesign', {
                    text: 'Template',
                    icon: 'template',
                    context: 'customDesigns',
                    onAction: function(_) {
                        $('#tinymce_item').val(editor.id);
                        tinyDesign();

                    }
                });
                editor.ui.registry.addButton('customEleDesign', {
                    text: 'Design',
                    icon: 'template',
                    context: 'customEleDesigns',
                    onAction: function(_) {
                        $('#tinymce_item').val(editor.id);
                        tinyEleDesign();

                    }
                });
                editor.ui.registry.addMenuItem('customCopy', {
                    text: 'Copy',
                    context: 'customCopy',
                    onAction: function(_) {
                        $('#tinymce_item').val(editor.id);
                        getElePath();

                    }
                });
                editor.ui.registry.addButton('bgImage', {
                    text: 'Background Image',
                    icon: 'image',
                    context: 'bgImage',
                    onAction: function(_) {
                        $('.tinymceBgmedia .imgbtn').trigger('click');
                        $('#tinymce_item').val(editor.id);
                    }
                });
            }
        }); */
        $(element).summernote({
            placeholder: 'Hello ..!'
            , tabsize: 2
            , height: 200
            , toolbar: [
                ['style', ['style']]
                , ['font', ['bold', 'underline', 'clear']]
                , ['color', ['color']]
                , ['para', ['ul', 'ol', 'paragraph']]
                , ['table', ['table']]
                , ['insert', ['link', 'video']]
                , ['view', ['fullscreen', 'codeview', 'help']]
                , ['mybutton', ['replaceImage', 'design', 'bgImage']] // custom button
            ]
            , callbacks: {
                onInit: function() {
                    var $editor = $(this).next('.note-editor').find('.note-editable');
                    $editor.addClass('testkannan');
                    $editor.on('click', 'img', function(e) {
                        alert('image selectetd');
                        e.preventDefault();

                        // Remove "selected" from all images in this editor
                        $editor.find('img').removeClass('selected');

                        // Add "selected" to clicked image
                        $(this).addClass('selected');
                    });
                }

            }
            , buttons: {
                replaceImage: function(context) {
                    var root = $('#rootfolder').val();
                    var ui = $.summernote.ui;

                    var button = ui.button({
                        contents: '<i class="fas fa-image"></i>'
                        , tooltip: 'Insert/Replace Image'
                        , click: function() {
                            var editorId = $(context.layoutInfo.note).attr('id');
                            $('#tinymce_item').val(editorId);

                            // 🔹 Save current cursor/selection before opening popup
                            context.invoke('editor.saveRange');

                            // open gallery popup
                            $('.tinymcemedia .imgbtn').trigger('click');

                            // listen once for change event
                            $('body').one('change', '#tinymce.galleryinputfield', function() {
                                var newSrc = root + '/gallery/' + $(this).val();
                                if (!newSrc) return;

                                // ✅ Find Summernote editor editable area
                                var $editor = $('#' + editorId);
                                var $editable = $editor.next('.note-editor').find(
                                    '.note-editable');

                                // ✅ Check if any image is selected
                                var $selectedImg = $editable.find('img.selected');

                                if ($selectedImg.length) {
                                    // 🔹 Replace selected image src
                                    $selectedImg.attr('src', newSrc);
                                    $selectedImg.removeClass('selected');
                                } else {
                                    // 🔹 Restore cursor and insert at correct position
                                    context.invoke('editor.restoreRange');
                                    context.invoke('editor.focus');
                                    context.invoke('editor.insertImage', newSrc);
                                }

                                // 🔹 Trigger change so autosave works
                                var content = $editor.summernote('code');
                                $editor.trigger('summernote.change', content);
                            });
                        }
                    });

                    return button.render();
                }
                , design: function(context) {
                    var root = $('#rootfolder').val();
                    var ui = $.summernote.ui;

                    var button = ui.button({
                        contents: '<i class="fas fa-pen"></i>'
                        , tooltip: 'Insert/Replace Image'
                        , click: function() {
                            var editorId = $(context.layoutInfo.note).attr('id');
                            $('#tinymce_item').val(editorId);

                            // 🔹 Save current cursor/selection before opening popup
                            tinyEleDesign();
                        }
                    });

                    return button.render();
                }
                , bgImage: function(context) {
                    var root = $('#rootfolder').val();
                    var ui = $.summernote.ui;

                    var button = ui.button({
                        contents: '<i class="fas fa-image"></i>'
                        , tooltip: 'Change Background of Nearest .bgimage Element'
                        , click: function() {
                            var editorId = $(context.layoutInfo.note).attr('id');
                            $('#tinymce_item').val(editorId);

                            // Save current cursor range
                            context.invoke('editor.saveRange');

                            // Open your gallery popup
                            $('.tinymceBgmedia .imgbtn').trigger('click');

                            // Handle once when user selects an image
                            $('body').one('change', '#tinymceBg', function() {
                                var filename = $(this).val();
                                if (!filename) return;

                                var fullSrc = root + '/gallery/' + filename;

                                // Restore the cursor range so Summernote knows where we were
                                context.invoke('editor.restoreRange');
                                var range = context.invoke('editor.createRange');
                                if (!range) return;

                                // Find nearest ancestor with .bgimage class
                                var $editable = $('#' + editorId).next('.note-editor').find('.note-editable');
                                var $node = $(range.sc).closest('.bgimage', $editable);

                                if ($node.length) {
                                    // ✅ Update background of the closest .bgimage element with !important
                                    var node = $node.get(0);
                                    node.style.setProperty('background-image', 'url(' + fullSrc + ')', 'important');
                                    node.style.setProperty('background-size', 'cover', 'important');
                                    node.style.setProperty('background-position', 'center center', 'important');
                                    node.style.setProperty('background-repeat', 'no-repeat', 'important');
                                } else {
                                    // ⚠️ No .bgimage ancestor found — optional fallback
                                    alert('No .bgimage element found near the cursor.');
                                }

                                // Trigger change for autosave/form binding
                                var $editor = $('#' + editorId);
                                var content = $editor.summernote('code');
                                $editor.trigger('summernote.change', content);
                            });
                        }
                    });

                    return button.render();
                }
            }
        });
    }

</script>
