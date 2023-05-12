<div>
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center"></div>
        <div class="navbar-menu-wrapper d-flex align-items-center">
            <ul class="navbar-nav">
                <li class="nav-item dropdown language-dropdown">
                    <a class="nav-link dropdown-toggle px-2 d-flex align-items-center" id="LanguageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                        @if(App::getLocale() == 'nl')
                            <div class="d-inline-flex mr-0 mr-md-3">
                                <div class="flag-icon-holder">
                                    <i class="flag-icon flag-icon-nl"></i>
                                </div>
                            </div>
                            <span class="profile-text font-weight-medium d-none d-md-block">{{__('Dutch')}}</span>
                        @endif
                        @if(App::getLocale() == 'en')
                            <div class="d-inline-flex mr-0 mr-md-3">
                                <div class="flag-icon-holder">
                                    <i class="flag-icon flag-icon-us"></i>
                                </div>
                            </div>
                            <span class="profile-text font-weight-medium d-none d-md-block">{{__('English')}}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-left navbar-dropdown py-2" aria-labelledby="LanguageDropdown">
                        <a href="{{ LaravelLocalization::getLocalizedURL('nl', null, [], true) }}" class="dropdown-item">
                            <div class="flag-icon-holder">
                                <i class="flag-icon flag-icon-nl"></i>
                            </div>
                            {{__('Dutch')}}
                        </a>
                        <a href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}" class="dropdown-item">
                            <div class="flag-icon-holder">
                                <i class="flag-icon flag-icon-us"></i>
                            </div>
                            {{__('English')}}
                        </a>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                {{-- <li class="nav-item dropdown">
                    <a class="nav-link count-indicator" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-bell-outline"></i>
                        <span class="count">7</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="messageDropdown">
                        <a class="dropdown-item py-3">
                            <p class="mb-0 font-weight-medium float-left">You have 7 unread mails </p>
                            <span class="badge badge-pill badge-primary float-right">View all</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <img src="{{asset('debugadmin/assets/images/faces/face10.jpg')}}" alt="image" class="img-sm profile-pic">
                            </div>
                            <div class="preview-item-content flex-grow py-2">
                                <p class="preview-subject ellipsis font-weight-medium text-dark">Marian Garner </p>
                                <p class="font-weight-light small-text"> The meeting is cancelled </p>
                            </div>
                        </a>
                        <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <img src="{{asset('debugadmin/assets/images/faces/face12.jpg')}}" alt="image" class="img-sm profile-pic">
                            </div>
                            <div class="preview-item-content flex-grow py-2">
                                <p class="preview-subject ellipsis font-weight-medium text-dark">David Grey </p>
                                <p class="font-weight-light small-text"> The meeting is cancelled </p>
                            </div>
                        </a>
                        <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <img src="{{asset('debugadmin/assets/images/faces/face1.jpg')}}" alt="image" class="img-sm profile-pic">
                            </div>
                            <div class="preview-item-content flex-grow py-2">
                                <p class="preview-subject ellipsis font-weight-medium text-dark">Travis Jenkins </p>
                                <p class="font-weight-light small-text"> The meeting is cancelled </p>
                            </div>
                        </a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link count-indicator" id="notificationDropdown" href="#" data-toggle="dropdown">
                        <i class="mdi mdi-email-outline"></i>
                        <span class="count bg-success">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="notificationDropdown">
                        <a class="dropdown-item py-3 border-bottom">
                            <p class="mb-0 font-weight-medium float-left">You have 4 new notifications </p>
                            <span class="badge badge-pill badge-primary float-right">View all</span>
                        </a>
                        <a class="dropdown-item preview-item py-3">
                            <div class="preview-thumbnail">
                                <i class="mdi mdi-alert m-auto text-primary"></i>
                            </div>
                            <div class="preview-item-content">
                                <h6 class="preview-subject font-weight-normal text-dark mb-1">Application Error</h6>
                                <p class="font-weight-light small-text mb-0"> Just now </p>
                            </div>
                        </a>
                        <a class="dropdown-item preview-item py-3">
                            <div class="preview-thumbnail">
                                <i class="mdi mdi-settings m-auto text-primary"></i>
                            </div>
                            <div class="preview-item-content">
                                <h6 class="preview-subject font-weight-normal text-dark mb-1">Settings</h6>
                                <p class="font-weight-light small-text mb-0"> Private message </p>
                            </div>
                        </a>
                        <a class="dropdown-item preview-item py-3">
                            <div class="preview-thumbnail">
                                <i class="mdi mdi-airballoon m-auto text-primary"></i>
                            </div>
                            <div class="preview-item-content">
                                <h6 class="preview-subject font-weight-normal text-dark mb-1">New user registration</h6>
                                <p class="font-weight-light small-text mb-0"> 2 days ago </p>
                            </div>
                        </a>
                    </div>
                </li> --}}
                <li class="nav-item dropdown d-none d-xl-inline-block user-dropdown">
                    <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                        @if ($profile_image)
                            <img src="<?php echo Theme::url('uploads'); ?>/{{$profile_image}}"  class="img-xs rounded-circle" alt="{{__('Profile image')}}" />
                        @else
                            <img src="{{asset('/debugadmin/assets/images/faces/face8.jpg')}}"  class="img-xs rounded-circle" alt="{{__('Profile image')}}">
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                        <div class="dropdown-header text-center">

                            @if($profile_image)
                                <img src="<?php echo Theme::url('uploads'); ?>/{{$profile_image}}"  class="img-md rounded-circle" alt="{{__('Profile image')}}" />
                            @else
                                <img src="{{asset('/debugadmin/assets/images/faces/face8.jpg')}}"  class="img-md rounded-circle" alt="{{__('Profile image')}}">
                            @endif

                            <p class="mb-1 mt-3 font-weight-semibold">{{$profile_name}}</p>
                            <p class="font-weight-light text-muted mb-0">{{$email}}</p>

                        </div>
                        <a  data-toggle="modal" data-target="#prof" class="dropdown-item">{{__('My Profile')}}</a>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign Out<i class="dropdown-item-icon ti-power-off"></i></a>
                        <form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                <span class="mdi mdi-menu"></span>
            </button>
        </div>
    </nav>
</div>
