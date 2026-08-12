
<form method="POST" action="{{ route('post-daf-successors-individuals', $id) }}" id="daf-successors-individuals-{{ $individual->key }}">
    @csrf

    <input type="hidden" name="key" value="{{ $individual->key }}">
    <input type="hidden" name="isNew" value="{{ $individual->isNew }}">
    <input type="hidden" name="contact_id" value="{{ $individual->contact_id }}">

    @include('donor.registration.person', ['model' => $individual])


    <div class="form-group row">

        <label for="id_share_value" class="col-md-3 col-form-label form-multi-line-label text-right pr-0">% of Giving Account</label>
        <div class="col-md-3">
            <input type="number" name="share_value" id="id_share_value" class="form-control" value="{{ old('share_value', $individual->share_value) }}" onchange="handleChange(this);" required>
        </div>

    </div>
{{-- Individual Address --}}
{{--<div class="form-group form-group-title">--}}
{{--<span>Address</span>--}}
{{--</div>--}}

@include('donor.registration.address', ['model' => $individual])

<div class="form-btn-bar">
    <div class="col-md-12 form-footer">
        <div class="row">
            <p class="offset-md-3 col-md-3">
                <button type="submit" name="save" id="id_save_btn" class="btn btn-wide btn-accent w100">SAVE</button>
            </p>
            <p class="col-md-3">
                <button type="submit" name="save_next" class="btn btn-accent w100">SAVE & NEXT</button>
            </p>
        </div>
    </div>
</div>

</form>
@if(!$individual->isNew)
    <div class="offset-md-9 col-md-3 text-right mb-1">
        <a href="javascript:void(0);" class="delete-individual" id="id_delete_individual-{{$individual->contact_id}}" onclick="deleteIndividual(this)" data="{{$individual->contact_id}}" style="">Delete</a>
    </div>
@endif


<script>
    function handleChange(input) {
        if (input.value < 1) {
            input.value = 1;
            alert('Minimum 1 allowed');
        }
        if (input.value > 100){
            input.value = 100;
            alert('Maximum 100 allowed');
        }
    }
</script>
<script>
    function deleteIndividual(e) {
        var id = $(e).attr('data');
        var body = $("body");

        var message = "<div style='text-align: center'>Are you sure you want to delete this Individual?</div><hr class='mb-0'>";

        $.confirm({
            columnClass: 'medium',
            title: '',
            content: message,
            buttons: {
                no: {
                    text: 'Cancel',
                    btnClass: 'btn-light',
                    keys: ['enter', 'shift'],
                    action: function () {
                    }
                },
                yes: {
                    text: 'Delete',
                    btnClass: 'btn-accent',
                    keys: ['enter', 'shift'],
                    action: function () {
                        body.css("cursor", "progress");
                        body.append('<div class="modal-backdrop fade show" style="z-index:100;"></div>');
                        window.location.href = "{{route("delete-successors-individual", $id)}}?contact_id="+id;

                    }
                }
            }
        });
        return false;
    };

</script>



