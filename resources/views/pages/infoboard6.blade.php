@extends('layouts.page')
@section('content')

<style>
    .row {
        max-width: 90%;
    }
    
    #headerRow {
        display:none;
    }    
</style>

<script>
    window.fullViews = true;
    window.rotateViews = true;
</script>

<div class='columns large-12'>

    <div class="callout primary info-callout text-center qHeader qHeader2">
        <span data-title="#q1" ></span>
    </div>

</div>

<div class="row">
    
    <div class='columns large-6'>


        
        <div id='q1'></div>
        <script >

            $(document).ready(function() {
                renderInfoTable( 'feed_all_19_plus', '#q1',1,0,15 );
            });


        </script>
    
    </div>
    
    <div class='columns large-6'>


        <div id='q2'></div>
        <script >

            $(document).ready(function() {
                renderInfoTable( 'feed_all_19_plus', '#q2',1,15,15 );
            });


        </script>
    
    </div>

    
</div>

<div class="row">

    <div class='columns large-6'>

        <p style="font-weight:bold;">If you missed your managers appointment please goto the managers desk.</p>

    </div>

</div>

<div class="row">

    <div class="columns small-12">
    <ul class="marquee" id="feed-ticker">

    </ul>
    </div>

    <script >

        $(document).ready(function() {

            doRollingMessages('feed_queue_19_plus');
        });

    </script>

</div>

@stop