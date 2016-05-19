@include('header')
  <section>
    @include('error')

    <!--body wrapper start-->
    <div class="wrapper">
      <div class="row">
        <div class="col-lg-6 col-md-offset-3">
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
                    <button type="submit" class="btn btn-primary pull-right">Login</button>
                  </div>
                </div>
              </form>
            </div>
          </section>
        </div>
      </div>
    </div>
@include('footer')
