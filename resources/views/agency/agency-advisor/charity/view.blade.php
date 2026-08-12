
@extends ('agency.layouts.main')
@section ('content')
    @include('common.page-header', ['pageTitle' => 'Charity'])
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-r-15 org-view">
                    <div class="form-wrapper form-last">
                        <div class="tab-content">
                            {{--.tab-pane--}}
                            <div class="tab-pane active" id="organization">
                                <div class="row">
                                    <div class="col-lg-9 col-md-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="page-subtitle mt-2">
                                                    <h2>{{ $charity->name }}</h2>
                                                    <a>
                                                        <select onchange="getCharity(this.value);" id="charity_id" class="form-control" name="charity_id">
                                                            @foreach($charities as $charityKey => $charityVal)
                                                                <option value="{{ $charityVal['id'] }}" {{ $charity->id == $charityVal->id ? 'selected' : '' }}>
                                                                    {{ $charityVal->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 image-header">
                                                <p class="address">
                                                    Email: {{$charity->email}}<br>
                                                    Phone: {{$charity->phone}}<br>
                                                    Address: {{$charity->address}}<br>
                                                    Website: {{$charity->website}}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="page-subtitle">Overview</h4>
                                            </div>
                                        </div>
                                        {!! $charity['description'] !!}

                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="page-subtitle">Mission</h4>
                                            </div>
                                        </div>
                                        {!! $charity['mission'] !!}
                                      

                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="page-subtitle">History</h4>
                                            </div>
                                        </div>
                                        {!! $charity['history'] !!}

                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="page-subtitle">Funds</h4>
                                            </div>
                                        </div>
                                        @foreach($charity['funds'] as $fundkey => $fundval)
                                            <div class="fund-pool">
                                                <a class="pool-kv js_toggle_pool_values" title="">
                                                    <span class="name">{{ $fundval['name'] }}</span>
                                                    <span class="amount"> ${{ $fundval['balance'] }}</span>
                                                </a>
                                                <a href="{{ route('agency-fund', [$fundval['fund_id']]) }}"><small><u><i>Fund Overview</i></u></small></a>
                                                &nbsp;
                                                <a href="{{ route('agency-grant-history', [$fundval['fund_id']]) }}"><small><u><i>Disbursement History</i></u></small></a>
                                                &nbsp;
                                                <a href="{{ route('agency-gift-history', [$fundval['fund_id']]) }}"><small><u><i>Contribution History</i></u></small></a>

                                                &nbsp;
                                                <a href="{{ route('agency-charity-fund-client', ['id' => $charity['id'] , 'fund_id' => $fundval['fund_id']]) }}"><small><u><i>Clients</i></u></small></a>

                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<script>
function getCharity(charity_id){

    window.location.href = '/m/agency/charity/'+charity_id;
}
</script>
@endsection