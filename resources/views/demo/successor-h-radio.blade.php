@extends ('demo.main', ['container' => "container-registration"] )

@section ('content')

    @include('demo.tabs')

    @include('demo.progress', ['page' => 2])

    <div class="container">
        <div class="row">
            <div class="col-12">
                <p class="form-title th-color">Successor Designations</p>
                <p>The Fund Advisors need to develop a succession plan to succeed them and assume privileges on the Donor-Advised Fund. You have the following three options for successor designations:</p>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="form-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option-a">
                <label class="form-check-label" for="inlineRadio1">Individuals / Charitable Organizations</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option-b">
                <label class="form-check-label" for="inlineRadio2">Establish a Permanent Endowment</label>
            </div>
        </div>
    </div>

    <div id="successor-a" style="display: none">
        <div class="accordion mt-5" id="id-accordion-successor">

            <div class="container custom-form">

                {{-- Collapsible One --}}
                <p class="accordion-title">
                    <a href="javascript:void(0)" class="accordion-link collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <span id="arrow-right-one" class="arrow-right"></span>
                        <span id="arrow-down-one" class="arrow-down hide"></span>
                        Individuals
                    </a>
                </p>

                <div id="collapseOne" class="collapse mt-3" aria-labelledby="headingOne"
                     data-parent="#id-accordion-successor">

                    <div class="row">
                        <div class="col-lg-8">

                            <div class="form-group row">
                                <label for="id-prefix" class="col-md-3 col-form-label text-right">Prefix</label>

                                <div class="col-md-3 pl-0">
                                    <input id="id-prefix" name="prefix" type="text" class="form-control" placeholder="">
                                </div>
                            </div>

                            <div class="form-group row account-name">
                                <label for="id-fname" class="col-md-3 col-form-label text-right">Name</label>
                                <div class="col-md-3 pl-0">
                                    <input id="id-fname" name="fname" type="text" class="form-control" placeholder="first name" required="">
                                </div>

                                <div class="middle-name">
                                    <input id="id-mname" name="mname" type="text" class="form-control" placeholder="middle initial">
                                </div>

                                <div class="col-md-3 pl-0">
                                    <input id="id-lname" name="lname" type="text" class="form-control" placeholder="last name" required="">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="id-suffix" class="col-md-3 col-form-label form-multi-line-label text-right">Suffix<br>(optional)</label>
                                <div class="col-md-3 pl-0">
                                    <input id="id-suffix" name="suffix" type="text" class="form-control" placeholder="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="id-ssn" class="col-md-3 col-form-label text-right">SSN#</label>
                                <div class="col-md-3 pl-0">
                                    <input id="id-ssn" name="ssn" type="text" class="form-control" placeholder="">
                                </div>

                                <label for="id-dob" class="col-md-3 col-3-less col-form-label text-right">Date of Birth</label>
                                <div class="col-md-3 pl-0">
                                    <input id="id-dob" name="dob" type="text" class="form-control" placeholder="mm/dd/yyyy">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="id-phone" class="col-md-3 col-form-label text-right">Day Phone*</label>
                                <div class="col-md-3 pl-0">
                                    <input id="id-phone" name="phone" type="text" class="form-control" placeholder=""
                                           required="">
                                </div>

                                <label for="id-mobile" class="col-md-3 col-3-less col-form-label text-right form-multi-line-label">Evening
                                    Phone<br>(optional)</label>
                                <div class="col-md-3 pl-0">
                                    <input id="id-mobile" name="mobile" type="text" class="form-control" placeholder="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="id-email" class="col-md-3 col-form-label text-right">Email</label>
                                <div class="col-md-6 pl-0">
                                    <input id="id-email" name="email" type="email" class="form-control" placeholder=""
                                           required="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="id-relation" class="col-md-3 col-form-label form-multi-line-label text-right">Relationship
                                    with Account Advisor</label>
                                <div class="col-md-3 pl-0">
                                    <input id="id-relation" name="relation" type="text" class="form-control" placeholder="">
                                </div>

                                <label for="id-share-value" class="col-md-3 col-3-less col-form-label form-multi-line-label text-right">% of Giving Account</label>
                                <div class="col-md-3 pl-0">
                                    <input id="id-share-value" name="share-value" type="text" class="form-control"
                                           placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row ">
                        <div class="col-lg-12">

                            <div class="form-group form-group-title">
                                <span>Legal/Residential Address</span>
                            </div>

                            @include('demo.address')

                            <div class="form-btn-bar text-left">
                                <div id="id-next-btn" class="col-12 form-footer">
                                    <p class="action"><a href="/registration/contribution" class="btn btn-hga-sm btn-theme">Save</a></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <div class="container custom-form">
                {{-- Collapsible two --}}
                <p class="accordion-title">
                    <a href="javascript:void(0)" class="accordion-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <span id="arrow-right-two" class="arrow-right"></span>
                        <span id="arrow-down-two" class="arrow-down hide"></span>
                        Charitable Organizations
                    </a>
                </p>

                <div id="collapseTwo" class="collapse mt-3" aria-labelledby="headingTwo" data-parent="#id-accordion-successor">

                    <div class="form-group row">
                        <label for="id-giving" class="col-md-2 col-form-label text-right">% of Giving Account</label>
                        <div class="col-md-4 pl-0">
                            <input id="id-giving" name="giving" type="text" class="form-control" placeholder=""
                                   required="">
                        </div>

                        <label for="id-fname" class="col-md-2 col-form-label text-right">Name</label>
                        <div class="col-md-4 pl-0 account-name">
                            <div class="field-name">
                                <div class="first-name">
                                    <input id="id-fname" name="fname" type="text" class="form-control" placeholder="first name" required="">
                                </div>
                                <div class="middle-name">
                                    <input id="id-fname" name="fname" type="text" class="form-control" placeholder="mi" required="">
                                </div>
                                <div class="last-name">
                                    <input id="id-lname" name="lname" type="text" class="form-control" placeholder="last name" required="">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="form-group row">
                        <label for="id-org-name" class="col-md-2 col-form-label text-right">Organization Name</label>
                        <div class="col-md-4 pl-0">
                            <input id="id-org-name" name="org-name" type="text" class="form-control" placeholder=""
                                   required="">
                        </div>

                        <label for="id-phone" class="col-md-2 col-form-label text-right">Phone</label>
                        <div class="col-md-2 pl-0">
                            <input id="id-phone" name="phone" type="text" class="form-control" placeholder=""
                                   required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-ftpid" class="col-md-2 col-form-label text-right">Federal Tax Payer ID#</label>
                        <div class="col-md-4 pl-0">
                            <input id="id-ftpid" name="ftpid" type="text" class="form-control" placeholder=""
                                   required="">
                        </div>
                    </div>

                    <div class="form-group form-group-title">
                        <span>Charitable Organization Address</span>
                    </div>

                    @include('demo.address')

                    <div class="form-group">
                        <div class="add-more">
                            <a href="javascript:void(0);"><i class="fas fa-plus-circle"></i> Add another charitable
                                organization</a>
                        </div>
                    </div>

                    <div class="form-btn-bar text-left">
                        <div id="id-next-btn" class="col-12 form-footer">
                            <p class="action"><a href="/registration/contribution" class="btn btn-hga-sm btn-theme">Save</a></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="container mt-3">
            <div class="row">
                <div class="col-12 col-form-label">
                    <span class="">Total % of Giving Account 0%</span>
                    <br><span class="label-note">Total must equal 100%</span>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-btn-bar text-center">
                        <div id="id-next-btn" class="col-12 form-footer">
                            <p class="action"><a href="/registration/contribution" class="btn btn-hga-md btn-wide btn-theme">SAVE & NEXT</a></p>
                            <p class="completed th-color">40% COMPLETED</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



    <div id="successor-b" style="display: none">
        <div class="container custom-form">
            {{-- Collapsible three --}}
            <div class="mt-5 accordion-link">
                Establish a Permanent Endowment
                <span class="ft-normal">&nbsp;(Lasting Legacy Account - minimum $25,000)</span>
            </div>

            <div class="mt-3 form-group row">
                <label for="id-giving" class="col-md-2 col-form-label">Name of Endowment</label>
                <div class="col-md-4 pl-0">
                    <input id="id-giving" name="giving" type="text" class="form-control" placeholder=""
                           required="">
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <span class="label-note">Total % of Giving Account 100%</span>
                </div>
            </div>

            <div class="form-btn-bar text-center">
                <div id="id-next-btn" class="col-12 form-footer">
                    <p class="action"><a href="/registration/contribution" class="btn btn-hga-md btn-wide btn-theme">SAVE & NEXT</a></p>
                    <p class="completed th-color">40% COMPLETED</p>
                </div>
            </div>

        </div>
    </div>


    <div class="container mt-5">
        <p>For additional information on successors, please see our
            <a href="" class="high-link">Donor-Advised Fund Program Guide.</a></p>
    </div>


    <div class="container text-right" style="margin-top: 6rem; margin-bottom: -3rem; z-index: 99999; position: relative;">
        <div class="row">
            <div class="col-12 col-form-label">
                <a href="/registration/successor" style="margin-left: 1rem">Main</a>
                <a href="/registration/successor-h-radio" style="margin-left: 1rem">Radio</a>
                <a href="/registration/successor-select" style="margin-left: 1rem">Select</a>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            $("#id-dob").daterangepicker({
                singleDatePicker: true,
            });
        });

        function onSuccessorTypeSelection(item) {
            if (item.value === 'option-a') {
                $('#successor-a').show();
                $('#successor-b').hide();
            } else if (item.value === 'option-b') {
                $('#successor-a').hide();
                $('#successor-b').show();
            } else {
                $('#successor-a').hide();
                $('#successor-b').hide();
            }
        }
        $(function () {
            $('input[name="inlineRadioOptions"]').change( function() {
                onSuccessorTypeSelection(this);
            });
            $('select').on('change', function(e) {
                onSuccessorTypeSelection(this);
            });
        })
    </script>


@endsection
