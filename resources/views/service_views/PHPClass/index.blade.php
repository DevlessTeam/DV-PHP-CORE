<?php 
DvInclude($payload, 'ActionClass.php') ;
$service = new $payload['service_name']();
?>

<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
</head>

<button class="btn btn-info pull-right" onclick="save()"><i class="far fa-save"></i> Save Code</button>
<h2>PHPClasses</h2>
<div style="color: green" class="text-center" id="message"></div>

<body>

<div id="container" style="width:100%;height:900px;border:1px solid grey"></div>

<?=DVJSSDK()?>

<script  src="https://unpkg.com/monaco-editor@0.10.1/min/vs/loader.js"></script>

<script>
  require.config({ paths: { 'vs': 'https://unpkg.com/monaco-editor@0.13.1/min/vs' }});
  require(['vs/editor/editor.main'], function() {
    window.editor = monaco.editor.create(document.getElementById('container'), {
      value:`<?=$service->getCode()?>`,
      language: 'php'
    });
  });

function save() {
  var code = window.editor.getValue();
  document.getElementById("message").style.color = "yellow";
  document.getElementById("message").textContent = "Saving...";
  SDK.call('PHPClass', 'save', [code], function(resp){
     if(resp.payload.result.status_code == 619) {
       document.getElementById("message").style.color = "green";
       document.getElementById("message").textContent = "Saved";
     }
     else if (resp.payload.result.status_code == 629) {
       document.getElementById("message").style.color = "green";
       document.getElementById("message").textContent = "No new changes";
     }else {
        document.getElementById("message").style.color = "red";
        document.getElementById("message").textContent = resp.payload.result; 
     }
  })
}

//save script
document.addEventListener("keydown", function(e) {
    if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
        e.preventDefault();
        save();
    }
});
</script>
</body>
</html>
