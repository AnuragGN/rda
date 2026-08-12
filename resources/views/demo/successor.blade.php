@extends ('demo.main', ['container' => "custom-form"] )

@section ('content')

    <style>
        .nav-tabs .nav-link {
            border: 2px solid transparent;
        }
        .custom-form .nav.nav-tabs .nav-item a.nav-link {
            font-size: 1.1rem;
            font-weight: 600;
        }
        .nav-tabs {
            border-bottom: 2px solid #c4c4c4;
        }
        .nav-tabs .nav-item {
            margin-bottom: -2px;
        }
        .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
            border-color: #c4c4c4 #c4c4c4 #fff;
        }
        .custom-form .nav.nav-tabs {
            margin-bottom: 0;
            margin-top: 2rem;
        }
        .tab-content {
            padding: 1rem;
            border: 2px solid #c4c4c4;
            border-top: none;
        }
    </style>

    @include('demo.tabs')

    <div class="container pageTop">
        <div class="form-body">

            @include('demo.progress', ['page' => 2])

            <div class="row">
                <div class="col-9">
                    <br />
                    <form id="id-form-primary">

                        <div class="form-group">
                            <p class="form-title">Successor Designations</p>
                            <p class="hide">The Fund Advisors need to develop a succession plan to succeed them and assume privileges on the Donor-Advised Fund. You have the following options for successor designations:</p>
                        </div>


                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="individuals-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Individuals</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="orgs-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Charitable Organizations</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="individuals-tab">

                                <div class="form-group mt-4 hide">
                                    <p class="form-subtitle">Individuals</p>
                                </div>

                                @include('demo.person', ['primary' => false])

                                <div class="form-group row">
                                    <label for="id-relation" class="col-md-3 col-form-label form-multi-line-label text-right">Relationship
                                        with Account Advisor</label>
                                    <div class="col-md-2 pl-0">
                                        <input id="id-relation" name="relation" type="text" class="form-control" placeholder="">
                                    </div>

                                    <label for="id-share-value" class="col-md-2 col-3-less col-form-label form-multi-line-label text-right">% of Giving Account</label>
                                    <div class="col-md-2 pl-0">
                                        <input id="id-share-value" name="share-value" type="text" class="form-control"
                                               placeholder="">
                                    </div>
                                </div>

                                {{-- Individual Address --}}
                                <div class="form-group form-group-title">
                                    <span>Address</span>
                                </div>

                                @include('demo.address')

                                <div class="form-group">
                                    <div class="add-more">
                                        <a href="javascript:void(0);"><i class="fas fa-plus-circle"></i> Add another individual</a>
                                        <div><span class="label-note">You can add up to 3 individuals</span></div>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="orgs-tab">

                                {{-- Charitable Organizations --}}
                                <div class="form-group hide">
                                    <p class="form-subtitle">Charitable Organizations</p>
                                </div>


                                <div class="form-group row">
                                    <label for="id-giving" class="col-md-3 col-form-label text-right">% of Giving Account</label>
                                    <div class="col-md-6 pl-0">
                                        <input id="id-giving" name="giving" type="text" class="form-control" placeholder=""
                                               required="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="id-org-name" class="col-md-3 col-form-label text-right">Organization Name</label>
                                    <div class="col-md-6 pl-0">
                                        <input id="id-org-name" name="org-name" type="text" class="form-control" placeholder=""
                                               required="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="id-eic" class="col-md-3 col-form-label text-right">EIN</label>
                                    <div class="col-md-2 pl-0">
                                        <input id="id-ein" name="ftpid" type="text" class="form-control" placeholder=""
                                               required="">
                                    </div>

                                    <label for="id-phone" class="col-md-2 col-form-label text-right">Phone</label>
                                    <div class="col-md-2 pl-0">
                                        <input id="id-phone" name="phone" type="text" class="form-control" placeholder=""
                                               required="">
                                    </div>

                                </div>

                                <div class="form-group form-group-title">
                                    <span>Charitable Organization Address</span>
                                </div>

                                @include('demo.address')

                                <div class="form-group row">
                                    <div class="col-12 add-more">
                                        <a href="javascript:void(0);"><i class="fas fa-plus-circle"></i> Add another organization</a>
                                        <div><span class="label-note">You can add up to 3 charitable organizations</span></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-form-label">
                                <span class="">Total % of Giving Account 0%</span>
                                <br><span class="label-note">Total must equal 100%</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12">
                                <div class="form-btn-bar text-center">
                                    <div id="id-next-btn" class="col-12 form-footer">
                                        <p class="action"><a href="/registration/contribution" class="btn btn-hga-md btn-wide btn-theme">SAVE & NEXT</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            $("#id-dob").daterangepicker({
                singleDatePicker: true,
            });
        });
    </script>

@endsection
