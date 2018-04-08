@if(Session::has('flash_message')) {{--
<div class="modal fade" id="notif" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:250px;height:3%;">
    <div class="modal-content" style="background-color:{{Session::get('color')}};">

      <div class="modal-body">
        <div id="left">
          <div>
            <center>
              <p>
                <font color="white">{{Session::get('flash_message')}}</font>
              </p>
            </center>

          </div>

        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
  </div>
</div>
<script>
  $(function () {

    $('#notif').modal({
      keyboard: true
    });
    $('.modal-backdrop').removeClass("modal-backdrop");
  });

  function hide() {
    $('#notif').modal('hide');
  }
  setTimeout('hide()', 2000);
</script> --}}
<script>
  $(function () {
    toastr.options = {
      "closeButton": true,
      "debug": false,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "onclick": null,
      "showDuration": "2000",
      "hideDuration": "2000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }

    switch ("{{Session::get('color')}}") {
      case '#7BE454':
        toastr['success']("{{Session::get('flash_message')}}", "Success Alert")
        break;
    
      case '#736F6F':
        toastr['info']("{{Session::get('flash_message')}}", "Notification Alert")
        break;

      case '#F1D97A':
        toastr['warning']("{{Session::get('flash_message')}}", "Warning Alert")
        break;

      case '#EA7878':
        toastr['error']("{{Session::get('flash_message')}}", "Error Alert")
        break;
    }
  })
</script>
{{session()->forget('flash_message')}} @endif