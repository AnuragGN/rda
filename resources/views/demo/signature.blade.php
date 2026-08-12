@extends ('demo.main', ['container' => "custom-form"] )

@section ('content')

    @include('demo.tabs')

    <div class="container pageTop">
        <div class="form-body">

            @include('demo.progress', ['page' => 5])

            <div class="row">
                <div class="col-8">
                    <br />
                    <form id="id-form-primary">

                        <div class="form-group">
                            <p class="form-title">Authorization</p>
                        </div>


                        <div class="form-group row">
                            <div class="col-md-12">
                                <textarea id="id-name" name="text" rows="5" type="text" class="form-control" disabled="disabled" placeholder='Lorem ipsum'>Curabitur egestas dolor eget feugiat vulputate. Sed mollis, ipsum a egestas laoreet, nulla elit eleifend felis, auctor ornare nulla nulla a eros. Integer turpis felis, varius id convallis elementum, dapibus quis ligula. Cras cursus auctor diam nec lacinia. In hac habitasse platea dictumst. Praesent enim ante, vehicula in nulla sed, posuere laoreet justo. Morbi nec risus quis quam sollicitudin rutrum. Nulla facilisi.Aenean aliquam diam sit amet varius rhoncus. Mauris semper urna nec sollicitudin mattis. Praesent porttitor dolor augue, at vulputate justo lacinia fringilla. Aenean vel magna non ante molestie dignissim. Praesent vulputate, diam ultrices interdum viverra, nulla metus pellentesque felis, eu maximus odio purus et orci. Phasellus accumsan mollis sapien, quis placerat orci hendrerit efficitur. Nullam sodales tellus id bibendum egestas. Ut semper ornare ligula, non tincidunt ligula pharetra commodo. Nunc ut molestie sapien. Phasellus tincidunt mauris nec urna dictum, ut iaculis tortor sollicitudin. Sed vitae nisi euismod, sollicitudin neque non, ultricies massa. Morbi vitae diam ac tellus lobortis porta at sed sem. Vestibulum accumsan lacinia leo. Proin mollis viverra sodales. Integer vitae odio sapien.
                                </textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <p>Please check the box below to:</p>

                                <div class="custom-list">
                                    <span>—</span>
                                    <span>Acknowledge you have read the our Program Guide and agree with the terms and/or conditions described therein</span>
                                </div>
                                <div class="custom-list">
                                    <span>—</span>
                                    <span>Certify to the best of your knowledge that all information presented in connection with the completion of this application and accompanying forms is accurate</span>
                                </div>
                                <div class="custom-list">
                                    <span>—</span>
                                    <span>Agree that you will promptly notify us in writing of any changes pertaining to this form</span>
                                </div>

                            </div>
                        </div>


                        <div class="form-group">
                            <div class="form-check form-check-inline form-check-authorize">
                                <input class="form-check-input" type="checkbox" id="same-as">
                                <label class="form-check-label" for="same-as">I Authorize</label>
                            </div>
                            <p id="auth-info-error" class="field-error">Please select checkbox.</p>
                        </div>


                        <div class="form-btn-bar text-center">
                            <div id="id-next-btn" class="col-12 form-footer">
                                <p class="action"><a href="/registration/primary" class="btn btn-hga-md btn-wide btn-theme">SUBMIT</a></p>
                                <p class="completed th-color hide" style="line-height: 1.5">AFTER SUBMISSION YOU WILL RECEIVE AN EMAIL CONFIRMATION.
                                    <br>THEN UPON REVIEW OF YOUR APPLICATION, HIGHGROUND WILL SEND FINAL DOCUMENTATION FOR SIGNATURE.</p>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="col-md-4">
                    <br />
                    <br />
                    <br />
                    <div class="info-card mb-3">
                        AFTER SUBMISSION YOU WILL RECEIVE AN EMAIL CONFIRMATION.
                        THEN UPON REVIEW OF YOUR APPLICATION, WE WILL SEND FINAL DOCUMENTATION FOR SIGNATURE.
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
        });
    </script>

@endsection
