
{{-- resources/views/profiles/view_profile_picture.blade.php --}}

<div class="card gn-shadow">
    <div class="header">
        <div onclick="sageCollapsible(this)" class="collapsible-child-visible c-pointer closed" data-child-id="id_profile_picture_view">
            <span class="open"><i class="fas fa-caret-down"></i></span>
            <span class="closed"><i class="fas fa-caret-right"></i></span>
            Profile Picture
        </div>
        {{-- <div><a href="{{ route('profile-picture-edit') }}">Edit</a></div> --}}
    </div>
    <div class="body" id="id_profile_picture_view">
        @if($profile->photo_url)
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <img src="{{ $profile->photo_url }}" alt="Profile Picture" style="max-width: 100px; max-height: 100px; border: 1px solid #ccc; padding: 5px;">
                <a href="{{ route('profile-picture-edit') }}" class="txt-btn-sm">
                    <i class="fas fa-edit toggle-icon"></i> Change Profile Picture
                </a>
            </div>
        @else
            <a href="{{ route('profile-picture-edit') }}" class="txt-btn-sm">
                <i class="fas fa-plus-circle toggle-icon"></i> Add Profile Picture
            </a>
        @endif
    </div>
</div>
<hr>

{{-- style="display: none;" --}}

