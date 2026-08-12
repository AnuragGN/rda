<?php
$contact = \App\Models\Contact::sessionContact();
$options = \App\Http\Controllers\CharityController::getRequestInfoOptions();
?>

<script type="text/javascript" src="/ma/javascripts/request-info.js" charset="utf-8"></script>

{{-- confirmation --}}
<div class="modal fade req-info-response-modal" id="id_req_info_response_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="response">
                <p>Your request has been submitted successfully.
                    {{--<button type="button" class="closeX" onclick="jsReqInfoForm.onHideResponse();">--}}
                        {{--<span aria-hidden="true">×</span>--}}
                    {{--</button>--}}
                </p>
                <button type="button" class="btn btn-secondary btn-sm" style="width: 150px;" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


{{-- form --}}
<div class="modal fade req-info-modal" id="id_req_info_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h3 class="modal-title">Request Info</h3>
                <button type="button" class="close" onclick="jsReqInfoForm.onHideReqMoreInfo();">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="id_form_req_info"
                  name="more_info"
                  action="/abc.do"
                  method="POST"
                  class="inline gn-form">

                {{ csrf_field() }}
                <input type="hidden" name="target_id" value="{{$model->getModelId()}}">
                <input type="hidden" name="target_type" value="{{$model->getModelType()}}">

                <div class="modal-body">
                    <div class="container">

                        <div class="form-group row">
                            <div class="col-lg-12">
                                <span>This information will be provided to {{ \App\Models\ClientInfo::isJCF() ? 'JCF' : 'our' }} staff only. We will then reach out to you in connection with this request.</span>
                            </div>
                        </div>

                        <div class="form-group row" id="id_info_error" style="display: none">
                            <div class="col-lg-12">
                                <span class="error-response">Some error has occurred while processing your request.</span>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label for="id_name" class="col-lg-2 col-md-2 col-form-label text-right pr-0">Name</label>
                            <div class="col-lg-5 col-md-10">
                                <input id="id_name" class="form-control" name="name" value="{{$contact->name}}" required type="text">
                                <p id="id_name_err" class="error" style="display: none">Name is required</p>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label for="id_phone" class="col-lg-2 col-md-2 col-form-label text-right pr-0">Phone</label>
                            <div class="col-lg-5 col-md-10">
                                <input id="id_phone" class="form-control pt-0" name="phone" value="{{$contact->getPrimaryPhoneNumber()}}" required type="text">
                                <p id="id_phone_err" class="error" style="display: none">Phone number is required</p>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label for="id_email" class="col-lg-2 col-md-2 col-form-label text-right pr-0">Email</label>
                            <div class="col-lg-5 col-md-10">
                                <input id="id_email" class="form-control pt-0" name="email" value="{{$contact->email_address}}" required type="text">
                                <p id="id_email_err" class="error" style="display: none">Email is required</p>
                            </div>
                        </div>

                        <div class="row">
                            <label class="offset-lg-2 col-lg-10 col-form-label">I would like to request (check all that apply):</label>
                        </div>

                        @foreach($options as $key => $value)
                            <div class="form-group row mb-1">
                                <div class="offset-lg-2 col-lg-10">

                                    <div class="form-check">
                                        {!! Form::checkbox('actions[]', $key, false, ['class' => 'form-check-input', 'id' => $key]) !!}
                                        {!! Form::label($key, $value, ['class' => 'form-check-label']) !!}
                                    </div>

                                </div>
                            </div>
                        @endforeach

                        <div class="form-group row mb-0 mt-3">
                            <label for="id_comment" class="col-lg-2 col-md-2 col-form-label text-right">Notes</label>
                            <div class="col-lg-9">
                                <textarea class="form-control" rows="3" required placeholder="Please provide any other details about your request here"
                                          name="comment" id="id_comment"></textarea>
                                <p id="id_comment_err" class="error" style="display: none">
                                    Please write a note or select at least one checkbox</p>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="container">
                        <div class="form-group row">
                            <div class="offset-lg-2 col-lg-5">
                                <a class="btn btn-theme" href="javascript:void(0);" onclick="jsReqInfoForm.onSubmit(this);" style="width: 100%;">
                                    <span id="id_rmi_submit">Submit</span>
                                    <span id="id_rmi_in_porgress" style="display: none"><img src="/ma/images/spinner.gif" width="16px"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    $(function(){
        jsReqInfoForm.init();
    });
</script>
