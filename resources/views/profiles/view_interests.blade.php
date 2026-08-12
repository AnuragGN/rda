<?php
$titleInfo = null;
if (\App\Models\ClientInfo::isJCF() || \App\Models\ClientInfo::isGNA()) {
    $titleInfo = 'Share your philanthropy interests with us by completing your Interest Profile below';
}
?>

@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), \App\Helpers\GnUtils::isDonorSession() ? ['container' => 'none'] : [])

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Interest Profile', 'titleInfo' => $titleInfo])

    <section class="content">
        <div class="container">
            <div class="form-wrapper2 form-last2">
                <div class="row profile-view">
                    <div class="col-xl-9">

                        @if(\App\Helpers\GnUtils::isDonorSession())
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card gn-shadow">
                                        <div class="header">
                                            <div>Selected Interests</div>
                                            <div><a href="{{route('profile-interests-edit')}}">Edit</a></div>
                                        </div>
                                        <div class="body" id="id_interests_view">
                                            @include('profiles._view_interests')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection
