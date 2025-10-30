<!-- Button to Open the Modal -->
<button type="button" class="btn btn-success mb-0" id="updatenotesbtn">
    Save
</button>
{{-- <div class="modal" id="updatenotesModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group">
                    <textarea class="form-control" id="updatenotes" name="updatenotes"
                        placeholder="Enter Update Notes"></textarea>
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn-success pull-right" id="updatenotessubmitbtn">Submit</button>
                    <a href="{{URL()->previous()}}" class="btn btn-primary" data-dismiss="modal">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@push('scripts')
<script>
    var root = $('#rootfolder').val();
    var form = '<?php echo $form;?>';
    $('body').on('click','#updatenotesbtn', function(e){
            if(validation(e,form)==true)
                $(form).submit();
                // $('#updatenotesModal').modal('show');            
    });
    /* $('body').on('click','#updatenotessubmitbtn', function(){
            // $('.tinywrappedelement > body').unwrap();
            var $iframe = $('.tox-edit-area__iframe');

                            // var doc = document.getElementById('#headertopcontent_ifr').contentWindow.document;
                            // doc.open();
                            // doc.write('Test');
                            // doc.close(); 
                            $iframe.ready(function() {
                                $iframe.contents().find("tinywrappedelement > *:first-child").unwrap();
                                // $iframe.contents().find("body").addClass('mydiv');
                            });
            var updatenotes = $('#updatenotes').val();
            if(updatenotes!=''){                
                var result = $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: root+ '/setsession/updatenotes/'+updatenotes,
                    type: "GET",
                    datatype: "json",
                    async: false,
                    success: function(data) {
                        $(form).submit();
                    },
                    error: function(xhr, status, error) {
                    }
                });
            }
            else{
                $('#updatenotes').after("<p class='error'>Enter Update Notes</p>");
            }
    }); */
</script>
@endpush