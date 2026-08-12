<?php
$selectedOutputColumns = $reportConfigData['output_columns'];
$reportColumns = \App\Helpers\ReportManager::getOutputColumnsByReportType($reportConfigData['report_type']);
$dateColumns = ['last_updated', 'created_on'];



?>

@extends (\App\Helpers\GnUtils::isDonorSession() ? 'donor.layouts.main' : 'agency.layouts.main')
@section('content')
    <style>
        .table.report-table tr th {
            min-width: 115px;
        }
    </style>
    @include('common.page-header', ['pageTitle' => 'Report'])
    <div class="container form-wrapper form-last" >
        <div class="row">

            @if($models->count())
                <div class="col-sm-12">

                    <div class="dropdown" style="float: right">
                        <button class="btn btn-sm btn-accent mb-2 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Export
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                            <form method="POST" action="{{ route('export-report-csv') }}">
                                @csrf
                                <input type="hidden" name="reportConfigData" value="{{ json_encode($reportConfigData, true) }}">
                                <button type="submit" class="dropdown-item">CSV</button>
                            </form>

                        </div>

                    </div>
                </div>

                <div class="report" style="overflow-x: auto;">
                    <div class="col-md-12">
                        <table class="table table-hover report-table">
                            <thead>
                            <tr>
                                @foreach ($selectedOutputColumns as $key => $value )
                                    @foreach ($reportColumns as $rcKey => $rcVal)
                                        @if($key == $rcKey && $value != false)
                                            <th scope="col">{{$rcVal}}</th>
                                        @endif
                                    @endforeach
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($models as $model => $val)
                                <tr>

                                    @foreach($selectedOutputColumns as $cKey => $cVal)
                                        @if($cVal != false )
                                            <td>
                                                @if (in_array($cKey, $dateColumns))
                                                    {{ \App\Helpers\GnUtils::customDate($val->$cKey) }}
                                                @elseif ($cKey == 'type')
                                                    @foreach($val->types as $i => $cType)
                                                        @if($i > 0)
                                                            {{', ' .\App\Models\ContactType::getTypeName($cType->contact_type_id)}}
                                                        @else
                                                            {{\App\Models\ContactType::getTypeName($cType->contact_type_id)}}
                                                        @endif
                                                    @endforeach
                                                @elseif ($cKey == 'email')
                                        
                                                    {{-- {{ $val->$cKey['email_address'] }} --}}
                                                    {{-- {{ $val->$cKey }} --}}

                                                    {{-- Decode the JSON string into an associative array --}}
                                                    <?php $emailData = json_decode($val->$cKey, true); ?>
                                                    {{-- {{dd($emailData)}} --}}
                                                    {{-- Access the email_address property --}}
                                                    {{ $emailData['email_address'] ?? 'N/A' }}

                                                @elseif ($cKey == 'phone')
                                                    @foreach($val->phones() as $i => $cType)
                                                        {{ $cType->phone_number }}
                                                    @endforeach

                                                @elseif ($cKey == 'fund_id' || $cKey == 'target_id')

                                                    @if ($val->$cKey == 0 || $val->$cKey == '')
                                                        NA
                                                    @else    
                                                        {{ \App\Models\Fund::getFundById($val->$cKey)->name }}
                                                    @endif

                                                @elseif ($cKey == 'amount')

                                                    @if ($val->$cKey == 0 || $val->$cKey == '')
                                                        NA
                                                    @else    
                                                        {{ \App\Helpers\GnUtils::money($val->$cKey) }}
                                                    @endif

                                                @elseif ($cKey == 'gift_date')

                                                    @if ($val->$cKey == 0 || $val->$cKey == '')
                                                        NA
                                                    @else    
                                                        {{ \App\Helpers\GnUtils::customDate($val->$cKey) }}
                                                    @endif
                                                    
                                                @else

                                                    @if ($cKey == 'description')
                                                        {{ str_replace('&nbsp;', '', strip_tags($val->$cKey)) }}
                                                    @else    
                                                        {{ $val->$cKey }}
                                                    @endif
                                                @endif
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="col-md-12">
                    <span>No records</span>
                </div>
            @endif

        </div>
    </div>
@endsection
