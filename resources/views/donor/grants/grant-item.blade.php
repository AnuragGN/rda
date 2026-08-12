@extends ('donor.layouts.main')

@section ('content')

    <div class="row">

        <div class="col-8">
            <div class="row row-page-title gift-history-header">
                <div class="col-12">
                    <div class="page-title">
                        <h3>Confirm</h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">

                    <div class="form-header">
                        <p>Please review and Confirm, or click on Edit to make any changes.</p>
                    </div>

                    <div class="grant-preview">
                        <div class="row gp-row">
                            <div class="col-4 label">From fund</div>
                            <div class="col-8 value">{{ $model->fund_id }}</div>
                        </div>
                        <div class="row gp-row">
                            <div class="col-4 label">To organization</div>
                            <div class="col-8 value"> {{ $model->organization }}</div>
                        </div>
                        <div class="row gp-row">
                            <div class="col-4 label">Amount</div>
                            <div class="col-8 value"> {{ \App\Helpers\GnUtils::money($model->amount) }}</div>
                        </div>
                        <div class="row gp-row">
                            <div class="col-4 label">Purpose</div>
                            <div class="col-8 value"> {{ $model->purpose }}</div>
                        </div>
                        <div class="row gp-row">
                            <div class="col-4 label">Note</div>
                            <div class="col-8 value"> {{ $model->note }}</div>
                        </div>
                        <div class="row gp-row">
                            <div class="col-4 label">Anonymous</div>
                            <div class="col-8 value"> {{ $model->anonymous }}</div>
                        </div>

                        {{-- {!! Form::open( ['action' => 'FundController@saveGrant', 'files' => false, 'id' => 'content-form' ]) !!}--}}
                        {!! Form::model($model, ['method' => 'POST', 'files' => false, 'route' => ['add-to-cart', 1], 'id' => 'grant-form']) !!}

                        {!!  Form::hidden('fund_id', null, []) !!}
                        {!!  Form::hidden('organization', null, []) !!}
                        {!!  Form::hidden('amount', null, []) !!}
                        {!!  Form::hidden('purpose', null, []) !!}
                        {!!  Form::hidden('note', null, []) !!}
                        {!!  Form::hidden('anonymous', null, []) !!}

                        <hr>
                        <div class="form-group row">
                            <div class="col-md-2 offset-sm-7">
                                {!! Form::submit('Edit', ['name' => 'save', 'class' => 'btn btn-secondary', 'style' => 'width: 100%']) !!}
                            </div>
                            <div class="col-md-2">
                                {!! Form::submit('Confirm', ['name' => 'save', 'class' => 'btn btn-primary', 'style' => 'width: 100%']) !!}
                            </div>
                        </div>
                    </div>
                    <br>

                    {!! Form::close() !!}

                    <br>
                    <br>
                </div>
            </div>
        </div>

    </div>



@endsection
