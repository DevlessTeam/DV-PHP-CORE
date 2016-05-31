@include('header')

  <section>
    @include('error')

    <!--body wrapper start-->
    <div class="wrapper">
      <div class="row">
        <a href="https://devless.io">
          <img src="{{url('/img/logo.png')}}" height="50" alt="Devless">
          <span class="brand-name" style="font-size: 50px; color: #fff; position: relative; top: 10px;">Devless</span>
        </a>
        <div class="col-lg-6 col-md-offset-3" style="position: fixed; top:10%;">
          <section class="panel">
            <header class="panel-heading">
              Getting Started
            </header>
            <div class="panel-body">
              <form action="{{ url('/setup') }}" class="form-horizontal" method="POST">
                <div class="form-group @if($errors->has('name')) has-error @endif" >
                  <label for="name" class="col-lg-2 col-sm-2 control-label">User    name</label>
                  <div class="col-lg-10">
                    <input type="text" id="name-field" name="username" class="form-control" required="">
                    @if($errors->has("name"))
                      <span class="help-block">{{ $errors->first("name") }}</span>
                    @endif
                  </div>
                </div>
                <div class="form-group @if($errors->has('email')) has-error @endif" >
                  <label for="email" class="col-lg-2 col-sm-2 control-label">Email</label>
                  <div class="col-lg-10">
                    <input type="email" id="email-field" name="email" class="form-control" required="">
                    @if($errors->has("email"))
                      <span class="help-block">{{ $errors->first("email") }}</span>
                    @endif
                  </div>
                </div>

                <div class="form-group @if($errors->has('password')) has-error @endif">
                  <label for="password" class="col-lg-2 col-sm-2 control-label"  >Password</label>
                  <div class="col-lg-10">
                    <input type="password" class="form-control" name="password" required="">
                    @if($errors->has("password"))
                      <span class="help-block">{{ $errors->first("password") }}</span>
                    @endif
                  </div>
                </div>

                <div class="form-group @if($errors->has('password_confirmation')) has-error @endif">
                  <label for="password_confirmation" class="col-lg-2 col-sm-2 control-label"  >Confirm Password</label>
                  <div class="col-lg-10">
                    <input type="password" class="form-control" name="password_confirmation" required="">
                    @if($errors->has("password_confirmation"))
                      <span class="help-block">{{ $errors->first("password_confirmation") }}</span>
                    @endif
                  </div>
                </div>

                <div class="form-group @if($errors->has('app_name')) has-error @endif" >
                  <label for="app_name" class="col-lg-2 col-sm-2 control-label">App Name</label>
                  <div class="col-lg-10">
                    <input type="text" id="app_name-field" name="app_name" class="form-control" required="">
                    @if($errors->has("app_name"))
                      <span class="help-block">{{ $errors->first("app_name") }}</span>
                    @endif
                  </div>
                </div>
                <div class="form-group @if($errors->has('app_description')) has-error @endif">
                    <label for="app_description" class="col-lg-2 col-sm-2 control-label">App Description</label>
                    <div class="col-lg-10">
                        <textarea class="form-control" id="app_description-field" rows="3" name="app_description"></textarea>
                         @if($errors->has("app_description"))
                            <span class="help-block">{{ $errors->first("app_description") }}</span>
                         @endif
                    </div>
                </div>

                <div class="form-group @if($errors->has('app_key')) has-error @endif" >
                  <label for="app_key" class="col-lg-2 col-sm-2 control-label">App Key</label>
                  <div class="col-lg-10">
                    <input type="text" id="app_key-field" name="app_key" class="form-control" required="" value="{{$app['app_key']}}">
                    @if($errors->has("app_key"))
                      <span class="help-block">{{ $errors->first("app_key") }}</span>
                    @endif
                  </div>
                </div>
                <div class="form-group @if($errors->has('app_token')) has-error @endif" >
                  <label for="app_token" class="col-lg-2 col-sm-2 control-label">App Token</label>
                  <div class="col-lg-10">
                    <input type="text" id="app_token-field" name="app_token" class="form-control" readonly="" value="{{$app['app_token']}}">
                    @if($errors->has("app_token"))
                      <span class="help-block">{{ $errors->first("app_token") }}</span>
                    @endif
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-lg-offset-2 col-lg-10">
                    <button type="submit" class="btn btn-info pull-right">Create App</button>
                  </div>
                </div>
              </form>
            </div>
          </section>
        </div>
      </div>
    </div>
@include('footer')
