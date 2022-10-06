   <!-- Top Bar Start -->
   <div class="topbar">


<!-- LOGO -->
<div class="topbar-left">


    <a href="{{route('index')}}" class="logo">
        <span>
                <img src="assets/images/logo-light.png" alt="" height="30">
            </span>
        <i>
                <img src="assets/images/logo-sm.png" alt="" height="60">
        </i>
    </a>

</div>


<nav class="navbar-custom">
    <ul class="navbar-right d-flex list-inline float-right mb-0">
        <!-- notification -->


        <li class="dropdown notification-list list-inline-item d-none d-md-inline-block">
            <div  class="app-search">
                <div class="form-group mb-0 ">
                    @if(isset($header))
                            @if(isset($header['badge']))
                                @foreach($header['badge'] as $key=>$value)
                                    <span class=" badge badge-{{$value['style']}}">{{$key}}</span>
                                @endforeach
                            @endif
                    @endif
                </div>
            </div>
        </li>

        <li class="dropdown notification-list">
            <div class="dropdown notification-list nav-pro-img">
                <a class="dropdown-toggle nav-link arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="assets/images/users/user-4.jpg" alt="user" class="rounded-circle">
                    <?php
                    if(isset(Auth::user()->name)) echo Auth::user()->name;
                    ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    <!-- item-->
                    <a class="dropdown-item" href="{{ route('2fa_setting') }}"><i class="mdi mdi-two-factor-authentication"></i>Cập nhật 2FA</a>
                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item text-danger" href="{{route('logout')}}"><i class="mdi mdi-power text-danger"></i> Logout</a>
                </div>
            </div>
        </li>

    </ul>

    <ul class="list-inline menu-left mb-0">
        <li class="float-left">
            @if(isset($header))
            <div class="{{$header['title']}}_button">
                <button class="button-menu-mobile open-left waves-effect" style="width: auto">
                    <h4 class="font-size-18">{{$header['title']}}</h4>
                </button>
            @foreach($header['button'] as $key=>$value)
                <button class="btn btn-{{$value['style']}}"  type="button" id="{{$value['id']}}"><span>{{$key}}</span></button>
            @endforeach
            </div>
            @else
                <button class="button-menu-mobile open-left waves-effect" style="width: auto">
                    <i class="mdi mdi-menu"></i>
                </button>
            @endif

        </li>

        <li class="d-none d-sm-block">
            <div class="pt-3 d-inline-block">
            </div>
        </li>
    </ul>

</nav>

</div>
<!-- Top Bar End -->
