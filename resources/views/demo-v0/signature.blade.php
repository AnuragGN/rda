@extends ('demo.main', ['container' => "container-registration"] )

@section ('content')

    @include('demo.tabs')

    <div class="container registration-form">
        <div class="row ">
            <div class="col-lg-8">

                <p class="registration-form-info">Please provide your signature below.</p>

                <p class="form-title th-color">Signature</p>

                <form id="registration-form">

                    <div class="form-group row">
                        <label for="id-mail-address-one" class="col-sm-3 col-form-label text-right">Account
                            Holder</label>
                        <label for="id-mail-address-one" class="col-sm-9 col-form-label label-bold">John Smith</label>
                    </div>

                    <div class="form-group row">
                        <label for="id-mail-address-one" class="col-sm-3 col-form-label text-right">Signature</label>

                        <div class="col-sm-9">
                            <div class="btn-group btn-signature w-100" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-switch btn-docusign active" onclick="onDocusign()">
                                    Docusign
                                </button>
                                <button type="button" class="btn btn-switch btn-signhere" onclick="onSignHere()">Sign
                                    here
                                </button>
                            </div>
                            <div class="sig-box">
                                <div class="sig-box-content docusign">
                                    <a href="/">Click to get from Docusign</a>
                                </div>
                                <div class="sig-box-content signhere hide">
                                    <span>Sign here</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Secondary --}}

                    <div class="form-group row">
                        <label for="id-mail-address-one" class="col-sm-3 col-form-label text-right">Additional Account
                            Holder</label>
                        <label for="id-mail-address-one" class="col-sm-9 col-form-label label-bold">Johnson
                            Smith</label>
                    </div>

                    <div class="form-group row">
                        <label for="id-mail-address-one" class="col-sm-3 col-form-label text-right">Signature</label>

                        <div class="col-sm-9">
                            <div class="btn-group btn-signature w-100" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-switch btn-docusign active" onclick="onDocusign()">
                                    Docusign
                                </button>
                                <button type="button" class="btn btn-switch btn-signhere" onclick="onSignHere()">Sign
                                    here
                                </button>
                            </div>
                            <div class="sig-box">
                                <div class="sig-box-content docusign">
                                    <a href="/">Click to get from Docusign</a>
                                </div>
                                <div class="sig-box-content signhere hide">
                                    <span>Sign here</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="id-date" class="col-sm-3 col-form-label text-right">DATE</label>

                        <div class="col-sm-3">
                            <input id="id-dob" name="dob" type="text" class="form-control" placeholder="mm/dd/yyyy">
                        </div>
                    </div>

                    <div class="form-btn-bar">
                        <div class="form-group row">
                            <div class="col-sm-12 text-center">
                                <div id="id-next-btn">
                                    <a href="/donor/primary" class="btn btn-hga-md btn-wide btn-theme">Save & Submit</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

            </div>

            <div class="col-lg-4">
                @include('demo.side-pane')
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
