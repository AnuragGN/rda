<!-- Institutional Client Widget -->

<div class="dashboard-section" id="institutional-clients-widget">
    <div class="chart-box mt-2">
        <div class="title"> Institutional Clients </div>
        <div class="scrollable-content" style="max-height: 350px;!important;">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    
                                    <th width="50%">Sponsor</th>
                                    <th width="15%">Total AUM</th>
                                    <th width="15%">Pending Gifts</th>
                                    <th width="15%">Pending Grants</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($charities as $charity)
                                    <tr>
                                        {{-- <td>{{ $charity['name'] }}</td> --}}
                                        <td>
                                            @php
                                                // Define the logo path variable
                                                $logoPath = 'ma/uploads/logos/' . $charity['id'] . '.jpeg';
                                                $fullLogoPath = public_path($logoPath);
                                            @endphp
                                            
                                            @if (file_exists($fullLogoPath))
                                                <img src="{{ asset($logoPath) }}" alt="{{ $charity['name'] }}" style="width: 50%; height: 50px; ">
                                            @else
                                                {{ $charity['name'] }}
                                            @endif
                                        </td>
                                        <td>${{ number_format($charity['total_balance'] ?? 0, 2) }}
                                        </td>
                                        <td></td>
                                        <td>${{ number_format($charity['pending_grants_balance'] ?? 0, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
