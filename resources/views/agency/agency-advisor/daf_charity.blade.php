
@extends ('agency.layouts.main')
@section ('content')
    @include('common.page-header', ['pageTitle' => $charity['charity_name'] ])
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
                                        <h3>Create Donor-Advised Fund Account</h3>
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="page-subtitle">Terms & Conditions</h4>
                                            </div>
                                        </div>
                                        
                                        {!! $charity['term_condition'] !!}

                                        
                                        <form id="daf-form" method="POST" action="{{ route('agency-charity-new-daf') }}" onsubmit="return validateTermsAndSubmit(event);">
                                            @csrf
                                            <div class="mb-4">
                                                <div class="form-check m-0" style="display: flex; align-items: center;">
                                                    <input class="form-check-input" type="checkbox" id="accept_terms" required style="width: 1.0em; height: 1.5em;">
                                                    <label class="form-check-label ml-1 mt-1" for="accept_terms" style="font-size: 1.0em;">
                                                        I accept the Terms & Conditions.
                                                    </label>
                                                </div>
                                                <div id="terms-error" class="mt-2" style="color: #d9534f; font-size: 1em; display: none;"></div>
                                                <div class="mt-3">
                                                    <input name="save" id="id_save_btn" class="btn btn-accent" type="submit" value="Create DAF Account">
                                                    <input type="hidden" value="{{ $charity['charity_id'] }}" name="charity_id" >
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