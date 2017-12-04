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

@include('scripts')

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

