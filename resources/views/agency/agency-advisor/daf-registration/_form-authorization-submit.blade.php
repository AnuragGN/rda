@include('common.page-header', ['pageTitle' => 'Authorize & Submit'])
{{--@include('common.page-header', ['pageTitle' => 'Review & Submit'])--}}

<div class="container pageTop">
    <div class="form-body form-wrapper form-last custom-form">

        {{--page-authorization--}}
        <div class="row">
            <div class="col-md-8">

                <div class="form-group">
                    <p class="form-title">Authorization</p>
                </div>
                @if (\App\Models\ClientInfo::isGNA())
                    <div class="form-group row">
                        <div class="col-md-12">
                    <textarea id="id-name" name="text" rows="5" type="text" class="form-control" disabled="disabled" placeholder='Lorem ipsum'>Curabitur egestas dolor eget feugiat vulputate. Sed mollis, ipsum a egestas laoreet, nulla elit eleifend felis, auctor ornare nulla nulla a eros. Integer turpis felis, varius id convallis elementum, dapibus quis ligula. Cras cursus auctor diam nec lacinia. In hac habitasse platea dictumst. Praesent enim ante, vehicula in nulla sed, posuere laoreet justo. Morbi nec risus quis quam sollicitudin rutrum. Nulla facilisi.Aenean aliquam diam sit amet varius rhoncus. Mauris semper urna nec sollicitudin mattis. Praesent porttitor dolor augue, at vulputate justo lacinia fringilla. Aenean vel magna non ante molestie dignissim. Praesent vulputate, diam ultrices interdum viverra, nulla metus pellentesque felis, eu maximus odio purus et orci. Phasellus accumsan mollis sapien, quis placerat orci hendrerit efficitur. Nullam sodales tellus id bibendum egestas. Ut semper ornare ligula, non tincidunt ligula pharetra commodo. Nunc ut molestie sapien. Phasellus tincidunt mauris nec urna dictum, ut iaculis tortor sollicitudin. Sed vitae nisi euismod, sollicitudin neque non, ultricies massa. Morbi vitae diam ac tellus lobortis porta at sed sem. Vestibulum accumsan lacinia leo. Proin mollis viverra sodales. Integer vitae odio sapien.
                    </textarea>
                        </div>
                    </div>
                @endif
                @if(\App\Models\ClientInfo::isHGA())
                    <div class="form-group row">
                        <div class="col-md-12">
                            <ul>
                                <li>I have read the <span style="color: #0093b2;"><a href="https://www.highgroundadvisors.org/images/HGA_DAF_ProgramGuide.pdf#page=1" target="_blank" class="daf-link">Donor-Advised Fund Program Guide</a></span> and agree to the terms described therein.</li>
                                <li>I certify that the information I have provided is accurate and complete.</li>
                                <li>I understand and agree that any contribution, once accepted by HighGround, represents an irrevocable contribution and is non-refundable.</li>
                                <li>I will promptly notify HighGround in writing of any changes pertaining to this application.
                                </li>
                            </ul>
                        </div>
                    </div>
                @else
                    <div class="form-group row">
                        <div class="col-md-12">
                            <p>Please check the box below to:</p>
                            <div class="custom-list">
                                <span>—</span>
                                @if(\App\Models\ClientInfo::isPFR())
                                    <span>Acknowledge you have read our @include('pfr.registration.daf-program-guide-link') and agree with the terms and/or conditions described therein</span>
                                @else
                                    <span>Acknowledge you have read our Program Guide and agree with the terms and/or conditions described therein</span>
                                @endif
                            </div>
                            <div class="custom-list">
                                <span>—</span>
                                <span>Certify to the best of your knowledge that all information presented in connection with the completion of this application and accompanying forms is accurate</span>
                            </div>
                            <div class="custom-list">
                                <span>—</span>
                                @if(\App\Models\ClientInfo::isPFR())
                                    <span>Agree that you will promptly Notify us in writing of any changes pertaining to this form</span>
                                @else
                                    <span>Agree that you will promptly notify us in writing of any changes pertaining to this form</span>
                                @endif
                            </div>

                        </div>
                    </div>
                @endif

                {{--post-daf-authorization--}}
                <form method="post" action="{{route('post-agency-daf-authorization', $id)}}">

                    {{ csrf_field() }}

                    <div class="form-group">
                        <div class="form-check form-check-inline form-check-authorize">
                            <input class="form-check-input" type="checkbox" name="authorized" {{$authorized}} id="id_authorization">
                            <label class="form-check-label text-bold" for="id_authorization">I Authorize</label>
                        </div>
                        <p id="auth-info-error" class="field-error">Please select checkbox.</p>
                    </div>

                    <div class="form-btn-bar text-center">
                        <div id="id-next-btn" class="col-12 form-footer">
                            <p class="action">
                                <input type="submit" class="btn btn-wide btn-accent" value="SUBMIT">
                            </p>
                        </div>
                    </div>

                </form>

            </div>

            <div class="col-md-4">
                @include(\App\Models\ClientInfo::clientViewFor("daf-registration.side-pane-authorization", "agency.agency-advisor."))
            </div>

        </div>
    </div>
</div>
