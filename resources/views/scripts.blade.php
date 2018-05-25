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

<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/5a2ea7e25d3202175d9b791f/default';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!--End of Tawk.to Script-->

<!-- Start of Fullstory -->
<script>
  window['_fs_debug'] = false;
  window['_fs_host'] = 'fullstory.com';
  window['_fs_org'] = 'CC949';
  window['_fs_namespace'] = 'FS';
  (function(m,n,e,t,l,o,g,y){
      if (e in m) {if(m.console && m.console.log) { m.console.log('FullStory namespace conflict. Please set window["_fs_namespace"].');} return;}
      g=m[e]=function(a,b){g.q?g.q.push([a,b]):g._api(a,b);};g.q=[];
      o=n.createElement(t);o.async=1;o.src='https://'+_fs_host+'/s/fs.js';
      y=n.getElementsByTagName(t)[0];y.parentNode.insertBefore(o,y);
      g.identify=function(i,v){g(l,{uid:i});if(v)g(l,v)};g.setUserVars=function(v){g(l,v)};
      g.shutdown=function(){g("rec",!1)};g.restart=function(){g("rec",!0)};
      g.consent=function(a){g("consent",!arguments.length||a)};
      g.identifyAccount=function(i,v){o='account';v=v||{};v.acctId=i;g(o,v)};
      g.clearUserCookie=function(){};
  })(window,document,window['_fs_namespace'],'script','user');
  </script>
<!-- End of Fullstory -->