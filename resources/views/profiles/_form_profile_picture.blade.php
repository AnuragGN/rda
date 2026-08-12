{{-- resources/views/profiles/_form_profile_picture.blade.php --}}

<form method="POST" action="{{ route('profile-picture-save') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-group row">
        <label for="profile_picture" class="col-md-3 col-form-label text-md-right">Choose Photo</label>
        <div class="col-md-9">
            @if($profile->photo_url)
                <div>
                    <img src="{{ $profile->photo_url }}" alt="Profile Picture" style="max-width: 150px; max-height: 150px; border: 1px solid #ccc; padding: 5px; margin-bottom: 10px;">
                </div>
            @endif
            <input type="file" name="profile_picture" id="profile_picture" class="form-control">
        </div>
    </div>
    <hr>
    <div class="form-group row">
        <div class="col-md-9 offset-md-3">
            <button type="submit" class="btn btn-accent w40">Save</button>
        </div>
    </div>
</form>

