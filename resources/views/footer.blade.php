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
<script>
	var beamer_config = {
		selector : "beamer-notification", //REPLACE: Your element Id to position the alert
		product_id : "dmMoreFcnull", //DO NOT CHANGE: This is your product code on Beamer
		//top: 0, /*Optionabeamer-notificationl: Top position offset for the notification bubble*/
		//right: 0, /*Optional: Right position offset for the notification bubble*/
		//language: 'EN', /*Optional: Bring news in the language of choice*/
		//filter: 'admin', /*Optional : Bring the news for a certain role as well as all the public news*/
		//lazy: false, /*Optional : true if you want to manually start the script by calling Beamer.init()*/
		//alert : true, /*Optional : false if you don't want to initialize the selector*/
		//callback : your_callback_function, /*Optional : Beamer will call this function, with the number of new features as a parameter, after the initialization*/
		
		//---------------Visitor Information---------------
		//user_firstname : "firstname", /*Optional : input your user firstname for better statistics*/
		//user_lastname : "lastname", /*Optional : input your user lastname for better statistics*/
		//user_email : "email", /*Optional : input your user email for better statistics*/
	};
</script>
<script type="text/javascript" src="https://app.getbeamer.com/js/beamer-embed.js"></script>
					
</body>

</html>

