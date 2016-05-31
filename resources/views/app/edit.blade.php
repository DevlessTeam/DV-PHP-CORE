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
                            <div class="form-group @if($errors->has('name')) has-error @endif" >
                                <label for="name" class="col-lg-2 col-sm-2 control-label">App Name</label>
                                <div class="col-lg-10">
                                    <input type="text" id="name-field" name="name" class="form-control" value="{{ $app->name }}">
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