<?php
$cls = isset($rPane) ? "col-md-12" : "col-md-6";
$cls = "col-md-10";
$readable = \App\Helpers\FileManager::getReadablePDFDocumentList();
$writable = \App\Helpers\FileManager::getWritablePDFDocumentList();
?>

<div class="row">

    @if(count($readable))
        <div class="{{$cls}}">

            @if (!\App\Models\ClientInfo::isHGA())
                <h3 class="page-subtitle mt-2">Download Documents</h3>
            @endif

            @foreach($readable as $key => $value)
                <p style="margin-bottom: 6px;"><a href="{{route('my-document-list', $key)}}">{{$value}}</a></p>
            @endforeach
            <br />
        </div>
    @endif


    @if(count($writable))
        <div class="{{$cls}}">

            <h3 class="page-subtitle mt-2">Upload Documents</h3>
            @foreach($writable as $key => $value)
                <p style="margin-bottom: 6px;"><a href="{{route('document-upload', $key)}}">{{$value}}</a></p>
            @endforeach
            <br />

        </div>
    @endif

</div>
