@php
$maxSelectable = 2;
if (\App\Models\ClientInfo::isNIF()) {
    $maxSelectable = 6;
} else if (\App\Models\ClientInfo::isHGA()) {
    $maxSelectable = 2;
}
@endphp

@extends ('agency.agency-advisor.daf-registration.main')

@section ('content')

    <style>
        h5.page-subtitle {
            font-size: 16px;
            text-transform: none;
        }
    </style>
    @include('common.page-header', ['pageTitle' => \App\Models\DAF\DAFInvestment::title()])

    <div class="container pageTop">
        <div class="form-body form-wrapper form-last custom-form">

            <div class="row">
                <div class="col-md-8">

                    <div class="form-group">
                        <p class="form-title">{{\App\Models\DAF\DAFInvestment::title()}}</p>
                    </div>
                    <div class="form-group">
                        @if(\App\Models\ClientInfo::isHGA())
                            <p class="">Please select a maximum of two pools.</p>
                        @elseif(\App\Models\ClientInfo::isPFR())
                            <p class="">Please select a maximum of 2 funds. (See @include('pfr.registration.daf-program-guide-link') for Fund descriptions)</p>
                        @else
                            <p class="">Please select a maximum of {{$maxSelectable}} entries.</p>
                        @endif
                    </div>

                    <div class="form-make-grant gn-form">

                        @include('errors.form-errors')

                        <h5 class="page-subtitle mt-2" style="justify-content: start;">
                            <div class="col-md-9 th-color">{{\App\Models\DAF\DAFInvestment::poolTitle()}}</div>
                            <div class="col-md-3 th-color">Allocation %</div>
                        </h5>

                        <form method="POST" action="{{ route('post-agency-daf-investments', $id) }}" id="id_investments_form" class="pl-2 pr-2">
                        @csrf

                        <p id="id_err_max_selected" style="color: #ff0000; font-weight: bold; display: none">
                            @if(!\App\Models\ClientInfo::isHGA())
                                You can select a maximum of two investment options.
                            @endif
                        </p>

                        @foreach($allocations as $i => $alloc)
                            @if($alloc->pool_id != 'FRMM')
                                <div class="row form-group ">
                                    <div class="col-md-8">{{$alloc->pool_name}}</div>
                                    {{--<div class="col-md-2">{{$alloc->allocation}}</div>--}}

                                    <div class="col-md-3">
                                        <div class="pool-value">
                                        <input type="number" name="allocations[{{ $alloc->pool_id }}]"
                                               id="id_alloc_{{ $i }}" class="form-control" min="0"
                                               value="{{ old('allocations.' . $alloc->pool_id, $alloc->allocation) }}">
                                            <span>%</span>
                                        </div>
                                    </div>

                                </div>
                            @endif
                        @endforeach

                        @foreach($allocations as $i => $alloc)
                            @if($alloc->pool_id == 'FRMM')
                                <div class="row form-group ">
                                    <div class="col-md-8">{{$alloc->pool_name}}</div>
                                    <div class="col-md-3">
                                        <div class="pool-value">
                                            <input id="id_mmp" class="form-control" readonly name={{'allocations[' . $alloc->pool_id . ']'}}>
                                            <span>%</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        <div class="form-group row">
                            <div class="col-md-11 mb-2" style="text-align: right">
                                <span style="font-size: 100%" id="id_alloc_status"></span>
                            </div>
                        </div>
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

                        @include(\App\Models\ClientInfo::clientViewFor("daf-registration.help-footer-investments", "agency.agency-advisor."))

                    </div>
                </div>

                <div class="col-md-4">
                    @include(\App\Models\ClientInfo::clientViewFor("daf-registration.side-pane-investments", "agency.agency-advisor."))
                </div>

            </div>
        </div>
    </div>

    <script>

        $(':input[type="number"]').keypress(function(event) {
            // console.log('event',  event.which);
            if (event.which != 8 && event.which != 0 && (event.which < 48 || event.which > 57)) {
                // $("#errmsg").html("Digits Only").show().fadeOut("slow");
                return false;
            }
        }).click(function(){
            var value = parseInt($(this).val());
            if (value == 0 || value < 1) $(this).val('');
            console.log('clicked!', value);
        }).blur(function(){
            var value = parseInt($(this).val());
            if (isNaN(value)) $(this).val(0);
            // console.log('blur!', value);
        }).change(function() {
            updateAllocStatus();
        });

        $(function(){
            updateAllocStatus();
        });

        function updateAllocStatus(){

            var errorMesg = $('#id_err_max_selected');
            errorMesg.hide();

            var sum = 0;
            var selected = 0;
            for(var i=0; i<7; i++) {
                var allocationItem = $('#id_alloc_' + i);
                if (allocationItem == null) console.log("Null for ", i);
                var value = parseInt(allocationItem.val());
                if(isNaN(value)) {
                    value = 0;
                    allocationItem.val(0);
                }
                if (value > 0) selected++;
                sum += value;
            }

            var mmpValue = 100 - sum;
            var mmpItem = $('#id_mmp');
            mmpItem.val(100 - sum);

            if (sum > 100) {
                mmpItem.css({ 'color': '#ff0000', 'font-weight' : 'bold'});
            } else {
                mmpItem.css({ 'color': '#000', 'font-weight' : 'normal'});
            }

            // console.log('SUM = ', sum);
            var item = $('#id_alloc_status');
            if (sum == 100) {
                // item.css({ 'color': '#000000'});
                item.removeClass('inv-total-err');
                item.addClass('inv-total-msg');
                item.html('Total allocation = 100%');
                $(':input[type="submit"]').prop('disabled', false);
            } else if ( sum < 100 ) {
                var left = 100 - sum;
                // item.css({ 'color': '#000000'});
                item.removeClass('inv-total-err');
                item.addClass('inv-total-msg');
                item.html('Amount left to invest ' + left + '%');
                $(':input[type="submit"]').prop('disabled', true);
            } else {
                // item.css({ 'color': '#ff0000'});
                item.removeClass('inv-total-msg');
                item.addClass('inv-total-err');
                item.html('Total allocation cannot be more than 100%');
                $(':input[type="submit"]').prop('disabled', true);
            }

            // console.log('selected : ', selected);
            // var disableSubmit = false;
            // var isHga = "{{\App\Models\ClientInfo::isHGA()}}";
            // if (true || isHga) disableSubmit =  sum != 100 ? true : false;

            var maxSelectable = "{{$maxSelectable}}";

            if (selected > maxSelectable) {
                errorMesg.show();
                $(':input[type="submit"]').prop('disabled', true);
                return false;
            }

        }
    </script>

@endsection
