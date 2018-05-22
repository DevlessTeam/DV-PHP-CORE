<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Slydepay Payments Documentation</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="<?=DvAssetPath($payload, 'css/style.css')?>">
  <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl"
    crossorigin="anonymous"></script>
</head>

<body class="bg-light">
  <div class="container">
    <header class="blog-header py-3">
      <div class="row flex-nowrap justify-content-between align-items-center">
        <div class="col-4 pt-1">
          <a class="text-muted" href="/service/SlackWebhook/view/index">
            <span class="logo">Slydepay Integration Docs</span>
          </a>
        </div>
        <div class="col-4 d-flex justify-content-end align-items-center">
          <a class="btn btn-sm btn-outline-secondary" href="/">
            <i class="fas fa-sign-out-alt"></i> Back to DevLess</a>
        </div>
      </div>
    </header>
    <div class="container">

      {{--
      <div class="row"> --}}
        <div class="jumbotron">
          <h3>How to Setup Slydepay Module</h3>
          <p>Follow the steps below to successfully setup your integration</p>
          <hr class="my-1">
          <ul>
            <li>
              <b>Step 1:</b> Get your merchant by signing up via the
              <a href="https://slydepay.com.gh" target="_blank">Slydepay official website.</a>
            </li>
            <li>
              <b>Step 2:</b> Head to
              <i>PAYLIVE</i> under the settings tab and grab your API Key. Don't forget to fill the other fields. The Callback
              URL is
              <span class="text-primary">{{URL()}}/service/Slydepay/view/callback</span>
            </li>
            <li>
              <b>Step 3:</b> Go to the Data Tables tab in DevLess and under the Slydepay service, select config for table.</li>
            <li>
              <b>Step 4:</b> Fill the fields with the appropriate information.
              <ol>
                <li>entity_id => Represents an identifier for each account integration (isRequired)</li>
                <li>merchant_key => Represents the API Key from Slydepay (isRequired)</li>
                <li>email_or_phone => Represents the email or phone number used to sign up for the Slydepay account (isRequired)</li>
                <li>description => Purpose of the integration. (isOptional)</li>
              </ol>
            </li>
          </ul>
          <hr class="my-2">
          <div>
            <h6>=> To initialize the payment process use</h6>
            <pre class="my-2">
->run('Slydepay', 'pay', [$entity_id, $order_id, $items])
</pre>
            <p>
              <b>$entity_id</b> => Represents the identifier for the payment. Should be the same as one on the config (isRequired)</p>
            <p>
              <b>$order_id</b> => Unique identifier for order batches (isRequired)</p>
            <p>
              <b>$items</b> => Represents order items (isRequired and should be an array).
              <code>
                [ { "id": "unique id", "name": "name of product", "quantity": "quantity of product", "unit_price": "price of product" } ]
              </code>
            </p>
            <p>Returns
              <code>payUrl</code> and
              <code>payToken</code>. Sample Response:
              <code>{ "payToken": "9031a756-59eb-4968-a753-6ee1c719edf0", "payUrl": "https://app.slydepay.com/paylive/detailsnew.aspx?pay_token=9031a756-59eb-4968-a753-6ee1c719edf0"
                }</code>
            </p>
          </div>
          <div>
            <h6>=> To check for payment status</h6>
            <pre>
->run('Slydepay', 'check', [$token])
<p><b>$token</b> => Represents the payment token from received from Slydepay on the callback. Also it can be found on records in the orders table in Slydepay module</p>
<p>Returns <code>{"sucess": "true", "result": "COMPLETED", "errorMessage": null, "errorCode": null }</code> </p>
<p>Refer to the <a href="http://doc.slydepay.com/" target="_blank">Slydepay official documentation</a> for other response types.</p>
</pre>
          </div>
          <div>
            <h6>=> To confirm transactions</h6>
<pre>
->run('Slydepay', 'confirm', [$order_id])
<p><b>$order_id</b> => Represents the order batch used when initializing the payment process</p>
<p>Returns <code>{ "success": true, "result": "CONFIRMED", "errorMessage": null, "errorCode": null }</code></p>
<p>Refer to the <a href="http://doc.slydepay.com/" target="_blank">Slydepay official documentation</a> for other response types.</p>
</pre>
          </div>
          <p>
            <b>Note:</b> Refer to the
            <a href="https://devless.gitbook.io/devless-docs-1-3-0/interacting-with-devless/http-api#rpc-calls"
              target="_blank">docs on how to make RPC calls to DevLess</a>
            if you want to initiate, check or confirm payment from the frontend</p>
        </div>
      </div>
      {{-- </div> --}}
  </div>

</body>

</html>