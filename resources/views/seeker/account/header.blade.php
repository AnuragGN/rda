<section class="content-header">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h1>Account</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Account</li>
                    @if(isset($item))
                        <li class="breadcrumb-item active">{{$item}}</li>
                    @endif
                </ol>
            </div>
        </div>
    </div>
</section>

