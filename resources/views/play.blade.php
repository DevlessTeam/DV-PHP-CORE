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
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="service">Service</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<select id="service" name="service" class="form-control">
								<option disabled selected value> -- select a right -- </option>
								<option value="0">PRIVATE</option>
								<option value="1">PUBLIC </option>
								<option value="2">AUTHENTICATE </option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="query">Query Access</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<select id="query" name="query" class="form-control">
								<option disabled selected value> -- select a right -- </option>
								<option value="0">PRIVATE</option>
								<option value="1">PUBLIC </option>
								<option value="2">AUTHENTICATE </option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="create">Create Access</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<select id="create" name="create" class="form-control">
								<option disabled selected value> -- select a right -- </option>
								<option value="0">PRIVATE</option>
								<option value="1">PUBLIC </option>
								<option value="2">AUTHENTICATE </option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="update">Update Access</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<select id="update" name="update" class="form-control">
								<option disabled selected value> -- select a right -- </option>
								<option value="0">PRIVATE</option>
								<option value="1">PUBLIC </option>
								<option value="2">AUTHENTICATE </option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="delete">Delete Access</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<select id="delete" name="delete" class="form-control">
								<option disabled selected value> -- select a right -- </option>
								<option value="0">PRIVATE</option>
								<option value="1">PUBLIC </option>
								<option value="2">AUTHENTICATE </option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="schema">Schema Access</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<select id="schema" name="schema" class="form-control">
								<option disabled selected value> -- select a right -- </option>
								<option value="0">PRIVATE</option>
								<option value="1">PUBLIC </option>
								<option value="2">AUTHENTICATE </option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="view">Service Views Access</label>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<select id="view" name="view" class="form-control">
								<option disabled selected value> -- select a right -- </option>
								<option value="0">PRIVATE</option>
								<option value="1">PUBLIC </option>
								<option value="2">AUTHENTICATE </option>
							</select>
						</div>
					</div>
					<div class="ln_solid"></div>
					<div class="form-group">
						<div class="text-center col-md-4 col-sm-4 col-xs-12 col-md-offset-4 col-sm-offset-4">
							<button class="btn btn-primary" type="button">Cancel</button>
							<button type="submit" class="btn btn-success">Save Changes</button>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>
{{-- Content end --}}