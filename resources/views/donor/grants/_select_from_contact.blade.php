
<div class="offset-sm-3 col-sm-9">
    {!! Form::select('from_contact_id', $fromDonors, $fromContactId, ['id' => 'from_contact_id', 'class' => 'form-control', 'required' => 'required']) !!}
</div>

<script>
    $('#from_contact_id').change(function(){
        // alert($(this).val());
        onFromContactChanged($(this).val());
    });
</script>

