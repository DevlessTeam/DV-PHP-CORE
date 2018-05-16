<?php 
DvInclude($payload, 'ActionClass.php') ;
$service = new $payload['service_name']();
$methods = $service->help();
$serviceNames = $service->getServices();
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$selectedService =  (isset($_GET['service_name']))?$_GET['service_name']:'';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>DevLess</title>

        <!-- Fonts -->
        <script  src="https://unpkg.com/monaco-editor@0.10.1/min/vs/loader.js"></script>
        <link rel="stylesheet" type="text/css" href="<?=DvAssetPath($payload, 'css/style.css')?>">
    </head>
    <body>
    <div class="side-bar">
    <p class="sidebar-title">DevLess Test Suit</p>
    <div class="hr-bar"></div>
    <div class="menu">
    <?php if($selectedService ==''){$selectedService = $serviceNames[0]->name;} ?>
      @foreach($serviceNames as $eachService)
        <a class="menu-item {{($selectedService == $eachService->name)? 'active-item':''}}" href="?service_name={{$eachService->name}}">{{$eachService->name}}</a>
      @endforeach
         </div>
    </div>
    <div class="header">DevLess Test Suit</div>
    <div class="main">
      <div class="row">
        <div class="col-4"><div class="card">Number of Tests: <span id="nt"> 0 <span></div></div>
        <div class="col-4"><div class="card">Number of Assertions: <span id="na"> 0 <span></div></div>
        <div class="col-4"><div class="card">Passed Tests: <span class="passed" id="pt">0</span></div></div>
        <div class="col-4"><div class="card">Failed Tests: <span class="failed" id="ft">0</span></div></div>
        </div>
      <div class="row">
        <div class="col-1">
          <div class="card">
          <div id="message"></div>
           <div id="container" style="width:100%;height:600px;border:1px solid grey"></div>
           <button class="btn btn-st" id="st" onclick="save()">Save Test</button>
           <button class="btn btn-rt" id="rt" onclick="run()">Run Test</button>
          </div>
        </div>
      
      </div>
    </div>
    <?= DVJSSDK();?>
    <script>
  require.config({ paths: { 'vs': 'https://unpkg.com/monaco-editor@0.10.1/min/vs/' }});

  require(['vs/editor/editor.main'], function() {
    window.editor = monaco.editor.create(document.getElementById('container'), {
      value: `<?php $service->getScript($selectedService);?>`,
      language: 'javascript'
    });
  });

function save() {
  var code = window.editor.getValue();
  var serviceName = "<?=$selectedService?>";
  document.getElementById("st").textContent = "Saving...";
  SDK.call('testSuit', 'save', [serviceName, code], function(resp){
     if(resp.payload.result.status_code == 609) {
       document.getElementById("message").textContent = '';        
       document.getElementById("st").textContent = "Save Test";
     }
     else if (resp.payload.result.status_code == 619) {
       document.getElementById("message").textContent = ''; 
       document.getElementById("st").textContent = "Save Test";
     }
     else {
        document.getElementById("st").textContent = "Failed :(";
        document.getElementById("message").textContent = resp.payload.result; 
     }
  })
}
function run() {
  document.getElementById('nt').textContent = 0;
  document.getElementById('na').textContent = 0;
  document.getElementById('pt').textContent = 0;
  document.getElementById('ft').textContent = 0;
  document.getElementById("rt").textContent = "Running ...";    
  var code = window.editor.getValue();
  var serviceName = "<?=$selectedService?>";
  var assert = function($name, $stmt) {
      var testNum = document.getElementById('nt').textContent;
      var assertionNum = document.getElementById('na').textContent;
      var assertionNum = parseInt(assertionNum);
      assertionNum++;
      document.getElementById('na').textContent = assertionNum;

     if($stmt) {
      var passedNum = document.getElementById('pt').textContent;
      var passedNum = parseInt(passedNum);
      passedNum++; 
       document.getElementById('pt').textContent = passedNum;
     } else {
      var failedNum = document.getElementById('ft').textContent;
      var failedNum = parseInt(failedNum);
      failedNum++; 
      document.getElementById('ft').textContent = failedNum;
      alert($name+' failed to assert to true');
     }
  }
  eval('var testSuit = {};'+code);
  var tests = (Object.getOwnPropertyNames(testSuit).filter(function (p) {
    return typeof testSuit[p] === 'function';
  }));
  document.getElementById('nt').textContent = tests.length;
  tests.forEach(function(test){
     testSuit[test]();
  
  })
  setTimeout(fuction(){
    document.getElementById("rt").textContent = "Run Test";    
  },1000);
}
//key binding
document.addEventListener("keydown", function(e) {
    if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
        e.preventDefault();
        save();
    }
    if (e.keyCode == 69 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
        e.preventDefault();
        run();
    }
});
</script>
    </body>
</html>