@extends('layout')

@section('header')

<script src="{{ Request::secure(Request::root())."/js/jquery-1.10.2.min.js" }}"></script>

<!-- page head start-->
<div class="page-head">
    <h3>Users</h3>
    <div class="pull-right" style="position: relative; bottom: 23px;">
        <button type="button" id="addbtn" class="btn btn-primary"  data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add User</button>
        <button type="button" id="settingsbtn" class="btn btn-info"  data-toggle="modal" data-target="#settings"><i class="fa fa-cog"></i> Settings</button>
        <button type="button" class="btn btn-danger" id="delete-users"><i class="fa fa-ban"></i> Delete User(s)</button>
    </div>
    <span class="sub-title">Users/</span>
</div>
<!-- page head end-->

@endsection

@section('content')
<br>
<div class="col-lg-12 col-md-12">
    <section class="panel">

        <table class="table table-striped table-bordered table-hover" id="users-table" width="100%">
            <thead>
                <tr>
                    <th><input type="checkbox" name="select_all" value="1" id="select-all"></th>
                    <th>Id</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Active</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td></td>
                        <td>{{$user->id}}</td>
                        <td>{{$user->username}}</td>
                        <td>{{$user->first_name}}</td>
                        <td>{{$user->last_name}}</td>
                        <td>{{$user->phone_number}}</td>
                        <td>{{$user->email}}</td>
                        <td>
                            @if($user->status == 0)
                                true
                            @else
                                false
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Add User</b></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success alert-dismissible" role="alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Success!</strong> User created.
                </div>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="username" class="col-sm-3 control-label"><b>Username</b></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="username" placeholder="Username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="first_name" class="col-sm-3 control-label"><b>First Name</b></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="first_name" placeholder="First Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="last_name" class="col-sm-3 control-label"><b>Last Name</b></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="last_name" placeholder="Last Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-sm-3 control-label"><b>Email</b></label>
                        <div class="col-sm-9">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone_number" class="col-sm-3 control-label"><b>Phone Number</b></label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="+233245678192">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-3 control-label"><b>Password</b></label>
                        <div class="col-sm-9">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password" class="col-sm-3 control-label"><b>Confirm Password</b></label>
                        <div class="col-sm-9">
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
        <!-- Modal end -->

        <!-- Modal update -->
        <div class="modal fade" id="udModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>Update User</b></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success alert-dismissible" role="alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Success!</strong> User info updated.
                </div>
                <form class="form-horizontal" id="updateForm" action="">
                    <div class="form-group">
                        <label for="username" class="col-sm-3 control-label"><b>Username</b></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="username" id="username" placeholder="Username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="first_name" class="col-sm-3 control-label"><b>First Name</b></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="last_name" class="col-sm-3 control-label"><b>Last Name</b></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-sm-3 control-label"><b>Email</b></label>
                        <div class="col-sm-9">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone_number" class="col-sm-3 control-label"><b>Phone Number</b></label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="+233245678192">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-3 control-label"><b>Password</b></label>
                        <div class="col-sm-9">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password" class="col-sm-3 control-label"><b>Confirm Password</b></label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password" class="col-sm-3 control-label"><b>Active</b></label>
                        <div class="col-sm-9">
                            <input type="checkbox" class="form-control" name="active" id="active">
                        </div>
                    </div>
            </div>
                <div class="modal-footer">
                    <button type="sumbit" class="btn btn-primary" id="update">Update User</button>
                </div>
            </form>
            </div>
        </div>
        </div>
        <!-- Modal end update-->

        <!-- Modal begin settings -->
        <div class="modal fade" id="settings" tabindex="-1" role="dialog" aria-labelledby="settingsLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"><b>Settings</b></h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Notice!</strong> Settings you change here affect how users authenticate with your DevLess instance.
                        </div>
                        <div id="settings-notif" style="display:none" class="alert alert-warning text-center">
                            
                        </div>
                            <div class="form-group">
                                <label for="session" class="col-sm-5 control-label"><b>Hours till User is logged out</b></label>
                                <div class="col-sm-7">
                                    <input type="number" id="session_time" class="form-control" min="0">
                                </div>
                            </div><br>
                            <div class="form-group"><label for="Session Expiration" class="col-sm-5 control-label"><b>Keep User logged in forever </b></label>
                                <div class="col-sm-7">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="expire_session" name="expiration" />Yes
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-5 control-label" for="selfSignUp"><b>Allow Users to signup automatically</b></label>
                                <div class="col-sm-7">
                                    <div class="radio">
                                        <label><input type="radio" id="self_signup_true" name="s_signup">Enable</label>
                                        <label><input type="radio" checked id="self_signup_false" name="s_signup">Disable</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group"><label for="" class="col-sm-5 control-label pull-left"><b >Verify New Users via Email</b></label>
                                <div class="col-sm-7">
                                    <div class="radio"><label for="v_enable"><input type="radio" name="v_account" id="verify_email_true" >Enable</label><label for="v_disable"><input type="radio" checked id="verify_email_false"name="v_account">Disable</label></div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" onclick="authForm.save()">Save Settings</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal end settings -->
    </section>
</div>
<!-- DevLess JS SDK -->
<?php

    use App\Helpers\DataStore;
    $instance = DataStore::instanceInfo();
    $app  = $instance['app'];

?>
<script src="{{URL('/')}}/js/devless-sdk.js" class="devless-connection" devless-con-token="<?= $app->token ?>"></script>

<script src="{{URL('/')}}/js/framework/users.js"></script>
@endsection
