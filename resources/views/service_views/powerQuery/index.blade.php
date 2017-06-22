@include("service_views/powerQuery/header")
<body class="dashboard-page">
    
    <nav class="main-menu">
        <ul>
            <li>
                <a href="#">
                    <i class="fa fa-home nav_icon"></i>
                </a>
            </li>
        </ul>
    </nav>
    <section class="wrapper scrollable">
        <section class="title-bar">
            <div class="logo">
                <h1>PowerQuery</h1>
            </div>
            <div class="header-right">
                <div class="profile_details_left">
                    <div class="header-right-left">
                        <!--notifications of menu start -->
                        <button class="btn btn-danger" data-toggle="modal" data-target="#myModal">Documentation</button>
                    </div>  
                    <div class="profile_details">       
                        <ul>
                            <li class="dropdown profile_details_drop">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <div class="profile_img">   
                                        <span class="prfil-img"><i class="fa fa-user" aria-hidden="true"></i></span> 
                                        <div class="clearfix"></div>    
                                    </div>  
                                </a>
                                <ul class="dropdown-menu drp-mnu">
                                    <li> <a href="{{Request::root()}}"><i class="fa fa-sign-out"></i> Logout</a> </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="clearfix"> </div>
                </div>
            </div>
            <div class="clearfix"> </div>
        </section>
        <div class="main-grid">

            <!-- Info -->
            <div class="alert alert-info" role="alert">
                <strong>Heads up!</strong> Click on a saved query to either update or remove it using the Editor on the right. Use the Add button
                to add new queries. Refer to the documentation 👆🏾for more info.
            </div>
            <!-- Info -->

            <!-- Main -->
            <div class="row">
                <div class="col-md-4" style="overflow: hidden;">
                    <p class="list-group-item-heading"><b>Saved Queries</b></p>
                    <?php $queries = DB::table('powerQuery_queries')->select('name', 'id')->get(); ?>
                    <div class="list-item pre-scrollable">
                        @if(count($queries) >= 1)
                            @foreach($queries as $query)
                                <a href="#" class="list-group-item" id="{{$query->id}}">{{$query->name}} <i class="fa fa-arrow-right pull-right"></i></a>
                            @endforeach
                        @else
                            <a href="#" class="list-group-item disabled" id="empty">No saved queries. </a>
                        @endif

                    </div>
                </div>
                <div class="col-md-7 col-md-offset-1">
                    <p class="list-group-item-heading"><b>Edit Query</b></p>
                    <form action="#" class="form-horizontal">
                        <div class="form-group">
                            <input type="text" class="form-control1" name="query_name" placeholder="Query Name" style="border-radius: 4px;" required>
                            <input type="hidden" name="id"/>
                        </div>
                    </form>
                    <div class="row">
                            <div id="editor" style="margin-top: 10px;"></div>
                    </div>
                    <div class="row">
                        <hr>
                        <button class="btn btn-primary" style="background-color: #5B479F; border-color: #5B479F;" id="exec_btn">Execute</button>
                        <button class="btn btn-primary" id="update_save">Save/Update</button>
                        <button class="btn btn-info" style="background-color: #FF5D25; border-color: #FF5D25;" id="clear_btn">Clear Editor</button>
                        <button class="btn btn-warning" style="background-color: #EB3E28; border-color: #EB3E28;" id="delete_btn">Delete</button>
                        <hr>
                    </div>
                    <div class="row">
                        <textarea name="response" id="response" cols="30" rows="10" readonly></textarea>
                    </div>
                </div>
            </div>
            <!-- End Main -->

            <!-- Alert Modal -->
                <div class="modal fade" tabindex="-1" data-modal-color="" role="dialog" id="alertModal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content col-md-offset-3" style="width:250px;height:3%;">
                            <div class="modal-body"></div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            <!-- End Alert -->
        
            <!-- Modal -->
            <div class="modal fade " id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Documentation</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <p style="color: #FDAE2D;">Quick guide to get you started on how to use the module. All examples are illustrated using the JavaScript SDK
                                </p>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Method</th>
                                        <th>Usage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>getAllQueries</td>
                                        <td>
                                            Get all saved power queries. <br>
                                            <code class="prettyprint">
                                                SDK.call('powerQuery', 'getAllQueries', [], function(res) {
                                                    console.log(res);
                                                })
                                            </code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>addQuery</td>
                                        <td>Save a new query for later use.
                                        This will store a new query <i>{"table":["$table"],"get":[]}</i> which translates to <i>table($table)->get()</i> for getting all users with the name <i>getUsers</i>. <br>
                                            <code class="prettyprint">
                                                SDK.call('powerQuery', 'addQuery', ['getUsers',{"table":["$table"],"get":[]}], function(res){console.log(res);})
                                            </code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>execStoreQuery</td>
                                        <td>
                                            Execture a stored power query. <br>
                                            <code class="prettyprint">
                                                SDK.call('powerQuery', 'execStoredQuery', ['getUsers', {"table":"users"}], function(res){console.log(res);})
                                            </code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>execQuery</td>
                                        <td>
                                            Provide a query to be run. <br>
                                            <code class="prettyprint">
                                                SDK.call('powerQuery', 'execQuery', [{"table":["$table"],"get":[]}], function(res){console.log(res);})
                                            </code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>editStoredQuery</td>
                                        <td>
                                            Edit a store query. <br>
                                            <code class="prettyprint">
                                                SDK.call('powerQuery', 'editStoredQuery', ['getUsers',{"table":["users"],"get":[]}], function(res){console.log(res);})
                                            </code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>getQuery</td>
                                        <td>
                                            Get a particular query. <br>
                                            <code class="prettyprint">
                                                SDK.call('powerQuery', 'getQuery', ['getUsers'], function(resp){console.log(resp);})
                                            </code>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal end -->

        </div>
@include('service_views/powerQuery/footer')

