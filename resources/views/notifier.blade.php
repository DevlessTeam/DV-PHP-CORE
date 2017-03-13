@if(Session::has('flash_message'))
<div class="modal fade" id="notif" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true" >
   <div class="modal-dialog" style="width:250px;height:3%;">
      <div class="modal-content" style="background-color:{{Session::get('color')}};">
        
         <div class="modal-body">
            <div id="left">
      <div>
          <center><p><font color="white">{{Session::get('flash_message')}}</font></p>
    </center>
   
         </div>
        
      </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>

   $(function () {

    $('#notif').modal({
      keyboard: true
   });
   $('.modal-backdrop').removeClass("modal-backdrop");
});

   function hide(){
   $('#notif').modal('hide');
   }
   setTimeout('hide()', 2000);
</script>
{{session()->forget('flash_message')}}
@endif
