        <!-- sidebar left start-->
        <div class="sidebar-left">
            <div class="header-section">

                <!--logo and logo icon start-->
                <div class="logo dark-logo-bg hidden-xs hidden-sm">
                    <a href="index.html">
                         <img src="/assets/img/logo.png" width="20" height="20" alt="Devless">
                        <!--<i class="fa fa-maxcdn"></i>-->
                        <span class="brand-name">Devless</span>
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
                <div class=" search-field">  </div>
                <!-- visible small devices end-->

                <!--sidebar nav start-->
                <ul class="nav nav-pills nav-stacked side-navigation">
                    <li>
                        <h3 class="navigation-title">Menu</h3>
                    </li>
                    <li><a href="{{route('app.index')}}"><span>App</span></a></li>
                    <li class="menu-list">
                        <a href="#"><span>Services</span></a>
                        <ul class="child-list">
                            <li><a href="{{ route('services.index') }}">All Services</a></li>
                            <li><a href="{{ route('services.create') }}">Add New</a></li>
                        </ul>
                    </li>

                    <li><a href="#"><span>Data Tables</span></a></li>


                    <li class="menu-list">
                        <a href="#"><span>API Console</span></a>
                        <ul class="child-list">
                            <li><a href="{{ url('console/privacy') }}">Privacy</a></li>
                            <li><a href="{{ url('console') }}">Console</a></li>
                        </ul>
                    </li>
                    {{-- <li><a href="{{ url('console') }}"><span>Api Docs</span></a></li> --}}


                    <li><a href="#"><span>Import/Export Services</span></a></li>

                </ul>
            </div>
        </div>
         <!-- body content start-->
        <div class="body-content" style="min-height: 800px;">

            <!-- header section start-->

        <!-- sidebar left end-->
