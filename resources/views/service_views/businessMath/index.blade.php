<?php 
DvInclude($payload, 'ActionClass.php') ;
$service = new $payload['service_name']();
$methods = $service->help();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>DevLess</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?=DvAssetPath($payload, 'css/style.css')?>">
    </head>
    <body>
    <div class="center-title"><h1>{{$payload['service_name']}}  Docs</h1></div>
    <div class="center-title"><b> The {{$payload['service_name']}} service  provides a list of methods you can call within the Rules section of each service or even try using them via the <a href="https://devless.gitbooks.io/devless-docs-1-3-0/content/sdks.html">SDKs</a></b><p>Example usage in Service Rules <code>->UsingService('{{$payload['service_name']}}')->callMethod('help')->withoutParams()->storeAs($output)->stopAndOutput(1000, 'list out help methods', $output)</code></p></div>
    <div class="push-down"></div>
     <table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>Callable methods</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
  <?php $count = 0; ?>
  @foreach ($methods[2] as $method => $description)
    
    <tr>
      <th scope="row">{{$count}}</th>
      <td><b>{{$method}}</b></td>
      <td><code>{{$description}}</code></td>
    </tr>
  <?php $count++; ?>
  @endforeach
    
  </tbody>
</table>
    </body>
</html>