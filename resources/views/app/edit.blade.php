@extends('layout')

@section('header')
   
<!-- page head start-->
    <div class="page-head">
        <h3>
            App 
        </h3>
        <span class="sub-title">App/</span>

    </div>
    <!-- page head end-->
@endsection

@section('content')
    @include('error')

   ` <!--body wrapper start-->
    <div class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                         App Panel
                    </header>
                    <div class="panel-body">
                         <form action="{{ route('app.update') }}" class="form-horizontal" method="POST">
                            <div class="form-group @if($errors->has('username')) has-error @endif" >
                                <label for="username" class="col-lg-2 col-sm-2 control-label">Username</label>
                                <div class="col-lg-10">
                                    <input type="text" id="username-field" name="username" class="form-control" value="{{ $user->username }}" required="">
                                      @if($errors->has("username"))
                                        <span class="help-block">{{ $errors->first("username") }}</span>
                                      @endif
                                </div>
                            </div>
                            <div class="form-group @if($errors->has('email')) has-error @endif" >
                                <label for="email" class="col-lg-2 col-sm-2 control-label">Email</label>
                                <div class="col-lg-10">
                                    <input type="email" id="email-field" name="email" class="form-control" value="{{ $user->email }}" required="">
                                      @if($errors->has("email"))
                                        <span class="help-block">{{ $errors->first("email") }}</span>
                                      @endif
                                </div>
                            </div>
                            <div class="form-group @if($errors->has('password')) has-error @endif" >
                                <label for="password" class="col-lg-2 col-sm-2 control-label">Password</label>
                                <div class="col-lg-10">
                                    <input type="password" id="password-field" name="password" class="form-control" required="">
                                      @if($errors->has("password"))
                                        <span class="help-block">{{ $errors->first("password") }}</span>
                                      @endif
                                </div>
                            </div>
                            <div class="form-group @if($errors->has('password_confirmation')) has-error @endif" >
                                <label for="password_confirmation" class="col-lg-2 col-sm-2 control-label">Password Confirmation</label>
                                <div class="col-lg-10">
                                    <input type="password" id="password_confirmation-field" name="password_confirmation" class="form-control" required="">
                                      @if($errors->has("password_confirmation"))
                                        <span class="help-block">{{ $errors->first("password_confirmation") }}</span>
                                      @endif
                                </div>
                            </div>
                            <div class="form-group @if($errors->has('name')) has-error @endif" >
                                <label for="name" class="col-lg-2 col-sm-2 control-label">App Name</label>
                                <div class="col-lg-10">
                                    <input type="text" id="name-field" name="name" class="form-control" value="{{ $app->name }}" required="">
                                      @if($errors->has("name"))
                                        <span class="help-block">{{ $errors->first("name") }}</span>
                                      @endif
                                </div>
                            </div>
                            <div class="form-group @if($errors->has('name')) has-error @endif">
                                <label for="description-field" class="col-lg-2 col-sm-2 control-label">Description</label>
                                <div class="col-lg-10">
                                    <textarea class="form-control" id="description-field" rows="3" name="description">{{ $app->description }}</textarea>
                                     @if($errors->has("description"))
                                        <span class="help-block">{{ $errors->first("description") }}</span>
                                     @endif
                                </div>
                            </div>
                             <div class="form-group @if($errors->has('app-key')) has-error @endif">
                                <label for="app-key" class="col-lg-2 col-sm-2 control-label"  >App Key</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" id="app-key" value='{{$_SERVER['SERVER_NAME']}}' placeholder="app key">
                                    @if($errors->has("name"))
                                        <span class="help-block">{{ $errors->first("api-key") }}</span>
                                      @endif
                                </div>
                            </div>
                            <div class="form-group @if($errors->has('token')) has-error @endif">
                                <label for="token-field" class="col-lg-2 col-sm-2 control-label">Token</label>
                                <div class="col-lg-10">
                                    <div class="input-group m-b-10" >
                                        <input type="text" id="token" readonly value="{{$app->token}}" class="form-control">
                                        <span class="input-group-btn">
                                             <input type="hidden" name="_method" value="PUT">
                                             <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                             <button class="btn btn-white" onclick="update_token()" type="button">Regenerate Token</button>
                                        </span>
                                    </div>
                                    @if($errors->has("token"))
                                           <span class="help-block">{{ $errors->first("token") }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <button type="submit" class="btn btn-info pull-right">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!--body wrapper end-->
    <script>
    function generate_token(){
            var settings = {
          "async": true,
          "crossDomain": true,
          "url": "{{route('app.update')}}",
          "method": "PUT",
          "headers": {
            "content-type": "application/json",
            "cache-control": "no-cache",
          },
          "processData": false,
          "data": "{\"action\":\"regen\"}"
        }

        $.ajax(settings).done(function (response) {
          console.log(response);
          $result = JSON.parse(response)
          if($result.status_code == 622){
              $("#token").val($result.payload.new_token);
          }
          else{
              
              alert("token could not be updated");
          }
        });
    }
    function update_token(message){
        if(confirm("Do you want to update token")){
            generate_token();
        }else{
            return false;
        }
    }
    </script>
@endsection