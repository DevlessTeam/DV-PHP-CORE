				<!-- /page content wrap continues -->
				</div>
			</div>
			<!-- /page content wrap ends -->

			<!-- footer content -->
			<footer>
				<div class="pull-right">
					DevLess - {{config('devless')['version']}}
				</div>
				<div class="clearfix"></div>
			</footer>
			<!-- /footer content -->
		</div>
	</div>

	<script src="{{ Request::secure(Request::root()).'/js/jquery-1.10.2.min.js' }}"></script>
	<script src="{{ Request::secure(Request::root()).'/js/bootstrap.min.js' }}"></script>
	<!-- FastClick -->
	<script src="{{ Request::secure(Request::root()).'/js/fastclick.js' }}"></script>
	<!-- NProgress -->
	<script src="{{ Request::secure(Request::root()).'/js/nprogress.js' }}"></script>
	<!-- Custom Theme Scripts -->
	<script src="{{ Request::secure(Request::root()).'/js/gent-custom.min.js' }}"></script>

	<script src="{{ Request::secure(Request::root()).'/js/shCore.js' }}"></script>
	<script src="{{ Request::secure(Request::root()).'/js/shBrushXml.js' }}"></script>
	<script type="text/javascript">SyntaxHighlighter.all();</script>
</body>
</html>
