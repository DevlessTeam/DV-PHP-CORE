<div class="col-md-3 left_col menu_fixed">
    <div class="left_col scroll-view">
        <div class="navbar nav_title logo-wrap" style="border: 0;">
            <img src="{{Request::secure(Request::root()).'/img/logo.png'}}" alt="Devless">
                <sup>{{config('devless')['version']}}</sup>
        </div>

        <div class="clearfix"></div>

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">

                <ul class="nav side-menu">
                    <li class="active"><a href="#"><i class="fa fa-laptop"></i>Dashboard</a></li>
                    <li><a href="{{route('app.index')}}"><i class="fa fa-desktop"></i>App</a></li>
                    <li><a><i class="fa fa-gears"></i> Services <span class="fa fa-chevron-right"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('services.index') }}">All Services</a></li>
                            <li><a href="{{ route('services.create') }}">Add New</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-database"></i> Data Tables <span class="fa fa-chevron-right"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ url('datatable') }}">Service Tables</a></li>
                            <li><a href="{{ url('devless_users') }}">Users</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ url('hub') }}"><i class="fa fa-cubes"></i>Service Hub</a></li>
                    <li><a href="{{ url('privacy') }}"><i class="fa fa-lock"></i>Privacy</a></li>
                    <li><a href="{{ url('console') }}"><i class="fa fa-terminal"></i>API Console</a></li>
                    <li><a href="{{route('migrate.index')}}"><i class="fa fa-download"></i>Migration</a></li>
                    <li><a href="{{url('logout')}}"><i class="fa fa-sign-out"></i>Logout </a></li>
                </ul>
            </div>

        </div>
        <!-- /sidebar menu -->
        
        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Logout" href="#">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
        </div>
        <!-- /menu footer buttons -->
        
    </div>
</div>
