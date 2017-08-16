<!--footer section start-->
<footer>
    <script src="https://cdn.smooch.io/smooch.min.js"></script>
<script>
Smooch.init({ appToken: '9wokwlxqcy4n953mn3l2zz9y7' });
</script>
    <?php echo date('Y'); ?> &copy; Devless.

</footer>
<!--footer section end-->



</div>
<!-- body content end-->
</section>

<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ asset('/js/jquery-1.10.2.min.js') }}"></script>
<script src="{{ asset('/js/jquery-migrate.js') }}"></script>
<script src="{{ asset('/js/bootstrap.min.js') }}"></script>
<!--notification pan-->
<script src="{{ asset('/js/modernizr.min.js') }}"></script>

<!-- datatable -->
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>

<!--Nice Scroll-->
<script src="{{ asset('/js/jquery.nicescroll.js') }}" type="text/javascript"></script>

<!--right slidebar-->
<script src="{{ asset('/js/slidebars.min.js') }}"></script>

{{--  <!--switchery-->
<script src="{{ asset('/js/switchery/switchery.min.js') }}"></script>
<script src="{{ asset('/js/switchery/switchery-init.js') }}"></script>  --}}

{{--  <!--Sparkline Chart-->
<script src="{{ asset('/js/sparkline/jquery.sparkline.js') }}"></script>
<script src="{{ asset('/js/sparkline/sparkline-init.js') }}"></script>  --}}

<!--Form Validation-->
<script src="{{ asset('/js/bootstrap-validator.min.js') }}" type="text/javascript"></script>

<!--Form Wizard-->
<script src="{{ asset('/js/jquery.steps.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/jquery.validate.min.js') }}" type="text/javascript"></script>

<!--wizard initialization-->
<script src="{{ asset('/js/wizard-init.js') }}" type="text/javascript"></script>


<!--common scripts for all pages-->
<script src="{{ asset('/js/scripts.js') }}"></script>
<!-- Ace Editor -->
@if(\Request::path() != 'console')
<script src="{{ asset('/js/ace/ace.js') }}" type="text/javascript" ></script>
<script src="{{ asset('/js/ace/theme-github.js') }}" type="text/javascript" ></script>

<script src="{{ asset('/js/ace/mode-php.js') }}" type="text/javascript" ></script>
<script src="{{ asset('/js/ace/jquery-ace.min.js') }}" type="text/javascript" ></script>
@endif

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
function copyToBoard(text){
	prompt("Ctrl/CMD+c to copy", text);
}
</script>

@include('notifier')
</body>

</html>
	