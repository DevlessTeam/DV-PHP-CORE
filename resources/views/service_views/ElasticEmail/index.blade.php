<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>ElasticEmail Module Documentation</title>

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
          <a class="text-muted" href="/service/ElasticEmail/view/index">
            <span class="logo">ElasticEmail Docs</span>
          </a>
        </div>
        <div class="col-4 d-flex justify-content-end align-items-center">
          <a class="btn btn-sm btn-outline-secondary" href="/">
            <i class="fas fa-sign-out-alt"></i> Back to DevLess</a>
        </div>
      </div>
    </header>
    <div class="row">
      <div class="col-md-6">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addConfig">
          <i class="fas fa-plus-square"></i> Add Config</button>
      </div>
    </div>

    <div class="row my-4">
      <!-- fetch config-->
      <div class="col-md-8 order-md-1">
        <h5 class="text-muted mt-2">Configuration</h5>
        <p class="text-secondary" style="font-size: 14px;">List all configs.</p>
        <div class="bg-white p-2 rounded">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">Entity ID</th>
                <th scope="col">API Key</th>
                <th scope="col">Options</th>
              </tr>
            </thead>
            <tbody class="dv-get-all:ElasticEmail:config">
              <tr>
                <td class="var-entity_id"></td>
                <td class="var-api_key"></td>
                <td>
                  <button class="dv-update btn btn-secondary btn-sm" data-toggle="modal" data-target="#updateConfig">
                    <i class="fas fa-pencil-alt"></i>
                  </button>
                  <button class="dv-delete btn btn-danger btn-sm">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!-- fetch config-->

      <!-- documentation -->
      <div class="col-md-4 order-md-2" style="max-height: 100vh;">
        <h5 class="text-muted mt-2">Documentation</h5>
        <p class="text-secondary" style="font-size: 14px;">Follow the steps listed below to setup.</p>
        <ol class="text-secondary">
          <li>
            <a href="https://elasticemail.com">https://elasticemail.com</a> and sign up for your account.</li>
          <li>Go to Settings and configure your domain (ElasticEmail)</li>
          <li>After successfully domain configuration, go to SMTP/API and grab your API Key</li>
          <li>Click on Add Config and configure the module. (ElasticEmail Module - DevLess) </li>
          <li>Done.</li>
        </ol>
        <p class="text-muted" style="font-weight: 600;">How to use ElasticEmail</p>
        <div class="jumbotron">
          <pre>
  ->run('ElasticEmail', 'send', [
    $entity_id,
    $to,
    $template_name, 
    $payload
  ])
</pre>
<p class="text-muted">
    <b>$entity_id</b> => Identifier for config</p>
<p class="text-muted">
    <b>$to</b> => Recipient email. Can be an array for multiple emails</p>
<p class="text-muted">
    <b>$template_name</b> => Name of the saved template in ElasticEmail</p>
<p class="text-muted">
    <b>$payload</b> => Email body</p>
        </div>
      </div>
      <!-- documentation -->
    </div>

    <!-- add config -->
    <div class="modal fade" id="addConfig" tabindex="-1" role="dialog" aria-labelledby="addConfig" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add Config</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="dv-notify-success">
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Ahoy!</strong> Config added successfully
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
            <div class="dv-notify-failed">
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Holy guacamole!</strong> Config add failed!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
            <form action="#" class="dv-add-oneto:ElasticEmail:config">
              <div class="form-group">
                <label for="entity_id">Entity ID</label>
                <input type="text" class="form-control" id="entity_id" name="entity_id" aria-describedby="entity_idHelp" placeholder="Entity ID (Identifier)"
                  required>
                <small id="entity_idHelp" class="form-text text-muted">Must be unique and can't contain any whitespaces.</small>
              </div>
              <div class="form-group">
                <label for="api_key">Description of webhook</label>
                <textarea type="text" class="form-control" id="api_key" name="api_key" aria-describedby="api_key" placeholder="API Key from ElasticEmail"
                  required></textarea>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
          </form>
        </div>
      </div>
    </div>
    <!-- add config -->
    <!-- Update config Modal -->
    <div class="modal fade" id="updateConfig" tabindex="-1" role="dialog" aria-labelledby="updateConfig" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="updateConfigLabel">Update Webhook</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="dv-notify-success">
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Ahoy!</strong> Config updated successfully
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
            <div class="dv-notify-failed">
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Holy guacamole!</strong> Config update failed!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
            <form action="#" class="dv-update-oneof:ElasticEmail:config">
              <div class="form-group">
                <label for="entity_id">Entity ID</label>
                <input type="text" class="form-control" id="entity_id" name="entity_id" aria-describedby="entity_id" placeholder="Entity ID (Identifier)"
                  required>
                <small id="entity_id" class="form-text text-muted">Must be unique and can't contain any whitespaces.</small>
              </div>
              <div class="form-group">
                <label for="api_key">API Key</label>
                <textarea type="text" class="form-control" id="api_key" name="api_key" aria-describedby="api_keyHelp" placeholder="API Key from ElasticEmail"
                  required></textarea>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
          </form>
        </div>
      </div>
    </div>
    <!-- update config modal -->
  </div>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>
  <script src="/js/devless-sdk.js" class="devless-connection" devless-con-token=""></script>
  <script>
    devlessCallbacks((res) => {
      if (res.status_code === 609 || res.status_code === 619) {
        location.reload();
      }
    });
  </script>
</body>

</html>