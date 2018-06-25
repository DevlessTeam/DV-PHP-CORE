
<!-- sidebar left start-->
<div class="sidebar-left">
    <div class="header-section">

        <!--logo and logo icon start-->
        <div class="logo dark-logo-bg hidden-xs hidden-sm">
            <a href="/">
                <img src="/img/logo.png')}}" alt="Devless">
                <!-- @if(Request::secure())
                    <img src="{{Request::secure(Request::path()).'/img/logo.png'}}" alt="Devless">
                @else
                    <img src="{{asset('/img/logo.png')}}" alt="Devless">
                @endif -->
                <sup>{{config('devless')['version']}}</sup>
            </a>
        </div>


        <!--logo and logo icon end-->

        <!--toggle button start-->
        <a class="toggle-btn"><i class="fa fa-outdent"></i></a>
        <!--toggle button end-->




    </div>
    <!--responsive view logo end-->

    <div class="sidebar-left-info">
        <!-- visible small devices start-->
        <div class=" search-field"></div>
        <!-- visible small devices end-->

        <!--sidebar nav start-->
        <ul class="nav nav-pills nav-stacked side-navigation">
            <li>
                <h3 class="navigation-title">Menu</h3>
            </li>

            <li class="<?=($menuName =='app')? 'active': ''?>"><a href="{{route('app.index')}}" id="app-nav"><i class="fa fa-desktop "></i><span>App</span></a></li>
            <li class="menu-list <?=($menuName =='all_services')? 'active': ''?>"  >
                <a href="#"><i class="fa fa-gears"></i><span>Services</span></a>

                <ul class="child-list">
                    <li><a href="{{ route('services.index') }}" ><span class="fa fa-dot-circle-o">  All Services</span></a></li>
                    <li><a href="{{ route('services.create') }}"><span class="fa fa-dot-circle-o">  Add New</span></a></li>
                </ul>
            </li>
            <li class="<?=($menuName =='datatable')? 'active': ''?>"><a href="{{ url('datatable') }}" id="datable-nav"><i class="fa fa-database"></i><span>Data Tables</span></a></li>
            <li class="<?=($menuName =='devless_users')? 'active': ''?>"><a href="{{ url('devless_users') }}" id="users-nav"><i class="fa fa-users"></i><span>Users</span></a></li>
            <li class="<?=($menuName =='service_hub')? 'active': ''?>"><a href="{{ url('hub') }}" id="hub-nav"><i class="fa fa-cubes"></i><span>Service Hub</span></a></li>
            <li class="<?=($menuName =='privacy')? 'active': ''?>"><a href="{{ url('privacy') }}" id="privacy-nav"><i class="fa fa-lock"></i><span>Privacy</span></a></li>
            <li class="<?=($menuName =='api_docs')? 'active': ''?>"><a href="{{ url('console') }}" id="api-nav"><i class="fa fa-terminal"></i><span>API Console</span></a></li>
            <li class="<?=($menuName =='migration')? 'active': ''?>"><a href="{{route('migrate.index')}}" id="migration-nav"><i class="fa fa-download"></i><span>Migration</span></a></li>
            <li><a href="{{url('logout')}}"><i class="fa fa-sign-out"></i><span>Logout </span></a></li>
        </ul>
    </div>
</div>
<!-- body content start-->
<div class="body-content" style="min-height: 800px;">

    <!-- header section start-->

    <!-- sidebar left end-->
