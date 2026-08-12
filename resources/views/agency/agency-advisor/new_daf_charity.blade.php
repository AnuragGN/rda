
@extends ('agency.layouts.main')
@section ('content')
    @include('common.page-header', ['pageTitle' => 'New DAF - '.$charity['charity_name'] ])
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-r-15 org-view">
                    <div class="form-wrapper form-last">
                        <div class="tab-content">
                            {{--.tab-pane--}}
                            <div class="tab-pane active" id="organization">
                                <div class="row">
                                    <div class="col-lg-9 col-md-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="page-subtitle">Create Donor-Advised Fund Account</h4>
                                            </div>
                                        </div>
                                        <form method="POST" action="#" accept-charset="UTF-8" id="daf-account-form">
                                            @csrf
                                            <div class="form-group">
                                                <p>Please fill in the form below to create DAF account.
                                                After account creation, Donor can login to complete the Donor-Advised Fund application.</p>
                                            </div>
                                            <div class="form-group"></div>

                                            <div class="form-group row">
                                                <label for="name" class="col-md-3 col-form-label text-right pr-0">Name</label>
                                                <div class="col-md-3 mb-1">
                                                    <input class="form-control" rows="2" placeholder="first name" required="" onkeypress="return /[a-z]/i.test(event.key)" name="first_name" type="text" fdprocessedid="c6m74l">
                                                </div>
                                                 <div class="col-md-3 mb-1">
                                                    <input class="form-control" rows="2" placeholder="last name" required="" onkeypress="return /[a-z]/i.test(event.key)" name="last_name" type="text" fdprocessedid="e86iqs">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="email" class="col-md-3 col-form-label text-right pr-0">Email</label>
                                                <div class="col-md-6">
                                                    <input class="form-control" rows="2" required="" name="email" type="email" id="email" fdprocessedid="ec3wa" placeholder="Email">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="phone_number" class="col-sm-3 col-3 col-form-label text-right pr-0">Phone</label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">+1</div>
                                                        </div>
                                                        <input id="id_phone" class="form-control" required="" name="phone_number" type="text" fdprocessedid="6xu89e" placeholder="Phone">
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="form-btn-bar">
                                                <div class="col-md-12 form-footer">
                                                    <div class="row">
                                                        <p class="offset-md-3 col-md-3">
                                                            <input name="save" id="id_save_btn" class="btn btn-wide btn-accent w100" type="submit" value="Submit" fdprocessedid="ludtib">
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<script>

function validateTermsAndSubmit(e) {
    e.preventDefault();
    var cb = document.getElementById('accept_terms');
    var err = document.getElementById('terms-error');
    var success = document.getElementById('success-message');
    if (!cb.checked) {
        cb.focus();
        err.textContent = 'You must accept the Terms & Conditions to proceed.';
        err.style.display = 'block';
        success.style.display = 'none';
        return false;
    } else {
        err.textContent = '';
        err.style.display = 'none';
    }
   
    // Optionally, submit the form for real:
     e.target.submit();
    return false;
}
</script>
@endsection