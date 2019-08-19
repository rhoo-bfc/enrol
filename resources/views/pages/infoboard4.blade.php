@extends('layouts.page')
@section('content')

<script>
    window.rotateViews = true;
</script>

<style>
    .row {
        max-width: 95%;
    }
    
    #headerRow {
        display:none;
    }    
    
    
    
</style>


<div class="row " id="infoboard4">
    
    <div class='columns large-7'>
        
        <div class="callout primary info-callout text-center qHeader qHeader2">
             Now Serving
        </div>
        
        <div id='q1' class="callouts"></div>
        <script >

            $(document).ready(function() {

                renderInfoTable( 'feed_callouts', '#q1',1,0,17 );     
            });


        </script>
    
    </div>
    <!--
    <div class='columns large-2'>
        
        <div class="rows ">
            
            <div class='columns large-12'>
        
            <img src="/img/bfc_full_colour_lg.jpg">
            
            </div>
            
            <div class='columns large-12'>
        
            <span class="ticker text-center tickerMargin">Current Waiting Time <span id="avgWaitTimeIndicator" > </span>mins</span>
            
            </div>
            
            <div class='columns large-12'>
        
           <span class="ticker text-center tickerMargin"> Predicted Enrol Time : <span id="avgEnrolTimeIndicator" > </span>mins</span>      
            </div>
            
        </div>
        
        <script >

            $(document).ready(function() {
                doInfoBoardStats();     
            });

        </script>
    </div>
    -->
    
    <div class='columns large-5'>
        
        <div class="callout primary info-callout text-center qHeader qHeader2">
            <span data-title="#q2" >Next Up 18 and Under Enrolment</span>
        </div>
        
        <div id='q2'></div>
        <script >

            $(document).ready(function() {
                renderInfoTable( 'feed_queue_16_to_18', '#q2',1,0,15 );     
            });


        </script>
    
    </div>
    
</div>

<div class="row">

    <div class="columns small-12">
    <ul class="marquee" id="feed-ticker">
    </ul>
    </div>


    <script >

        $(document).ready(function() {

            doRollingMessages('feed_queue_16_to_18');
        });

    </script>
  
</div>

@stop