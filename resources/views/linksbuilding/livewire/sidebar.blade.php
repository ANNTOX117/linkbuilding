<div>
    <a href="#" class="nav-link">
        <div class="profile-image">
            @if ($profile_image)
                <img src="<?php echo Theme::url('uploads'); ?>/{{$profile_image}}"  class="img-xs rounded-circle" alt="{{__('Profile image')}}" />
            @else
                <img src="{{asset('/debugadmin/assets/images/faces/face8.jpg')}}"  class="img-xs rounded-circle" alt="{{__('Profile image')}}">
            @endif
            <div class="dot-indicator bg-success"></div>
        </div>
        <div class="text-wrapper">
            <p class="profile-name">{{$profile_name}}</p>
            <p class="designation">{{ucfirst($role)}}</p>
        </div>
    </a>
</div>
