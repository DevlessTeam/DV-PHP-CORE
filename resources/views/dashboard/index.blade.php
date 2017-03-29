@extends('layout2')

@section('content')

{{-- Page title --}}
<div class="page-title">
	<div class="title_left">
		<h3>Welcome to Devless</h3>
	</div>

	<div class="title_right">
		<div class="col-md-5 col-sm-5 col-xs-12 pull-right title-btn">
			<button class="btn btn-primary addon-btn pull-right" data-toggle="modal" data-target=".bs-example-modal-lg"><i class="fa fa-plug"></i>Connect to Application</button>
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
				Content goes here
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
				<h4 class="modal-title" id="myModalLabel">Connect to Application</h4>
			</div>
			<div class="modal-body">
				<p>Copy and Paste the script below and paste into your web application app</p>
				{{-- Todo: add app token --}}
				<pre class="prettyprint syntaxhighlight lines brush: xml" id="script">
					<!-- Add this script to your Application -->
					<script src='{{URL::to("/")}}/js/devless-sdk.js' class='devless-connection' devless-con-token='{{$app->token}}'></script>
				</pre>
				<button type="button" class="btn btn-primary" id="copyBtn" onclick="copyBtn()">Copy to Clipboard</button>
			</div>

		</div>
	</div>
</div>
{{-- End Content --}}

<script type="text/javascript">

	/* Copy text to Clipboard */
	var copyBtn = function() {

		var el;

		var text = document.querySelector('.code')
				.querySelector('.number2').innerText;

		el = document.createElement("input");

		el.setAttribute("value", text);

		$('.modal-body').append(el);

		el.select();

		document.execCommand("copy", true);

		$('input').remove();

	};
</script>
@endsection

