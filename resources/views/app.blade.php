{{-- Page title & Title Right --}}
<div class="page-title">
	<div class="title_left">
		<h3>Welcome to Devless</h3>
	</div>

	<div class="title_right">
		<div class="col-md-5 col-sm-5 col-xs-12 pull-right title-btn">
			<button class="btn btn-primary addon-btn pull-right"><i class="fa fa-plug"></i>Connect to My App</button>
		</div>
	</div>
</div>
{{-- Page title end --}}

<div class="clearfix"></div>

{{-- Content --}}
{{-- <div class="row page-routeName"> --}} {{-- @Edmond --}}
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Form Design</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<br />
				<form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">

					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="username">Username<span class="required"> *</span>
						</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<input type="text" id="username" required="required" class="form-control col-md-7 col-xs-12">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="email">Email<span class="required"> *</span>
						</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<input type="email" id="email" required="required" class="form-control col-md-7 col-xs-12">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="appname">App Name<span class="required"> *</span>
						</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<input type="text" id="appname" required="required" class="form-control col-md-7 col-xs-12">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="token">Token<span class="required"> *</span>
						</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							{{-- <input type="text" id="token" readonly="readonly" value="Hello World" required="required" class="form-control col-md-7 col-xs-12"> --}}
							<div class="input-group">
	                            <input type="text" id="token" readonly="readonly" value="Hello World" required="required" class="form-control">
	                            <span class="input-group-btn">
	                            	<button type="button" class="btn btn-primary"><i class="fa fa-refresh"></i> Regenerate!</button>
	                            </span>
	                        </div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="password">Password<span class="required"> *</span>
						</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<input type="text" id="password" required="required" class="form-control col-md-7 col-xs-12">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="Password confirmation">Password Confirmation<span class="required"> *</span>
						</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<input type="text" id="Password" required="required" class="form-control col-md-7 col-xs-12">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="oldpassword">Old Password<span class="required"> *</span>
						</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<input type="text" id="oldpassword" required="required" class="form-control col-md-7 col-xs-12">
						</div>
					</div>
					<div class="ln_solid"></div>
					<div class="form-group">
						<div class="text-center col-md-4 col-sm-4 col-xs-12 col-md-offset-4 col-sm-offset-4">
							<button class="btn btn-success addon-btn" type="submit"><i class="fa fa-save"></i>Save Changes</button>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>
{{-- Content end --}}