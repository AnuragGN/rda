
@extends ('agency.layouts.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Notifications', 'hcXlWidth' => '12'])

    <div class="container">
        <div class="form-wrapper form-last">

            <div class="row">
                <div class="col-xl-12 col-r-15">
                    <div class="text-right mb-2">
                        <a href="{{route('agency-send-manual-notification')}}"
                           class="btn btn-accent btn-sm mr-2 ml-2">
                            Send Notification</a>
                    </div>
                    <table class="table table-hover table-sm contact-table" id="myTable">
                        <thead>   
                            <tr>
                                <th>#</th>
                                <th style="text-align: left !important;">Notification</th>
                                <th style="text-align: left !important;">Sent By</th>
                                <th style="text-align: left !important;">Sent On</th>
                                <th style="text-align: left !important;">Notification Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notifications as $i => $notification) 
                                @php
                                    $read_user_ids = $notification->read_user_ids;
                                    $read_user_ids_arr = explode(',', $read_user_ids);

                                    $readAtDate = '';
                                    if(in_array($contact_id, $read_user_ids_arr))
                                    {
                                        $read_at_arr = json_decode($notification->read_at);
                                        
                                        foreach ($read_at_arr as $item) {
                                            if ($item->contact_id == $contact_id) {
                                                $readAtDate = $item->read_at;
                                                break;
                                            }
                                        }
                                    } 
                                @endphp    
                                <tr>
                                     <td>{{ $notifications->firstItem() + $loop->index }}</td>
                                    <td style="text-align: left !important;">{{ $notification->notification }}</td>
                                    <td style="text-align: left !important;">{{ $notification->sender_first_name }} {{ $notification->sender_last_name }}</td>
                                    
                                    <td style="text-align: left !important;">{{ \App\Helpers\GnUtils::customDate($notification->created_at) }}</td>

                                    <td style="text-align: left !important;">

                                        @if($contact_id == $notification->from)
                                            --
                                        @else
                                            @if(in_array($contact_id, $read_user_ids_arr))
                                                <small>Acknowledged  <br>{{ \Carbon\Carbon::parse($readAtDate)->format('m-d-Y H:i') }}</small>
                                            @else
                                                <a title="Mark As Read" style="cursor: pointer;color:red;" 
                                                onclick="markAsRead({{ $notification->id }},'',1);"><small style="font-weight: bold;">Acknowledge</small></a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>    
                            @empty
                                @include("utils.data-not-found", [])
                            @endforelse
                        </tbody>   
                    </table>
                    {{-- ✅ ORIGINAL CUSTOM PAGINATION RESTORED --}}
                    @if ($notifications->hasPages())
                        <div class="d-flex justify-content-end mt-3">
                            <ul class="pagination mb-0">

                                <li class="page-item {{ $notifications->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link"
                                    href="{{ $notifications->previousPageUrl() }}">«</a>
                                </li>

                                @foreach ($notifications->getUrlRange(1, $notifications->lastPage()) as $page => $url)
                                    <li class="page-item {{ $page == $notifications->currentPage() ? 'active' : '' }}">
                                        <a class="page-link"
                                        href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                <li class="page-item {{ $notifications->hasMorePages() ? '' : 'disabled' }}">
                                    <a class="page-link"
                                    href="{{ $notifications->nextPageUrl() }}">»</a>
                                </li>

                            </ul>
                        </div>
                    @endif
                    <br>
                </div>  
            </div>
        </div>
    </div>
@endsection
