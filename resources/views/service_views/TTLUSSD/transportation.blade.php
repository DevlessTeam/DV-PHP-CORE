
<?php 
        use App\Helpers\DataStore; 
        use App\Http\Controllers\ServiceController as service; 
        $instance = DataStore::instanceInfo();
        $app  = $instance['app'];
?>
 <div class="dv-notify"></div>
<div class="dv-get-all:ttlussd:tractors">
<span class="var-engine"></span>
<span class="var-owners-phone"></span>
</div>
    <script src="{{URL('/')}}/js/devless-sdk.js" class="devless-connection" devless-con-token="<?= $app->token ?>"></script>

