@extends('donor.layouts.main')

@section ('content')

    <style>
        table .clr-primary {
            color: #fff;
        }
    </style>
    @include('common.page-header', ['pageTitle' => 'Investment Fund Performance'])
    <div class="container">
        <div class="form-wrapper form-last">

            <div class="form-group row">
                <div class="col-3">
                    <h5>AS OF MARCH 31, 2022</h5>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped ">
                    <thead>
                    <tr class="clr-primary">
                        <th scope="col">INVESTMENT FUND</th>
                        <th scope="col">QTR</th>
                        <th scope="col">1 YEAR</th>
                        <th scope="col">3 YEARS</th>
                        <th scope="col">5 YEARS</th>
                        <th scope="col">10 YEARS</th>
                        <th scope="col">SINCE INCEPTION</th>
                        <th scope="col">EXPENSE RATIOS</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($funds as $fund)
                        <tr>
                            <td>{{$fund->fund_investment}}</td>
                            <td>{{$fund->qtr}}</td>
                            <td>{{$fund->year_1}}</td>
                            <td>{{$fund->years_5}}</td>
                            <td>{{$fund->years_5}}</td>
                            <td>{{$fund->years_10}}</td>
                            <td>{{$fund->since_inception}}</td>
                            <td>{{$fund->expense_ratios}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection
