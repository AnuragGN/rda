<div class="row">
    <div class="col-12">
        {!! Form::open( ['method' => 'GET', 'files' => false, 'id' => 'id-form-date', 'class' => '' ]) !!}
        <div style="display: flex; justify-content: flex-end; align-items: center;">
            <small>Select Statement Date</small>
            <input style="width: 110px; height: 31px;" id="id_date_entered" name="date"
                   type="text" class="form-control ml-2 mr-sm-2" placeholder="mm-dd-yyyy">
            <button type="submit" class="btn btn-sm btn-theme js_on_submit_filter">Go</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $(function () {
        var format = 'MM-DD-YYYY';
        $("#id_date_entered").daterangepicker({
            locale: {
                format: format
            },
            opens: 'left',
            singleDatePicker: true,
        });
    });
</script>
