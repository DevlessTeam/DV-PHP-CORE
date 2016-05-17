@include('header')
            <!--footer section start-->
            <footer>
                2016 &copy; Devless.
               
            </footer>
            <!--footer section end-->



        </div>
        <!-- body content end-->
    </section>

<!-- Placed js at the end of the document so the pages load faster -->
<script src='/assets/js/jquery-1.10.2.min.js'>
<script src="assets/js/jquery-1.10.2.min.js"></script>
<script src="/assets/js/jquery-migrate.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<!--notification pan-->
<script src="/assets/js/modernizr.min.js"></script>

<!--Nice Scroll-->
<script src="/assets/js/jquery.nicescroll.js" type="text/javascript"></script>

<!--right slidebar-->
<script src="/assets/js/slidebars.min.js"></script>

<!--switchery-->
<script src="/assets/js/switchery/switchery.min.js"></script>
<script src="/assets/js/switchery/switchery-init.js"></script>

<!--Sparkline Chart-->
<script src="/assets/js/sparkline/jquery.sparkline.js"></script>
<script src="/assets/js/sparkline/sparkline-init.js"></script>

<!--Form Validation-->
<script src="/assets/js/bootstrap-validator.min.js" type="text/javascript"></script>

<!--Form Wizard-->
<script src="/assets/js/jquery.steps.min.js" type="text/javascript"></script>
<script src="/assets/js/jquery.validate.min.js" type="text/javascript"></script>

<!--wizard initialization-->
<script src="/assets/js/wizard-init.js" type="text/javascript"></script>

<!-- Ace Editor -->
<script src="https://ace.c9.io/build/src/ace.js"></script>

<!--common scripts for all pages-->
<script src="/assets/js/scripts.js"></script>
@include('notifier')
<script type="text/javascript">
    var editor = ace.edit("editor");
    editor.resize();
    editor.getSession().setUseWrapMode(true);
</script>
</body>
</html>
