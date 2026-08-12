<?php
/** @var \App\Models\OrganizationInfo $org */

/** @var \App\Models\OrgNeedApp $programs */
$programs = $org->getProgramsData();
$board = $org->getBoardMembers();
?>

@if(\App\Helpers\GnUtils::isSeekerSession())
    <div style="text-align: left; margin-bottom: 4px; font-style: italic; font-size: 12px;">
        <span>Organization Public Information View</span>
    </div>
@endif

<div class="tabs_oval" id="id_tabs_performance">
    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link active" href="#organization" data-toggle="tab">Organization</a></li>
        @if(count($programs))
            <li class="nav-item"><a class="nav-link" href="#programs" data-toggle="tab">Programs</a></li>
        @endif
        <li class="nav-item hide"><a class="nav-link" href="#funding" data-toggle="tab">Past Funding</a></li>
        @if($board->board && strlen(trim(strip_tags($board->board))))
            <li class="nav-item"><a class="nav-link" href="#directors" data-toggle="tab">Directors</a></li>
        @endif
        <li class="nav-item hide"><a class="nav-link" href="#budget" data-toggle="tab">Budget</a></li>
    </ul>
</div>

<div class="form-wrapper form-last">
    <div class="tab-content">

        {{--.tab-pane--}}
        <div class="tab-pane active" id="organization">
            <div class="row">
                <div class="col-lg-9 col-md-12">
                    @include('org.organization-info')
                </div>
            </div>
        </div>

        {{--.tab-pane--}}
        <div class="tab-pane" id="programs">
            <div class="row">
                <div class="col-lg-9 col-md-12">
                    @include('charity.program-list-items', ['items' => $programs])
                </div>
            </div>
        </div>

        {{--.tab-pane--}}
        <div class="tab-pane" id="funding">
            <p>Past Funding</p>
        </div>

        {{--.tab-pane--}}
        <div class="tab-pane" id="directors">
            {!! $board->board !!}
        </div>

        {{--.tab-pane--}}
        <div class="tab-pane" id="budget">
            <p>Budget</p>
        </div>

    </div>
    <!-- /.tab-content -->

</div>
