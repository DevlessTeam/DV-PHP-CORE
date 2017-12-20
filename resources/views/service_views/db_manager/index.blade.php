<?php
 $configs = config()['database'];
 
 $driver = $configs['default'];
 $username = $configs['connections'][$driver]['username'];
 $password = $configs['connections'][$driver]['password'];
 $server = $configs['connections'][$driver]['host'];
 $database = $configs['connections'][$driver]['database'];

?>
<script type="text/javascript">
	alert("You will be redirected to the DB interface please just hit `login` to get access to your database, or change the connection details to use another database");
</script>

<?=DvRedirect(url('/')."/service/db_manager/view/db/?username=$username&password=$password&db=$database&server=$server&driver=$driver
", 0);?>
