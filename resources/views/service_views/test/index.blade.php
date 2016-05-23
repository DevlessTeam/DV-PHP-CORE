<center><h1><u>Welcome!</u></h1></center>
<p>Get started with Lean Views</p>
<p> Lean views provide an easy way to create simple management consoles for a service </p>

<p> You also have available properties of the service as well as parameters passed to it </p>
<p> You may also use the blade templating tool provided within laravel. We strongly advice you use a 
    frontend framework.
    @foreach($payload as $key => $value)
        <p>{{$key}} => {{$value}}</p>
      
    @endforeach

