      <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <div class="slimscroll-menu" id="remove-scroll">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        @if(\Illuminate\Support\Facades\Auth::user()->part_time != 1)
                        <ul class="metismenu" id="side-menu">
                            <li class="menu-title">Main</li>
                            <li>
                                <a href="{{route('index')}}" class="waves-effect">
                                    <i class="ti-home"></i><span> Trang chủ </span>
                                </a>
                            </li>
                            <li class="menu-title">VietMMO</li>
                            <li>
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-package"></i> <span> App And Project <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span> </a>
                                <ul class="submenu">
                                    @can('du_an-index')
                                    <li><a href="{{route('da.index')}}">Quản lý Dự án</a></li>
                                    @endcan
                                    @can('project-index')
                                    <li><a href="{{route('project.index')}}">Quản lý Project</a></li>
                                    @endcan
                                    @can('template-index')
                                    <li><a href="{{route('template.index')}}">Quản lý Template</a></li>
                                    @endcan
                                    @can('project-index')
                                        <li><a href="{{route('project.indexBuild')}}">Tiến trình xử lý</a></li>
                                        <li><a href="{{route('project.appChplay')}}">Quản lý APP (CHPlay)</a></li>
                                        <li><a href="{{route('project.appAmazon')}}">Quản lý APP (Amazon)</a></li>
                                        <li><a href="{{route('project.appSamsung')}}">Quản lý APP (Samsung)</a></li>
                                        <li><a href="{{route('project.appXiaomi')}}">Quản lý APP (Xiaomi)</a></li>
                                        <li><a href="{{route('project.appOppo')}}">Quản lý APP (Oppo)</a></li>
                                        <li><a href="{{route('project.appVivo')}}">Quản lý APP (Vivo)</a></li>
                                        <li><a href="{{route('project.appHuawei')}}">Quản lý APP (Huawei)</a></li>
                                    @endcan
                                    @can('keystore-index')
                                        <li><a href="{{route('keystore.index')}}">Quản lý Keystore</a></li>
                                    @endcan
                                </ul>
                            </li>

                            <li {{ Route::currentRouteName() == 'apk_process.index'  ? 'class=mm-active' :'' }}>
                                <a {{ Route::currentRouteName() == 'apk_process.index'  ? 'class=mm-active' :'' }} href="javascript:void(0);" class="waves-effect"><i class="ti-image"></i> <span> APK Analysis<span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span> </a>
                                <ul class="submenu">
                                    <li {{@$_GET['type'] == 2 ?'class=mm-active' :'' }} >
                                        <a href="javascript:void(0);" class="waves-effect"><i class="ti-game"></i> <span> Game <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span> </a>
                                        <ul class="submenu" >
                                            <?php
                                                $arr = App\Models\Market_category::where('type',2)->get();
                                            ?>
                                            @foreach($arr as $item)
                                            <li {{@$_GET['category'] == $item['id'] ? 'class=mm-active' :'' }}><a  {{@$_GET['category'] == $item['id'] ? 'class=mm-active' :'' }} href="{{route('apk_process.index',['type'=>$item['type'],'category'=>$item['id']])}}">&emsp;&emsp;{{$item['name']}}</a></li>
                                            @endforeach
                                        </ul>
                                    </li>

                                    <li {{@$_GET['type'] == 1 ?'class=mm-active' :'' }} >
                                        <a href="javascript:void(0);" class="waves-effect"><i class="ti-package"></i> <span> App <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span> </a>
                                        <ul class="submenu" >
                                            <?php
                                            $arr = App\Models\Market_category::where('type',1)->get();
                                            ?>
                                            @foreach($arr as $item)
                                                <li {{@$_GET['category'] == $item['id'] ? 'class=mm-active' :'' }}><a  {{@$_GET['category'] == $item['id'] ? 'class=mm-active' :'' }} href="{{route('apk_process.index',['type'=>$item['type'],'category'=>$item['id']])}}">&emsp;&emsp;{{$item['name']}}</a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    <li {{@$_GET['type'] ===0 ?'class=mm-active' :'' }} >
                                        <a href="javascript:void(0);" class="waves-effect"><i class="ti-receipt"></i> <span> Other <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span> </a>
                                        <ul class="submenu" >
                                            <?php
                                            $arr = App\Models\Market_category::where('type',0)->get();
                                            ?>
                                            @foreach($arr as $item)
                                                <li {{@$_GET['category'] == $item['id'] ? 'class=mm-active' :'' }}><a  {{@$_GET['category'] == $item['id'] ? 'class=mm-active' :'' }} href="{{route('apk_process.index',['type'=>$item['type'],'category'=>$item['id']])}}">&emsp;&emsp;{{$item['name']}}</a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    <li {{@$_GET['pss_console'] == 3 ? 'class=mm-active' :'' }}><a href="{{route('apk_process.index',['pss_console'=>3])}}"><i class="ti-check"></i>Đã xử lý</a></li>

                                </ul>
                            </li>


                            <li>
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-image"></i> <span> Template Preview <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span> </a>
                                <ul class="submenu">
                                    @can('template-preview-index')
                                        <li><a href="{{route('template-preview.index')}}">Template Frame Preview</a></li>
                                        <li><a href="{{route('template-text-preview.index')}}">Template Text Preview</a></li>
                                        <li><a href="{{route('category_template_frame.index')}}">Category Template Frame</a></li>
                                        <li><a href="{{route('category_template.index')}}">Category Template</a></li>
                                        <li><a href="{{route('data_profile.index')}}">Data Mẫu</a></li>
                                    @endcan

                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-receipt"></i> <span> Ga & Dev & Ads <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span> </a>
                                <ul class="submenu">
                                    @can('gadev-index')
                                        <li><a href="{{route('gadev.index')}}">Gmail Quản lý</a></li>
                                    @endcan
                                    @can('dev-index')
                                        <li><a href="{{route('dev.index')}}">Quản lý DEV (CH-Play)</a></li>
                                    @endcan

                                    @can('dev_amazon-index')
                                        <li><a href="{{route('dev_amazon.index')}}">Quản lý DEV (Amazon)</a></li>
                                    @endcan
                                    @can('dev_samsung-index')
                                        <li><a href="{{route('dev_samsung.index')}}">Quản lý DEV (Samsung)</a></li>
                                    @endcan
                                    @can('dev_xiaomi-index')
                                        <li><a href="{{route('dev_xiaomi.index')}}">Quản lý DEV (Xiaomi)</a></li>
                                    @endcan
                                    @can('dev_oppo-index')
                                        <li><a href="{{route('dev_oppo.index')}}">Quản lý DEV (Oppo)</a></li>
                                    @endcan
                                    @can('dev_vivo-index')
                                        <li><a href="{{route('dev_vivo.index')}}">Quản lý DEV (Vivo)</a></li>
                                    @endcan

                                    @can('dev_huawei-index')
                                        <li><a href="{{route('dev_huawei.index')}}">Quản lý DEV (Huawei)</a></li>
                                    @endcan

                                    @can('ga-index')
                                        <li><a href="{{route('ga.index')}}">Quản lý GA</a></li>
                                    @endcan
                                    @can('ga-index')
                                        <li><a href="{{route('profile.index')}}">Quản lý Profile</a></li>
                                    @endcan
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-sim"></i> <span> Quản lý Sim <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span> </a>
                                <ul class="submenu">
                                    @can('khosim-index')
                                        <li><a href="{{route('khosim.index')}}">Quản lý Kho sim</a></li>
                                    @endcan
                                    @can('cocsim-index')
                                        <li><a href="{{route('cocsim.index')}}">Quản lý Cọc Sim</a></li>
                                    @endcan
                                    @can('hub-index')
                                        <li><a href="{{route('hub.index')}}">Quản lý Hub</a></li>
                                    @endcan
                                    @can('sms-index')
                                        <li><a href="{{route('sms.index')}}">Quản lý SMS</a></li>
                                    @endcan
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-email"></i> <span>Job Auto CHplay<span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span> </a>
                                <ul class="submenu">
                                    @can('mail_manage-index')
                                        <li><a href="{{route('mail_manage.index')}}">Tài nguyên Gmail</a></li>
                                    @endcan
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-bookmark-alt"></i> <span> Gmail Reg Auto<span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span> </a>
                                <ul class="submenu">
                                    @can('mail_manage-index')
                                        <li><a href="{{route('mail_parent.index')}}">Quản lý Mail Parent</a></li>
                                    @endcan
                                    @can('mail_manage-index')
                                        <li><a href="{{route('mail_parent.indexNo')}}">Mail Parent (No Phone)</a></li>
                                    @endcan
                                    @can('mail_reg-index')
                                        <li><a href="{{route('mail_reg.index')}}">Quản lý Mail Reg</a></li>
                                    @endcan
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="waves-effect"><i class="typcn typcn-device-desktop"></i> <span>Fake Device<span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span> </a>
                                <ul class="submenu">
                                    @can('device-index')
                                        <li><a href="{{route('device.index')}}">Quản lý Device</a></li>
                                    @endcan

                                        <li><a href="{{route('imei.index')}}">Gen Imei</a></li>
                                        <li><a href="{{route('iccid.index')}}">Gen ICCID</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="waves-effect"><i class="fas fa-code"></i> <span>Script<span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span> </a>
                                <ul class="submenu">
                                    @can('script-index')
                                        <li><a href="{{route('script.index')}}">Quản lý Script</a></li>
                                    @endcan

                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-browser-upload"></i> <span>File Manager<span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span> </a>
                                <ul class="submenu">
                                    @can('template-index')
                                        <li><a href="{{route('template.upload')}}">File Manager</a></li>
                                    @endcan

                                </ul>
                            </li>

                            <li>
                                <a href="{{route('checkapi.index')}}" class="waves-effect"><i class="ti-check-box"></i> <span> Check API <span class="float-right menu-arrow"></span> </span> </a>

                            </li>

                            <li>
                                <a href="{{route('bot.index')}}" class="waves-effect"><i class="ti-check-box"></i> <span> Bot <span class="float-right menu-arrow"></span> </span> </a>

                            </li>
                            <li>
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-archive"></i> <span> Quản trị phân quyền <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span> </a>
                                <ul class="submenu">
                                    @can('user-index')
                                    <li><a href="{{route('user.index')}}">User</a></li>
                                    <li><a href="{{route('settings.index')}}">Settings</a></li>
                                    @endcan
                                    @can('vai_tro-index')
                                    <li><a href="{{route('role.index')}}">Vai trò</a></li>
                                    @endcan
                                    @can('phan_quyen-index')
                                    <li><a href="{{route('permission.index')}}">Phân quyền</a></li>
                                    @endcan
                                </ul>
                            </li>
                        </ul>
                        @else
                            <ul class="metismenu" id="side-menu">
                                <li class="menu-title">VietMMO</li>
                                <li>
                                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-package"></i> <span> App And Project <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span> </a>
                                    <ul class="submenu">
                                        @can('project-index')
                                            <li><a href="{{route('project.index')}}">Quản lý Project</a></li>
                                        @endcan
                                    </ul>
                                </li>
                            </ul>
                        @endif

                    </div>
                    <!-- Sidebar -->
                    <div class="clearfix"></div>
                </div>
                <!-- Sidebar -left -->
            </div>
            <!-- Left Sidebar End -->
