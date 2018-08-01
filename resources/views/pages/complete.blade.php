@extends('layouts.page')
@section('content')

<div class="row">
    <div class="small-12 large-6 small-centered columns">
       
        <div class="callout secondary">
            <h3>{{ $message }}</h3>
        </div>
        
    </div>
</div>

<div class="row">
    <div class="small-12 large-6 small-centered columns">
       
        <a class="expanded button" href="/registration/create">Next Enrollee</a>
        
    </div>
</div>

<script>
    
    setTimeout(function () {
       window.location.href = "/registration/create"; //will redirect to your blog page (an ex: blog.html)
    }, 5000);
    
</script>

@stop