@extends('layout2')

@section('content')

{{-- Page title --}}
<div class="page-title">
	<div class="title_left">
		<h3>Welcome to Devless</h3>
	</div>

	<div class="title_right">
		<div class="col-md-5 col-sm-5 col-xs-12 pull-right title-btn">
			<button class="btn btn-primary addon-btn pull-right" data-toggle="modal" data-target=".bs-example-modal-lg"><i class="fa fa-plug"></i>Connect to My App</button>
		</div>
	</div>
</div>
{{-- Page title end --}}

<div class="clearfix"></div>

{{-- Content begins --}}
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Dashboard</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				Add content to the page ...
			</div>
		</div>
	</div>
</div>

{{-- Modal --}}
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Modal title</h4>
			</div>
			<div class="modal-body">
				<h4>Text in a modal</h4>
				<p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
				<p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>

		</div>
	</div>
</div>
{{-- End Content --}}
@endsection