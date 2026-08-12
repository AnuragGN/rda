@extends ('donor.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Recurring Grants Recommendations'])

    <div class="container">
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-xl-9">

                    <div class="row">
                        {!! Form::label('fund', 'Funds', ['class' => 'col-sm-2 col-form-label text-right pr-0']) !!}
                        <div class="col-sm-4 pb-1">
                            {!! Form::select('fund_id', $funds, $selectedFund, ['id' => 'id_fund_selector', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6 text-right">
                            <a href="{{ route('grant-create') }}" class="btn btn-accent btn-sm">
                                Make Another Grant
                            </a>
                        </div>
                    </div>

                    <h1 class="page-title w100 mt-2"></h1>

                    @if(\App\Models\ClientInfo::isCCT())
                        <p>To modify an existing recurring grant, such as amount or frequency, please contact your Philanthropic Advisor</p>
                    @endif

                    @forelse($models as $model)
                        @include('donor.grants.recurring-grant-item', compact('model'))
                    @empty
                        @include("utils.data-not-found", [])
                    @endforelse

                    @if($total > 0)
                        <div class="row">
                            <div class="col-12">
                                <div class="cart-grant-footer">
                                    <span class="text-primary-dark">Total:
                                        <span class="fw700 text-accent">{{\App\Helpers\GnUtils::money($total)}}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                </div>
            </div>

        </div>
    </div>

    <script>
        $('#id_fund_selector').change(function(){
            var fundId= $(this).val();
            window.location.href = '/m/recurring-grants/' + fundId;
        });
    </script>

@endsection
