@extends('layouts.main')

@section('content')

    <div class="container">
        <h1>Activities</h1>
        <table class="table table-bordered">
            <tr>
                <th>Id</th>
                <th>D&T</th>
                <th>Name</th>
                <th>Action</th>
                <th>Description</th>
                <th>Target Type</th>
                <th>Target Id</th>
                <th>Ip</th>
                {{--<th>User Agent</th>--}}
                <th>User</th>
                {{--<th>Action</th>--}}
            </tr>
            @if($logs->count())
                @foreach($logs as $key => $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->created_on }}</td>
                        <td>{{ $log->name }}</td>
                        <td class="text-success">{{ $log->action }}</td>
                        <td><label class="label label-info">{{ $log->description }}</label></td>
                        <td><label class="label label-info">{{ $log->target_type }}</label></td>
                        <td><label class="label label-info">{{ $log->target_id ? $log->target_id : $log->target_string_id }}</label></td>
                        <td class="text-warning">{{ $log->ip }}</td>
                        {{--<td class="text-danger">{{ $log->agent }}</td>--}}
                        <td>{{ $log->getUserInfo() }}</td>
                        {{--<td><button class="btn btn-danger btn-sm">Delete</button></td>--}}
                    </tr>
                @endforeach
            @endif
        </table>
    </div>
@endsection
