@extends('layout2')

@section('content')

{{-- Page title & Title Right --}}
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

{{-- Content --}}
<div class="row">
	<div class="col-md-8 col-sm-8 col-xs-12">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>New to Devless?</h2>
						<ul class="nav navbar-right panel_toolbox">
							<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="#">Settings 1</a>
									</li>
									<li><a href="#">Settings 2</a>
									</li>
								</ul>
							</li>
							<li><a class="close-link"><i class="fa fa-close"></i></a>
							</li>
						</ul>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<p>Learn the key concepts of DevLess by following our 5-minute step-by-step tutorial. You can choose between building a simple blog or an e-commerce storefront. We provide templates to make this super easy.</p>
						<div class="row">
							<div class="col-md-4">
								<div class="tuts-div">
									<i class="fa fa-close"></i><h4>Build a Blog</h4>
								</div>
							</div>
							<div class="col-md-4">
								<div class="tuts-div">
									<i class="fa fa-close"></i><h4>Build a Blog</h4>
								</div>
							</div>
							<div class="col-md-4">
								<div class="tuts-div">
									<i class="fa fa-close"></i><h4>Build a Blog</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">

			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>Daily active users</h2>
						<ul class="nav navbar-right panel_toolbox">
							<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="#">Settings 1</a>
									</li>
									<li><a href="#">Settings 2</a>
									</li>
								</ul>
							</li>
							<li><a class="close-link"><i class="fa fa-close"></i></a>
							</li>
						</ul>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						Enter Content
					</div>
				</div>
			</div>

		</div>
	</div>
	<div class="col-md-4 col-sm-4 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Recent Activities</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#">Settings 1</a>
							</li>
							<li><a href="#">Settings 2</a>
							</li>
						</ul>
					</li>
					<li><a class="close-link"><i class="fa fa-close"></i></a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				Content Here
			</div>
		</div>
	</div>
</div>
{{-- Content end --}}

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

