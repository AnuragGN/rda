@extends('agency.layouts.main')
@section('content')
@include('common.page-header', ['pageTitle' => 'Clients', 'hcXlWidth' => 12])
<style>
    .fund-advisors .advisor-name {
        font-weight:600;
        font-size: 110%;
    }
    .fund-advisors label {
        font-weight: 600!important;
        font-size: 90%;
        margin: 0;
        color: #646464;
    }
</style>
    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-lg-12 col-r-15 fund-advisors">
                        <div class="row">
                            <div class="col-xl-3 col-md-3 gn-form">
                                <select name="fund_id" id="fund_id" class="form-control" onchange="getFundClientList()">
                                    <option value="0"{{ !request('fund_id') ? ' selected' : '' }}>All Fund</option>
                                    @foreach ($contactFunds as $fund => $val)
                                        <option value="{{ $fund }}"{{ request('fund_id') == $fund ? ' selected' : '' }}>{{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            @forelse($items as $i => $client)
                               
                                <div class="col-sm-6">
                                    <div class="gn-card card-fund-item gn-shadow">
                                        <span class="advisor-name">{{ $client['contact_id'] ? \App\Models\Contact::getByContactId($client['contact_id'])->first_name . ' ' . \App\Models\Contact::getByContactId($client['contact_id'])->last_name : 'N/A' }}</span>
                                        <br><label>Email:</label><span> {{ $client['contact_id'] ? \App\Models\Contact::getByContactId($client['contact_id'])->email_address : 'N/A' }}</span>
                                        
                                        <br><label>Last Logged In:</label> 
                                        <span>
                                            {{ \App\Helpers\GnUtils::customDate(optional(\App\Models\LogActivity::getClientLastLogin($client['contact_id']))->created_on) ?? 'NA' }}
                                       
                                        </span>
                                        <br><a class="btn btn-theme btn-sm" href="{{ route('agency-client-detail', ['id' => $client['contact_id']]) }}" style="float: right;">View Profile</a><br>
                                    </div>
                                </div>
                            @empty
                                @include("utils.data-not-found", [])
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<script>
    function getFundClientList() {
        var fund_id = document.getElementById('fund_id').value;
        var url = new URL(window.location.href);

        if (fund_id === '0' || fund_id === '') {
            url.searchParams.delete('fund_id');
        } else {
            url.searchParams.set('fund_id', fund_id);
        }

        window.location.href = url.pathname + url.search;
    }
</script>
@endsection

