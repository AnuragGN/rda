<style>
    .jconfirm .jconfirm-box div.jconfirm-content-pane {
        margin-bottom: 0.5rem;
    }
</style>


@if($address->organization_address_id and $address->is_primary != 'Y')
    <a href="javascript:void(0);" id="id_delete_address" style="">Delete Address</a>

    <form name="deleteOrgAddressForm" id="deleteOrgAddressForm" method="POST"
          action="{{ route('gs-org-address-delete') }}">
        {{ csrf_field() }}
        <input type="hidden" name="organization_address_id" value="{{$address->getModelId()}}" />
    </form>


    <script>
        $('#id_delete_address').on('click',function(){
            var body = $("body");

            var message = "<div style='text-align: center'>Are you sure you want to delete this address?</div><hr class='mb-0'>";

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
                            document.deleteOrgAddressForm.submit();
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
