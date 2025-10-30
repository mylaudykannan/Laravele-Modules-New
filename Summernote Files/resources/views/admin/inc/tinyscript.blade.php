@push('contentpush')
<!-- Gallery -->
<div class="tinymcemedia hidden">
    <input type="text" id="tinymce_item">
    @include('Gallery::admin.inc.mediainput', ['name' => 'tinymce'])
</div>
<div class="tinymceBgmedia hidden">
    <input type="text" id="tinymce_item">
    @include('Gallery::admin.inc.mediainput', ['name' => 'tinymceBg'])
</div>



<!-- Designs Modal -->
<div class="modal" id="designModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<!-- Element Path Modal -->
<div class="modal" id="elepathModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>
@endpush
@push('scripts')
{{-- <script src="https://cdn.tiny.cloud/1/ku26yeyqf2gbwbg4gwvouxnov9r9w3eaqgqtbs2fj3gaolmx/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" integrity="sha256-KzZiKy0DWYsnwMF+X1DvQngQ2/FxF7MF3Ff72XcpuPs=" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    var rootfolder = '<?php echo URL(' / '); ?>';
    /* $('#summernote').summernote({
                placeholder: 'Hello ..!',
                tabsize: 2,
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                    ['mybutton', ['replaceImage','design']] // custom button
                ],
                callbacks: {
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

                },
                buttons: {
                    replaceImage: function(context) {
                        var root = $('#rootfolder').val();
                        var ui = $.summernote.ui;

                        var button = ui.button({
                            contents: '<i class="fas fa-image"></i>',
                            tooltip: 'Insert/Replace Image',
                            click: function() {
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
                    },
                    design: function(context) {
                        var root = $('#rootfolder').val();
                        var ui = $.summernote.ui;

                        var button = ui.button({
                            contents: '<i class="fas fa-pen"></i>',
                            tooltip: 'Insert/Replace Image',
                            click: function() {
                                var editorId = $(context.layoutInfo.note).attr('id');
                                $('#tinymce_item').val(editorId);

                                // 🔹 Save current cursor/selection before opening popup
                                tinyEleDesign();
                            }
                        });

                        return button.render();
                    }
                }
            }); */
    $('.tinyarea').summernote({
        iframe: true
        , placeholder: 'Hello ..!'
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
        selector: '.tinyarea:not(.textarea)',
        end_container_on_empty_block: true,
        menubar: false,
        toolbar: true,
        verify_html: true,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'lists',
            'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'wordcount', 'visualblocks'
        ],
        toolbar: 'newdocumentcustom | linkcustom | previewcustom | fullscreencustom | customImageButton | bgImage | customEleDesign | code | removeemptyline',
        valid_elements: '*[*]',
        setup: function(editor) {
            editor.on('NodeChange', function(e) {
                var images = editor.getDoc().getElementsByTagName('img');
                for (var i = 0; i < images.length; i++) {
                    images[i].onerror = function() {
                        this.classList.add('image-error');
                        this.src =
                            'https://placehold.co/600x400'; // Optionally, set a placeholder image or keep it blank
                    };
                }
            });
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
            editor.on('init', function() {
                const scripts = [
                    'http://127.0.0.1:8000/frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js',
                    'http://127.0.0.1:8000/frontend/assets/vendor/php-email-form/validate.js',
                    'http://127.0.0.1:8000/frontend/assets/vendor/aos/aos.js',
                    'http://127.0.0.1:8000/frontend/assets/vendor/glightbox/js/glightbox.min.js',
                    'http://127.0.0.1:8000/frontend/assets/vendor/swiper/swiper-bundle.min.js',
                    'http://127.0.0.1:8000/frontend/assets/vendor/purecounter/purecounter_vanilla.js',
                    'http://127.0.0.1:8000/frontend/assets/js/main.js'
                ];

                scripts.forEach(src => {
                    let script = document.createElement('script');
                    script.src = src;
                    script.onload = () => console.log(`Loaded: ${src}`);
                    editor.getDoc().head.appendChild(script);
                });
            });
        }
    }); */
    $('body').on('click', '.designblock', function() {
        var design = $(this).html();
        var editor = $('#tinymce_item').val();
        $('#' + editor).summernote('code', design);
        $('.modal').modal('hide');
    });
    $('body').on('click', '.designeleblock', function() {
        // var _this = $(this);
        var design = $(this).html();
        var editor = $('#tinymce_item').val();
        tinymce.get(editor).setContent('');
        tinymce.get(editor).execCommand('mceInsertContent', false, design);
        $('.modal').modal('hide');
    });
    $('body').on('click', '.designselect', function() {
        $(this).closest('.card-header').next('.designcard').find('.designblock').trigger('click');
    });
    $('body').on('click', '.designeleselect', function() {
        $(this).closest('.card-header').next('.designcard').find('.designeleblock').trigger('click');
    });
    $('body').on('change', '#tinymce', function() {
        var _this = $(this);
        setTimeout(function() {
            var src = _this.closest('.imageselect').find('img').attr('src');
            var editor = $('#tinymce_item').val();
            if (tinymce.activeEditor.selection.getNode().nodeName == 'IMG') {
                tinymce.activeEditor.selection.getNode().setAttribute("src", src);
                tinymce.activeEditor.selection.getNode().setAttribute("data-mce-src", rootfolder + src);
            } else
                tinymce.get(editor).execCommand('mceInsertContent', false
                    , '<img height="auto" width="auto" src="' + src + '"/>');
            tinymce.activeEditor.fire('change');
        }, 1000);
        $('#designModal').modal('hide');
    });
    /*  $('body').on('change', '#tinymceBg', function() {
         alert(123);
         var _this = $(this);
         setTimeout(function() {
             var src = _this.closest('.imageselect').find('img').attr('src');
             var editor = $('#tinymce_item').val();
             var bgnode = tinymce.activeEditor.selection.getNode().closest('.bgimage');

             tinymce.activeEditor.dom.setStyles(bgnode, {
                 'background-position': 'center'
                 , 'background-repeat': 'no-repeat'
                 , '-webkit-background-size': 'contain'
                 , '-moz-background-size': 'cover'
                 , '-o-background-size': 'cover'
                 , 'background-size': 'cover'
                 , 'background-image': 'url("' + src + '")'
             });




         }, 1000);
         $('#designModal').modal('hide');
     }); */

    function tinyDesign() {
        var editor = $('#tinymce_item').val();
        var allowed = $('#' + editor).attr('data-allowed');
        var search = $('#templatesearch [name="search"]').val();
        var category = $('#templatesearch [name="category"]').val();
        var result = $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , data: {
                'allowed': allowed
                , 'search': search
                , 'category': category
            }
            , url: rootfolder + '/admin/design/load'
            , type: "GET"
            , datatype: "html"
            , success: function(data) {
                $('#designModal .modal-body').html(data);
                $('#designModal').modal('show');
            }
            , error: function(xhr, status, error) {

            }
        });
        // tinymce.get(editor).execCommand('mceInsertContent', false, '<h2>Custom Design</h2>');
    }

    function tinyEleDesign() {
        var editor = $('#tinymce_item').val();
        var allowed = $('#' + editor).attr('data-ele-allowed');
        var search = $('#designsearch [name="search"]').val();
        var category = $('#designsearch [name="category"]').val();
        var result = $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , data: {
                'allowed': allowed
                , 'search': search
                , 'category': category
            }
            , url: rootfolder + '/admin/design/load'
            , type: "GET"
            , datatype: "html"
            , success: function(data) {
                $('#designModal .modal-body').html(data);
                $('#designModal').modal('show');
            }
            , error: function(xhr, status, error) {

            }
        });
        // tinymce.get(editor).execCommand('mceInsertContent', false, '<h2>Custom Design</h2>');
    }

    function getElePath() {
        var editor = $('#tinymce_item').val();
        var html = $('#' + editor).next('.tox-tinymce').find(
            '.tox-statusbar .tox-statusbar__text-container > .tox-statusbar__path').text();
        var arrayhtml = html.split('»');
        arrayhtml = arrayhtml.filter(function(v) {
            return v !== ''
        });
        var arraylength = arrayhtml.length;
        pathhtml = '';
        for (var i = 0; i < arraylength; ++i) {
            var currentelementclass = '';
            if (i == (arraylength - 1))
                currentelementclass = 'current';
            if (arrayhtml[i] != '')
                pathhtml = pathhtml + '<div class="pathborder ' + currentelementclass + '" data-i="' + i + '">' +
                arrayhtml[i];
        }
        for (var i = 0; i < arraylength; ++i) {
            if (arrayhtml[i] != '')
                pathhtml = pathhtml + '</div>';
        }
        $('#elepathModal').modal('show');
        $('#elepathModal .modal-body').html(pathhtml);
    }

    function removeEmptyline() {
        //to remove whitespace empty lines
        var editor = tinymce.activeEditor;
        var content = editor.getContent();
        content = content.replace(/^(?=\n)$|^\s*|\s*$|\n\n+/gm, "");
        content = content.replace(/<[^/>][^>]*><\/[^>]+>/gim, "");
        // content = content.replace(/<[S]+></[S]+>/gim, "");
        editor.setContent(content);
    }

    var clickcount = 0;
    $('body').on('click', "#elepathModal *", function(e) {
        clickcount = clickcount + 1;
        var _this = $(this);
        if ($(e.target).closest('.pathborder').not(this).length) {
            _this.removeClass('selected');
            _this.css('background', '#fff');
        } else {
            _this.find('.pathborder').removeClass('selected');
            _this.find('.pathborder').css('background', '#fff');
            _this.css('background', '#ccc');
            _this.addClass('selected');

            setTimeout(function() {
                var selected_index = $('.pathborder.selected').attr('data-i');
                var current_index = $('.pathborder.current').attr('data-i');
                var index_dif = parseInt(current_index) - parseInt(selected_index);
                var node = tinyMCE.activeEditor.selection.getNode();
                // var parent = node.parentNode;
                for (let i = 0; i < index_dif; i++) {
                    node = node.parentNode;
                }

                tinyMCE.activeEditor.dom.addClass(node, 'tinyselected');
                /* setTimeout(function() {}, 3000); */

                removenode = node;
                var checkExist = setInterval(function() {
                    if (removenode.parentNode.getAttribute('id') == 'tinymce') {
                        removenode = node;
                        clearInterval(checkExist);
                    } else {
                        // console.log(removenode.parentNode.getAttribute('id'));
                        tinyMCE.activeEditor.dom.removeClass(removenode.parentNode
                            , 'tinyselected');
                        removenode = removenode.parentNode;
                    }
                }, 100);
                /* var parentnodes = tinyMCE.activeEditor.selection.getNode('.tinyselected');
                console.log(parentnodes);
                if(parentnodes!=null)
                    tinyMCE.activeEditor.selection.removeClass(tinyMCE.activeEditor.selection.getNode(), 'tinyselected'); */

                // console.log(clickcount%2);
            }, 1000);
        }

    });

    // tinytemplate search
    $('body').on('click', '#templatesearch .submitbtn', function(e) {
        e.preventDefault();
        tinyDesign();
    });
    //tinydesign search
    $('body').on('click', '#designsearch .submitbtn', function(e) {
        e.preventDefault();
        tinyEleDesign();
    });

</script>
<style>
    .note-editable * {
        all: revert !important;
    }

    .note-editable img {
        /* important for summernote image function */
        flex: 1 0 0% !important;
        position: relative !important;
        max-width: 100% !important;
    }

</style>
<link rel="stylesheet" href="{{ asset('summernote/bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('summernote/frontend.css') }}">
@endpush
