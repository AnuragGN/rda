@extends ('donor.layouts.main' )

@section ('content')

    @include('common.page-header', ['pageTitle' => $custom->text->RECENT_CONTRIBUTIONS])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-xl-10">

                        <div class="d-none d-md-block">
                            <table  id="transactions" class="w100">
                                <tbody>
                                @if (count($models))
                                    <tr>
                                        <th style="text-align: center">Date</th>
                                        <th style="text-align: center">From</th>
                                        <th style="text-align: center">To</th>
                                        <th style="text-align: center">Transaction Id</th>
                                        <th style="text-align: center">Reference Id<sup>#</sup></th>
                                        <th style="text-align: right">Amount</th>
                                        <th style="text-align: center">Status<sup>*</sup></th>
                                    </tr>
                                @endif
                                @forelse($models as $i => $model)
                                    <tr>
                                        <td>{{ \App\Helpers\GnUtils::customDate($model->transaction_date) }}</td>
                                        <td style="text-align: center">{{$model->account_type}} {{$model->account_number}}</td>
                                        <td style="text-align: center">{{$model->paid_to}}</td>
                                        <td style="text-align: center">{{$model->transaction_id}}</td>
                                        <td style="text-align: center">{{$model->ref_id}}</td>
                                        <td style="text-align: right">{{ \App\Helpers\GnUtils::money($model->amount) }}</td>
                                        <td style="text-align: right">{{$model->displayStatus}}</td>
                                    </tr>
                                @empty
                                    <p><i class="fas fa-info-circle"></i> Records not found</p>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-block d-md-none">
                            @forelse($models as $i => $model)
                                <div class="m-transactions gn-shadow">
                                    <div class="two-column header">
                                        <span>{{ \App\Helpers\GnUtils::customDate($model->transaction_date) }}</span>
                                        <span>{{ \App\Helpers\GnUtils::money($model->amount) }}</span>
                                    </div>
                                    <span>From: </span>{{$model->account_type}} {{$model->account_number}}
                                    <br><span>To: </span>{{$model->paid_to}}
                                    <br><span class="">Transaction Id: </span>{{$model->transaction_id}}
                                    <br><span class="">Reference Id: </span>{{$model->ref_id}}
                                    <br><span class="">Status: </span>{{$model->displayStatus}}
                                </div>
                            @empty
                                <p><i class="fas fa-info-circle"></i> Records not found</p>
                            @endforelse
                        </div>

                        <hr>
                        {{-- &emsp; &ensp; &nbsp; --}}
                        @if (count($models))
                            <div class="tr-definitions">
                                <span class="term">*Transaction Status</span>
                                <br>&emsp; Success - The transaction has been approved.
                                <br>&emsp; Failed - This transaction has been declined, or could not be processed due to some error.
                                <br>&emsp; Error - The transaction could not be initiated/completed due to system or network error.
                            </div>
                            <br />
                            <div class="tr-definitions">
                                <span class="term">#Reference Id - </span> GiftingNetwork's Transaction Reference Id
                            </div>
                        @endif

                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection
