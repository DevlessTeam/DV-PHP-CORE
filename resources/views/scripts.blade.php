<!-- Placed js at the end of the document so the pages load faster -->
<script src="{{ Request::secure(Request::path()).'/js/jquery-1.10.2.min.js' }}"></script>
<script src="{{ Request::secure(Request::path()).'/js/jquery-migrate.js' }}"></script>
<script src="{{ Request::secure(Request::path()).'/js/bootstrap.min.js' }}"></script>
<!--notification pan-->
<script src="{{ Request::secure(Request::path()).'/js/modernizr.min.js' }}"></script>

<!-- datatable -->
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.5/js/dataTables.select.min.js"></script>

<!--Nice Scroll-->
<script src="{{ Request::secure(Request::path()).'/js/jquery.nicescroll.js' }}" type="text/javascript"></script>

<!--right slidebar-->
<script src="{{ Request::secure(Request::path()).'/js/slidebars.min.js' }}"></script>

<!--switchery-->
<script src="{{ Request::secure(Request::path()).'/js/switchery/switchery.min.js' }}"></script>
<script src="{{ Request::secure(Request::path()).'/js/switchery/switchery-init.js' }}"></script>

<!--Form Validation-->
<script src="{{ Request::secure(Request::path()).'/js/bootstrap-validator.min.js' }}" type="text/javascript"></script>

<!--Form Wizard-->
<script src="{{ Request::secure(Request::path()).'/js/jquery.steps.min.js' }}" type="text/javascript"></script>
<script src="{{ Request::secure(Request::path()).'/js/jquery.validate.min.js' }}" type="text/javascript"></script>

<!--common scripts for all pages-->
<script src="{{ Request::secure(Request::path()).'/js/scripts.js' }}"></script>
<!-- Ace Editor -->
@if(Request::path() != 'console')
  <script src="{{ Request::secure(Request::path()).'/js/ace/ace.js' }}" type="text/javascript" ></script>
  <script src="{{ Request::secure(Request::path()).'/js/ace/theme-github.js' }}" type="text/javascript" ></script>
  <script src="{{ Request::secure(Request::path()).'/js/ace/mode-php.js' }}" type="text/javascript" ></script>
  <script src="{{ Request::secure(Request::path()).'/js/ace/jquery-ace.min.js' }}" type="text/javascript" ></script>
@else
  <script src="{{ Request::secure(Request::path()).'/js/src-min-noconflict/ace.js' }}" type="text/javascript" charset="utf-8"></script>
  <script src="{{ Request::secure(Request::path()).'/js/framework/api-console.js' }}" type="text/javascript" charset="utf-8"></script>
@endif

<!-- Toastr -->
<script src="{{ Request::secure(Request::path()).'/js/toastr.js'}}" type="text/javascript"></script>

<!-- Chatra {literal} -->
<script>
    (function(d, w, c) {
        w.ChatraID = 'gkhabyT7T2KQndYsf';
        var s = d.createElement('script');
        w[c] = w[c] || function() {
            (w[c].q = w[c].q || []).push(arguments);
        };
        s.async = true;
        s.src = 'https://call.chatra.io/chatra.js';
        if (d.head) d.head.appendChild(s);
    })(document, window, 'Chatra');
</script>
<!-- /Chatra {/literal} -->