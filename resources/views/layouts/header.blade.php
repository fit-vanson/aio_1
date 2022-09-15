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
{{--        <li class="dropdown notification-list d-none d-md-block">--}}
{{--            <form role="search" class="app-search">--}}
{{--                <div class="form-group mb-0">--}}
{{--                    <input type="text" class="form-control" placeholder="Search..">--}}
{{--                    <button type="submit"><i class="fa fa-search"></i></button>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </li>--}}

        <!-- full screen -->
{{--        <li class="dropdown notification-list d-none d-md-block">--}}
{{--            <a class="nav-link waves-effect" href="#" id="btn-fullscreen">--}}
{{--                <i class="mdi mdi-fullscreen noti-icon"></i>--}}
{{--            </a>--}}
{{--        </li>--}}

        <!-- notification -->

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

{{--    <ul class="list-inline menu-left mb-0">--}}
{{--        <li class="float-left">--}}
{{--            <button class="button-menu-mobile open-left waves-effect">--}}
{{--                <i class="mdi mdi-menu"></i>--}}
{{--            </button>--}}
{{--        </li>--}}
{{--    </ul>--}}

    <ul class="list-inline menu-left mb-0">
        <li class="float-left">
            <button class="button-menu-mobile open-left waves-effect" style="width: auto">
                @if(isset($header))
                <h4 class="font-size-18">{{$header['title']}}</h4>

                        @foreach($header['button'] as $key=>$value)
                            <button class="btn btn-success"  type="button" id="{{$value}}"><span>{{$key}}</span></button>
                        @endforeach

                @else
                    <i class="mdi mdi-menu"></i>
                @endif
            </button>
        </li>
        <li class="d-none d-sm-block">


            <div class="pt-3 d-inline-block">

{{--                <a class="btn btn-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                    Create--}}
{{--                </a>--}}

{{--                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">--}}
{{--                    <a class="dropdown-item" href="#">Action</a>--}}
{{--                    <a class="dropdown-item" href="#">Another action</a>--}}
{{--                    <a class="dropdown-item" href="#">Something else here</a>--}}
{{--                    <div class="dropdown-divider"></div>--}}
{{--                    <a class="dropdown-item" href="#">Separated link</a>--}}
{{--                </div>--}}
            </div>
        </li>
    </ul>

</nav>

</div>
<!-- Top Bar End -->
