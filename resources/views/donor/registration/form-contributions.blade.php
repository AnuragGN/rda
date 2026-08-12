@extends ('donor.registration.main')

@section ('content')

    @include('common.page-header', ['pageTitle' => $custom->text->DAF_APPLICATION_FORM])

    <div class="container pageTop">
        <div class="form-body form-wrapper form-last custom-form">

            <div class="row">
                <div class="col-8">

                </div>

                <div class="col-4">
                    <br />
                    <div class="info-card">
                        <p>FOR CASH ACH DEPOSITS:
                            <br>Bank of America, Morristown NJ
                            <br>ABA# 111000111
                            <br>For credit to OAKWOOD Foundation
                            <br>Account# 0181181181
                            <br>Ref: For the benefit of: __________________</p>

                        <p>FOR CASH WIRE DEPOSITS:
                            <br>Bank of America, Morristown NJ
                            <br>ABA# 026026026
                            <br>For credit to OAKWOOD Foundation
                            <br>Account# 0181181181
                            <br>Ref: For the benefit of: __________________</p>

                        <p>FOR CHECK DEPOSITS:
                            <br>OAKWOOD Foundation
                            <br>P.O. Box 840840
                            <br>Morristown, NJ 07960-0330</p>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection
