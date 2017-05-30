@extends('layout')

@section('header')

<script src="{{ Request::secure(Request::root())."/js/jquery-1.10.2.min.js" }}"></script>

<!-- page head start-->
<div class="page-head">
    <h3>Users</h3>
    <button type="button" id="addbtn" class="btn btn-primary pull-right" style="position: relative; bottom: 23px;" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add User</button>
    <span class="sub-title">Users/</span>
</div>
<!-- page head end-->

@endsection

@section('content')
<br>
<div class="col-lg-12 col-md-12">
    <section class="panel">

        <table class="table table-striped table-bordered table-hover" id="users-table" width="100%"></table>
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add User</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success alert-dismissible" role="alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Success!</strong> User created.
                </div>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="username" class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="username" placeholder="Username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="first_name" class="col-sm-2 control-label">First Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="first_name" placeholder="First Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="last_name" class="col-sm-2 control-label">Last Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="last_name" placeholder="Last Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone_number" class="col-sm-2 control-label">Phone Number</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="+233245678192">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password" class="col-sm-2 control-label">Confirm Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save">Create User</button>
            </div>
            </div>
        </div>
        </div>
    </section>
</div>
<!-- DevLess JS SDK -->
<?php 
    
    use App\Helpers\DataStore; 
    use App\Http\Controllers\ServiceController as service; 
    $instance = DataStore::instanceInfo();
    $app  = $instance['app'];

?>
<script src="{{URL('/')}}/js/devless-sdk.js" class="devless-connection" devless-con-token="<?= $app->token ?>"></script>

<script>
    $(document).ready(function() {
        var dataSet = [];
        var Datatable;
        $.get('/retrieve_users', function(res) {
            console.log(res);
            for (i= 0; i < res.length; i++) {
                arr = [
                    res[i].id,
                    res[i].username,
                    res[i].first_name,
                    res[i].last_name,
                    res[i].phone_number,
                    res[i].email,
                    !res[i].status
                ];
                dataSet.push(arr);
            }

           Datatable = $('#users-table').DataTable({
                data: dataSet,
                columns:[
                    {"title": "Id"},
                    {"title": "Username"},
                    {"title": "First Name"},
                    {"title": "Last Name"},
                    {"title": "Phone Number"},
                    {"title": "Email"},
                    {"title": "Active"}
                ]
            });
        })

        $('#save').click(function() {
            var id = parseInt(Datatable.data()[0][0]) + 1;
            var username = $('#username').val()
            var phone_number = $('#phone_number').val();
            var email = $('#email').val();
            var fname = $('#first_name').val();
            var lname = $('#last_name').val();
            var password = $('#password').val();
            var con_password = $('#confirm_password').val();

            if (password === con_password) {
                SDK.call('devless', 'signUp', [email, password, username, phone_number, fname, lname], function(res) {
                    if(res.payload.result) {
                        Datatable.row.add([id, username, fname, lname, phone_number, email, 'true']).draw();
                        $('.alert').show();
                    } else {
                        alert('User creation failed');
                    }
                })
            }
        })
    });
</script>
@endsection
