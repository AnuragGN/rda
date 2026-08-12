<?php
$badgeCount = \App\Models\GrantItem::countCartItems();
?>

<div class="{{ \App\Models\ClientInfo::isGNA() || \App\Models\ClientInfo::isGMF() ? '' : 'd-block d-md-none' }}">

    <div class="row">
        <div class="col-12">
            <h3 class="page-subtitle uppercase">Explore</h3>
        </div>
    </div>

    <div class="dashboard-menu">
        <div class="row">

            @if($custom->feature->CONTRIBUTION)
                <div class="col-6 col-md-3">
                    <a href="{{ route('contribute') }}" class="btn btn-menu text-accent">
                        <i class="fas fa-gift"></i>
                        <span>{{ $custom->text->MAKE_A_GIFT }}</span>
                    </a>
                </div>
            @endif

            <div class="col-6 col-md-3">
                <a href="{{ route('grant-create') }}" class="btn btn-menu text-accent">
                    <i class="fas fa-donate"></i>
                    <span>{{ $custom->text->MAKE_A_GRANT }}</span>
                </a>
            </div>

            @if(false && $custom->feature->RECENT_CONTRIBUTIONS)
                <div class="col-6 col-md-3">
                    <a href="{{ route('transactions') }}" class="btn btn-menu text-accent">
                        <i class="fas fa-exchange-alt"></i>
                        <span>{{$custom->text->RECENT_CONTRIBUTIONS}}</span>
                    </a>
                </div>
            @endif

            <div class="col-6 col-md-3">
                <a href="{{ route('my-cart') }}" class="btn btn-menu text-accent">
                    <i class="fas fa-shopping-cart"></i>
                    <span>{{ \App\Models\GrantForm::cartLabel() }} {{ $badgeCount ? ' (' . $badgeCount . ')': '' }}</span>
                </a>
            </div>

                <div class="col-6 col-md-3">
                    <a href="{{ route('charitable-catalog') }}" class="btn btn-menu text-accent">
                        <i class="fas fa-list"></i>
                        <span>{{ $custom->text->CHARITABLE_CATALOG }}</span>
                    </a>
                </div>

            <div class="hide">
                <div class="col-6 col-md-3">
                    <a href="{{route('charitable-catalog')}}" class="btn btn-menu">{{ $custom->text->CHARITABLE_CATALOG }}</a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{route('organization-matches')}}" class="btn btn-menu">Organization Matches</a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{route('project-matches')}}" class="btn btn-menu">Project Matches</a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{route('profile-interests-edit')}}" class="btn btn-menu">Interest Profile</a>
                </div>
            </div>
        </div>
    </div>

    @if(\App\Models\ClientInfo::isJCF())
        <div class="row text-center">
            <div class="col-12">
                <hr class="mb-1">
                <a href="javascript:void(0)" onclick="onLogout()">Log Out</a>
            </div>
        </div>
    @endif

</div>