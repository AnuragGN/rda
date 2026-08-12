<style>
    .jconfirm .jconfirm-box div.jconfirm-content-pane {
        margin-bottom: 0.5rem;
    }
</style>


@if($phone->organization_phone_id and $phone->is_primary != 'Y')
    <a href="javascript:void(0);" id="id_delete_phone" style="">Delete Phone</a>

    <form name="deleteOrgPhoneForm" id="deleteOrgPhoneForm" method="POST"
          action="{{ route('gs-org-phone-delete') }}">
        {{ csrf_field() }}
        <input type="hidden" name="organization_phone_id" value="{{$phone->getModelId()}}" />
    </form>


    <script>
        $('#id_delete_phone').on('click',function(){
            var body = $("body");

            var message = "<div style='text-align: center'>Are you sure you want to delete this phone?</div><hr class='mb-0'>";

            $.confirm({
                columnClass: 'medium',
                title: '',
                content: message,
                buttons: {
                    yes: {
                        text: 'Delete',
                        btnClass: 'btn-grey',
                        keys: ['enter', 'shift'],
                        action: function(){
                            body.css("cursor", "progress");
                            body.append('<div class="modal-backdrop fade show" style="z-index:100;"></div>');
                            document.deleteOrgPhoneForm.submit();
                        }
                    },
                    no: {
                        text: 'Cancel',
                        btnClass: 'btn-accent',
                        keys: ['enter', 'shift'],
                        action: function(){}
                    }
                }
            });
            return false;
        });

    </script>

@endif
