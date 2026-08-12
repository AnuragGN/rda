<div class="form-group row" style="margin-bottom: 0">
    {!! Form::label('note', 'Optional Note', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
    <div class="col-sm-9">
        {!! Form::textarea('note', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Internal note for staff']) !!}
        <span class="from-footer hide">Internal note for staff</span>
    </div>
</div>
