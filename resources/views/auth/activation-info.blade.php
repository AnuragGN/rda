@extends ('donor.layouts.main')

<style>
    .abox {
        margin: 2rem auto 0;
        min-width: 300px;
        max-width: 500px;
        padding: 15px;
        /*background: #f9f9f9;*/
        /*border: 1px solid #eee*/
    }
    .abox p {
        font-size: 1.2rem;
    }
    .abtnbar {
        margin: 1rem auto;
        text-align: center;
    }
    .abtnbar a {
        font-size: 1.5rem;
        padding: 0.5rem 0;
        width: 200px;
    }
    .abtnbar a i {
        margin-right: 4px;
    }
</style>
@section ('content')

    <div class="row text-center">
        <div class="abox">
            <h3> {{ $title }}</h3>
            <p>{{ $info }}
        </div>
    </div>

    @if(Auth::guest())
        <div class="abtnbar">
            <a class="btn btn-accent" onclick="jsAuth.showModel()" href="#">
                <i class="fas fa-user" aria-hidden="true"></i> Sign in </a>
        </div>
    @endif

    <div class="abtnbar">
        <a class="btn btn-o-dark" href="/">
            <i class="fas fa-home" aria-hidden="true"></i> Home</a>
    </div>
    <br>
    <br>
    <br>
    <br>

@endsection
