
@extends ('agency.layouts.main')
@section ('content')
    @include('common.page-header', ['pageTitle' => $charity['name'].' Funds'])
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
                                                    <h2>{{ $fund['name'] }}
                                                    </h2>
                                                    <a>
                                                        <select onchange="getClientByFund(this.value);" id="fund_id" class="form-control" name="fund_id">
                                                            @foreach($funds as $fundKey => $fundVal)
                                                                <option value="{{ $fundVal['fund_id'] }}" {{ $fund['fund_id'] == $fundVal['fund_id'] ? 'selected' : '' }}>
                                                                    {{ $fundVal['name'] }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @foreach($clients as $client)
                                            <div class="fund-pool">
                                                <a class="pool-kv js_toggle_pool_values" title="">
                                                    <span class="name">{{ $client->first_name }} {{ $client->last_name }}</span>
                                                </a>
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
function getClientByFund(fund_id){

    var charity_id = '{{ $charity['id'] }}';
    // console.log(charity_id);
   
    window.location.href = '/m/agency/charity/'+charity_id+'/'+fund_id;
}
</script>
@endsection