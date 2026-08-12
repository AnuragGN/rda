{{--JCF Specific--}}

<div class="form-group row">
    {!! Form::label('grant_purpose', 'Purpose (memo as you would like it to appear on grant check)', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
    <div class="col-sm-9">
        {!! Form::textarea('grant_purpose', null, ['class' => 'form-control', 'rows' => 2]) !!}
        <span class="from-footer hide">Purpose will be included on the grant check</span>
    </div>
</div>

<div class="form-group row" style="margin-bottom: 0">
    {!! Form::label('notes', 'Optional note to JCF', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
    <div class="col-sm-9">
        {!! Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 2]) !!}
        <span class="from-footer hide">Internal note for staff</span>
    </div>
</div>

<div class="form-group row">
    {!! Form::label('anonymous', 'I wish to remain anonymous', ['class' => 'col-sm-3 col-form-label text-right pr-0']) !!}
    <div class="col-sm-9">
        {!! Form::checkbox('anonymous', null, null, ['class' => 'form-control2 checkbox-1x']) !!}
    </div>
</div>
