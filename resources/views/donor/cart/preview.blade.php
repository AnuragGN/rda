<?php
if (!isset($confirmed)) $confirmed = 0;
// step 1: check if confirmed
$affirm = !$confirmed;
// step 2: do not show for these clients
if (\App\Models\ClientInfo::isHGA() || \App\Models\ClientInfo::isJCF()) {
    $affirm = false;
}
?>
@extends ('donor.layouts.main')

@section ('content')

    <section class="content-header">
        <div class="container">
            <div class="row mt-2">
                <div class="col-xl-9">
                    @if(\App\Models\ClientInfo::isHGA())
                        <h1>Confirmation</h1>
                    @else
                        <h1>{{ $confirmed == 1 ? 'Confirmation' : 'Confirm Grant Recommendation(s)' }}</h1>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container">
            <div class="form-wrapper2 form-last2">
                <div class="row">
                    <div class="col-xl-9">

                        @if($confirmed == 1)
                            @if(\App\Models\ClientInfo::isCCT())
                                <p>Your recommendation(s) has been submitted successfully.
                                    If you have any questions or concerns, please reach out to your
                                    Philanthropic Advisor.</p>
                            @elseif(\App\Models\ClientInfo::isCCT()) {{--"FOR NT"--}}
                                <p>Your recommendation(s) has been submitted successfully.
                                    If you have any questions or concerns, please reach out to your
                                    Relationship Manager</p>
                            @else
                                <p>Your recommendation(s) has been submitted successfully.</p>
                            @endif
                        @else
                            <p>Please review and confirm or click cancel to make any changes.</p>
                        @endif

                        <div class="row">
                            <div class="col-12">
                                {!! Form::open(array('route' => ['checkout', 1])) !!}

                                @forelse($models as $i => $model)
                                    @include(\App\Models\ClientInfo::clientViewFor("cart.preview-item", "donor."), ['model' => $model])
                                @empty
                                    @include("utils.data-not-found", [])
                                @endforelse

                                @if($affirm)
                                    <div class="form-group row" style="align-items: center;">
                                        <div class="col-1 text-right">
                                            <input class="form-control2 checkbox-1x text-right" id="id_affirmation" name="affirmation" type="checkbox">
                                        </div>
                                        <label for="id_affirmation" class="col-11 col-form-label;" style="font-weight: 600;">
                                            I affirm that this grant is for charitable purposes and that
                                            neither I nor any member of my family will receive goods or services
                                            in exchange for this grant.
                                        </label>
                                    </div>
                                    <hr>
                                @endif

                                <div class="col-12">
                                    <p style="text-align: right">Total Recommended: {{ $total }}</p>
                                </div>

                                @if( $confirmed == 1)
                                    <div class="col-md-3 col-sm-4 col-6">
                                        <a href="{{ route('donor-home') }}" class="btn btn-primary btn-accent" style="width: 100%">Home</a>
                                    </div>
                                @else
                                    <div class="col-12">
                                        <div class="btn-bar btn-bar-right">
                                            <a href="{{ route('my-cart') }}">Cancel</a>
                                            {!! Form::submit('Confirm & Submit', ['name' => 'action', 'id' => 'js_grants_confirmed', 'class' => 'btn btn-primary btn-accent', 'style' => 'width: 200px']) !!}
                                        </div>
                                    </div>
                                @endif

                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(function(){
            var affirmationItem = $('#id_affirmation');
            if ( affirmationItem.length ) {
                $(':input[type="submit"]').prop('disabled', true);
            }
            affirmationItem.change(function() {
                if(this.checked) {
                    $(':input[type="submit"]').prop('disabled', false);
                } else {
                    $(':input[type="submit"]').prop('disabled', true);
                }
            });

        });
    </script>

@endsection
