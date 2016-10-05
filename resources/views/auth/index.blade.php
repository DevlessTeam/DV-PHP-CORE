@include('header')
@if(Session::has('error'))
<div id="flash_msg" class="modal fade col-md-offset-3" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" style="width:250px;height:3%;">
            <div class="modal-body text-center" style="color: #fff; background-color: #EA7878;">
                {{Session::get('error')}}
            </div>
        </div>
    </div>
</div>
<script src="{{url('/js/jquery-1.10.2.min.js')}}"></script>
<script src="{{url('/js/bootstrap.min.js')}}"></script>
<script charset="utf-8">
        $(function modal() {
            $('#flash_msg').modal({
                keyboard: true
            });
            $('.modal-backdrop').removeClass("modal-backdrop");
            modalHide();
        });

        function modalHide() {
            setTimeout(function(){
                $('#flash_msg').modal('hide');
            }, 3000);
        }
</script>
@endif

  <section>
    @include('error')
    <!--body wrapper start-->
    <div class="wrapper">
      <div class="row">
        <a href="https://devless.io">
          <img src="{{url('/img/logo.png')}}" class="login-logo" alt="Devless">
        </a>
          <center>
        <div class="col-lg-6 col-md-6 " style="margin-left:24%;margin-right:24%;">
          <section class="panel">
            <header class="panel-heading">
              Login
            </header>
            <div class="panel-body">
              <form action="{{ url('/login') }}" class="form-horizontal" method="POST">
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
                  <label for="app-key" class="col-lg-2 col-sm-2 control-label"  >Password</label>
                  <div class="col-lg-10">
                    <input type="password" class="form-control" name="password" required="">
                    @if($errors->has("password"))
                      <span class="help-block">{{ $errors->first("password") }}</span>
                    @endif
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-lg-offset-2 col-lg-10">
                    <button type="submit" class="btn btn-info pull-right">Login</button>
                  </div>
                </div>
              </form>
            </div>
          </section>
        </div>
      </center>
      </div>
    </div>

    @if(session::has('error'))
    {{session()->forget('error')}}
    @endif
