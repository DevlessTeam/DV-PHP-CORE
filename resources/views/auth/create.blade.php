@include('header')
    <style media="screen">
        @media screen and (min-width: 768px) {
            #imgtop {
                position: fixed;
                left: 0;
            }

            section .panel {
                top: 50px;
            }
        }

        @media screen and (min-width: 768px) and (max-width: 991px) {
            section .panel {
                top: 50px;
            }
        }

        @media screen and (min-width: 993px) and (max-width: 1199px) {
            section .panel {
                left: -200px;
            }
        }
    </style>
  <section>
    @include('error')

    <!--body wrapper start-->
    <div class="wrapper">
      <div class="row">
        <a href="https://devless.io">
          <img src="{{ asset('/img/logo.png') }}" class="setup-logo" alt="Devless">
        </a><br><br>
        <div class="col-lg-6 col-md-offset-3 col-sm-10 col-sm-offset-1">
          <section class="panel">
            <header class="panel-heading">
              Setup
            </header>
            <div class="panel-body">
              <form action="{{ url('/setup') }}" class="form-horizontal" method="POST">
                    <input type="hidden" id="username-field" value="Add username here" name="username" class="form-control" required="">
                <div class="form-group @if($errors->has('email')) has-error @endif" >
                  <label for="email" class="col-lg-2 control-label">Email<font color="red">*</font></label>
                  <div class="col-lg-10">
                    <input type="email" id="email-field" name="email" class="form-control" required="">
                    @if($errors->has("email"))
                      <span class="help-block">{{ $errors->first("email") }}</span>
                    @endif
                  </div>
                </div>

                <div class="form-group @if($errors->has('password')) has-error @endif">
                  <label for="password" class="col-lg-2 control-label"  >Password<font color="red">*</font></label>
                  <div class="col-lg-10">
                    <input type="password" class="form-control" name="password" required="">
                    @if($errors->has("password"))
                      <span class="help-block">{{ $errors->first("password") }}</span>
                    @endif
                  </div>
                </div>

                <div class="form-group @if($errors->has('password_confirmation')) has-error @endif">
                  <label for="password_confirmation" class="col-lg-2 control-label"  >Confirm Password<font color="red">*</font></label>
                  <div class="col-lg-10">
                    <input type="password" class="form-control" name="password_confirmation" required="">
                    @if($errors->has("password_confirmation"))
                      <span class="help-block">{{ $errors->first("password_confirmation") }}</span>
                    @endif
                  </div>
                </div>

                    <input type="hidden" id="app_name-field" name="app_name" value="Set app name" class="form-control" required="">
                    
                        <textarea class="form-control" id="app_description-field"  rows="3" type="hidden" name="app_description" style="display: none;">Add description here</textarea>
                
                    <input type="hidden" id="app_token-field" name="app_token" class="form-control" readonly value="{{$app['app_token']}}">
                    <button type="submit" class="btn btn-info " style="margin-left: 80%" onclick="registerUser()" >Create App </button><br><br>
              </form>
            </div>
          </section>
        </div>
      </div>
    </div>
