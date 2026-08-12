{{-- resources/views/profiles/edit_profile_picture.blade.php --}}

@extends (\App\Helpers\GnUtils::getUserView('layouts.main'), ['container' => 'none'])

@section ('content')

    @include('common.page-header', ['pageTitle' => 'Profile Picture', 'hcXlWidth' => 12])

    <section class="content">
        <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-xl-9">

                        <div class="form-make-grant gn-form">
                            @include('errors.form-errors')
                            @include('profiles._form_profile_picture')

                            <hr class="mt-5 mb-2">

                            <div style="text-align: right;">
                                @if($profile->photo_url)
                                    {{-- <a href="{{ route('profile-picture-delete') }}" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Delete Profile Picture
                                    </a> --}}
                                    <a href="javascript:void(0);" onclick="deleteProfilePicture()" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Delete Profile Picture
                                    </a>                                    
                                @else
                                
                                    <small style="color: #999"><i class="fas fa-info-circle"></i> No profile picture uploaded.</small>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

<script>
    function deleteProfilePicture() {
    var message = "Are you sure you want to delete your profile picture?";

    $.confirm({
        columnClass: 'medium',
        title: '',
        content: message,
        buttons: {
            no: {
                text: 'No',
                btnClass: 'btn-light',
                keys: ['enter', 'shift'],
                action: function() {
                    // no action
                }
            },
            yes: {
                text: 'Yes',
                btnClass: 'btn-danger',
                keys: ['enter', 'shift'],
                action: function() {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('profile-picture-delete') }}",
                        data: {
                            '_token': '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            location.reload();
                        },
                        error: function(error) {
                            console.error(error);
                            // Handle error here
                        }
                    });
                }
            }
        }
    });
}

</script>
