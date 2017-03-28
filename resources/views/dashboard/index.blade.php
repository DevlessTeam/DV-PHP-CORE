@extends('layout')

@section('header')

<style type="text/css">
	.page-head {
		min-height: 75px;
	}
	.page-head h3 {
		font-size: 32px;
	}
	.bdr-gray {
		border: 1px solid #f4f5f5;
		padding: 30px 0;
	}
	.bdr-gray i {
		font-size: 50px;
	}
	.bdr-gray h4 {
		font-size: 20px
	}
	.dash-tuts {
		margin-top: 30px;
	}
</style>
<!-- page head start-->
<div class="page-head">
	<div class="col-md-8">
		<h3>Welcome to Devless</h3>
	</div>
	<div class="col-md-4">
		<button class="btn btn-info addon-btn pull-right"><i class="fa fa-plug pull-right"></i>Connect to My App</button>
	</div>
</div>
<!-- page head end-->

@endsection

@section('content')

<!--body wrapper start-->
<div class="wrapper">
	<!--state overview end-->

	<div class="row">
		<div class="col-md-9">
			<section class="panel">
				<div class="panel-body">
					<h3>New to Devless?</h3>
					<p>Learn the key concepts of DevLess by following our 5-minute step-by-step tutorial. You can choose between building a simple blog or an e-commerce storefront. We provide templates to make this super easy.</p>
					<div class="row dash-tuts">
						<div class="col-md-4">
							<div class="bdr-gray">
								<div class="text-center"><i class="fa fa-tag"></i></div>
								<h4 class="text-center">Build a Blog</h3>
								</div>
							</div>
							<div class="col-md-4">
								<div class="bdr-gray">
									<div class="text-center"><i class="fa fa-tag"></i></div>
									<h4 class="text-center">Build a Blog</h3>
									</div>
								</div>
								<div class="col-md-4">
									<div class="bdr-gray">
										<div class="text-center"><i class="fa fa-tag"></i></div>
										<h4 class="text-center">Build a Blog</h3>
										</div>
									</div>
								</div>
							</div>
						</section>

						<section class="panel" id="block-panel">
							<div class="panel-body">
								Content Comes Here
							</div>
						</section>
					</div>
					<div class="col-md-3">
						<section class="panel" style="min-height: 450px;">
							<div class="panel-body">

							</div>
						</section>
					</div>
				</div>
			</div><!--body wrapper end-->

			@endsection



