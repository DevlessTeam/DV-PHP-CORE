<div style="padding: 20px; background-color: #D9D5DC;">
  <h3>Confirming payment. Please wait whiles the process complete.</h3>
  <h4>This window will close when the process is complete</h4>
  <?php
  require_once('ActionClass.php');
  $payToken = $_REQUEST['pay_token'];
  
  if(isset($payToken) && $payToken !== "") {
    $dv = new Slydepay();
    if($dv->check($payToken)->success) {
      echo "<script>window.close();</script>";
      echo "<p>Process complete. You can close the window and head back to the main application.</p>
        <p><a href='https://devless.io'>A DevLess hack</a></p>";
    } else {
      echo "An error occurred please contact administrator immediately about your payment";
    }
  }
  ?>
  </div>