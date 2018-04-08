<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Slack Webhook</title>

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
            <span class="logo">Slack Webhook</span>
          </a>
        </div>
        <div class="col-4 d-flex justify-content-end align-items-center">
          <a class="btn btn-sm btn-outline-secondary" href="/services"><i class="fas fa-sign-out-alt"></i> Back to DevLess</a>
        </div>
      </div>
    </header>
    <div class="row">
      <div class="col-md-6">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addWebhook"><i class="fas fa-plus-square"></i> Add Webhook</button>
      </div>
    </div>
    <div class="row my-4">
      <div class="col-md-8 order-md-1">
        <h5 class="text-muted mt-2">Configuration</h5>
        <p class="text-secondary" style="font-size: 14px;">List all webhook configs.</p>
        <div class="bg-white p-2 rounded">
          <table class="table table-responsive table-striped table-bordered">
            <thead>
              <tr>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Webhook URL</th>
                <th scope="col">Options</th>
              </tr>
            </thead>
            <tbody>
              <tr class="dv-get-all:SlackWebhook:config">
                <td class="var-name"></td>
                <td class="var-description"></td>
                <td class="var-webhook_url" style="word-break: break-all;" </td>
                  <td>
                    <button class="dv-update btn btn-secondary btn-sm" data-toggle="modal" data-target="#updateWebhook">
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
      <div class="col-md-4 order-md-2" style="max-height: 100vh;">
        <h5 class="text-muted mt-2">Documentation</h5>
        <p class="text-secondary" style="font-size: 14px;">Follow the steps listed below to setup.</p>
        <ol class="text-secondary">
          <li>Go to
            <a href="https://api.slack.com">https://api.slack.com</a> and sign in your account.</li>
          <li>Create a Slack App
            <ul>
              <li>Choose an app name</li>
              <li>Select your preferred development workspace. Workspace translate to the slack team you want to forward messages.
              </li>
              <li>Click on
                <code>Create App</code> when done</li>
            </ul>
          </li>
          <li>Select <b>Incoming Webhooks</b> from the list options under Add features and functionality</li>
          <li>Activate Incoming Webhooks</li>
          <li>Click on <code>Add New Webhook to Workspace</code> at the bottom of the page</li>
          <li>Select the channel or member to post messages to and <code>Authorize</code></li>
          <li>Almost there! Copy the Webhook URL and complete the form</li>
        </ol>
        <p class="text-muted" style="font-weight: 600;">How to use SlackWebhook</p>
        <div class="jumbotron">
<pre>
->run('SlackWebhook', 'send', [
  $name_of_webhook,
  $message
])
</pre>
<p class="text-muted"><b>$name_of_webhook</b> => Name of the webhook</p>
<p class="text-muted"><b>$message</b> => Message to post to channel. Refer to <a href=" https://api.slack.com/docs/messages/builder">
  Slack API Message Builder</a> to compose your message</p>
        </div>
      </div>
    </div>
    <!-- Add Webhook Modal -->
    <div class="modal fade" id="addWebhook" tabindex="-1" role="dialog" aria-labelledby="addWebhook" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Webhook</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="dv-notify-success">
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Ahoy!</strong> Webhook added successfully
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
            <div class="dv-notify-failed">
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Holy guacamole!</strong> Webhook add failed!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
            <form action="#" class="dv-add-oneto:SlackWebhook:config">
              <div class="form-group">
                <label for="name">Name of webhook</label>
                <input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp" placeholder="new_signups" required>
                <small id="nameHelp" class="form-text text-muted">The name must be unique and can't contain any whitespaces.</small>
              </div>
              <div class="form-group">
                <label for="description">Description of webhook</label>
                <textarea type="text" class="form-control" id="description" name="description" aria-describedby="descriptionHelp" placeholder="Collect sign up details to marketing channel"
                  required></textarea>
              </div>
              <div class="form-group">
                <label for="webhook">Webhook URL</label>
                <input type="text" class="form-control" id="webhook" name="webhook_url" aria-describedby="webhookHelp" placeholder="https://hooks.slack.com/services/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"
                  required>
                <small id="webhookHelp" class="form-text text-muted">Refer to the documentation on how to obtain the URL.</small>
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
     <!-- Update Webhook Modal -->
     <div class="modal fade" id="updateWebhook" tabindex="-1" role="dialog" aria-labelledby="updateWebhook" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="updateWebhookLabel">Update Webhook</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="dv-notify-success">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Ahoy!</strong> Webhook updated successfully
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              </div>
              <div class="dv-notify-failed">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Holy guacamole!</strong> Webhook update failed!
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              </div>
              <form action="#" class="dv-update-oneof:SlackWebhook:config">
                <div class="form-group">
                  <label for="name">Name of webhook</label>
                  <input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp" placeholder="new_signups" required>
                  <small id="nameHelp" class="form-text text-muted">The name must be unique and can't contain any whitespaces.</small>
                </div>
                <div class="form-group">
                  <label for="description">Description of webhook</label>
                  <textarea type="text" class="form-control" id="description" name="description" aria-describedby="descriptionHelp" placeholder="Collect sign up details to marketing channel"
                    required></textarea>
                </div>
                <div class="form-group">
                  <label for="webhook">Webhook URL</label>
                  <input type="text" class="form-control" id="webhook" name="webhook_url" aria-describedby="webhookHelp" placeholder="https://hooks.slack.com/services/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"
                    required>
                  <small id="webhookHelp" class="form-text text-muted">Refer to the documentation on how to obtain the URL.</small>
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
  </div>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>
  <script src="/js/devless-sdk.js" class="devless-connection" devless-con-token="ca9859341da887230a17b2cafd6a93bd"></script>
  <script>
    devlessCallbacks((res) => {
      if (res.status_code === 609 || res.status_code === 619) {
        location.reload();
      }
    });
  </script>
</body>

</html>