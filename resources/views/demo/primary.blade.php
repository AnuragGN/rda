@extends ('demo.main', ['container' => "form-page custom-form"] )

@section ('content')

    @include('demo.tabs')

    <div class="container pageTop">
        <div class="form-body">

            @include('demo.progress', ['page' => 1])

            <div class="row">
                <div class="col-8">
                    <br />
                    <form id="id-form-primary">
                        <div class="form-group">
                            <p class="form-title">Donor Account Fund Name</p>
                        </div>
                        <div class="form-group row">
                            <label for="id-name" class="col-md-3 col-form-label text-right">Fund Name</label>
                            <div class="col-md-6 pl-0">
                                <input id="id-name" name="fname" type="text" class="form-control" placeholder='e.g. "The Johnson Family Fund"' required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <br />
                            <p class="form-title">Donor Information</p>
                        </div>

                        @include('demo.person')

                        <div class="form-group row hide">
                            <label for="id-email" class="col-md-3 col-form-label text-right">Citizenship</label>
                            <div class="col-md-6 pl-0">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                    <label class="form-check-label" for="inlineRadio1">U.S. Citizen</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                    <label class="form-check-label" for="inlineRadio2">U.S. resident alien</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-group-title">
                            <span>Address</span>
                        </div>


                        @include('demo.address')

                        <div class="form-btn-bar text-left">
                            <div class="col-12 form-footer">
                                <a href="javascript:void(0);" class="form-info"><i class="fas fa-plus-circle"></i> Add Additional Fund Holder</a>
                                <p class="form-note">(add up to 3 additional account advisors who each have full and equal privileges)</p>
                                <p class="text-center"><a href="/registration/successor" class="btn btn-hga-md btn-wide btn-theme">SAVE & NEXT</a></p>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-4">
                    <br />
                    <br />
                    <br />
                    <div class="info-card mb-3">
                        Any grants made to approved charities will be accompanied by a letter that includes the Donor-Advised Fund account name, unless a specific request is made for the grant to remain anonymous.
                    </div>
                    <div class="info-card">
                        There can be up to a total of four Advisors for each account, with one individual serving as the Donor/Primary Advisor. Each Advisor will have full and equal privileges to:
                        <div class="custom-list">
                            <span>—</span>
                            <span>Recommend grants</span>
                        </div>
                        <div class="custom-list">
                            <span>—</span>
                            <span>Recommend changes to investment pool allocations</span>
                        </div>
                        <div class="custom-list">
                            <span>—</span>
                            <span>Name and remove Successors</span>
                        </div>
                        <div class="custom-list">
                            <span>—</span>
                            <span>Name and remove Advisors</span>
                        </div>
                        Advisors being added or removed must provide written consent to their addition or removal. See our
                        <a href="" class="high-link">Donor-Advised Fund Program Guide</a>
                        for additional information regarding correspondence and confirmations.
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        $(function () {
            $("#id-dob").daterangepicker({
                singleDatePicker: true,
            });

            // $(document).scrollTop(300);

            $(function(){
//                $('html, body').animate({
//                    scrollTop: $('.pageTop').offset().top
//                }, 1000);
//                return false;
            });
        });
    </script>

@endsection
