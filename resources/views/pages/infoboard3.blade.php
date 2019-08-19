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

<div class="row" data-equalize id="stats-info-eq" >
    <div class="small-12 large-4 columns text-center" data-equalizer-watch >
        <span class="ticker">Current Time : <span id="clock"></span></span>
    </div>
    <div class="small-12 large-4 columns text-center" data-equalizer-watch >
        
        <span class="ticker">Predicted Wait Time : <span id="avgWaitTimeIndicator" > </span>m</span>
    </div>
    <div class="small-12 large-4 columns text-center" data-equalizer-watch >
        <span class="ticker"> Avg. Enrol Time : <span id="avgEnrolTimeIndicator" > </span>m</span>
    </div>
</div>

<script >

    $(document).ready(function() {
        doInfoBoardStats();     
    });

</script>

<div class="row">
    
    <div class='columns large-3'>
        
        <div class="callout primary info-callout text-center qHeader">
             18 and Under Enrolment
        </div>
        
        <div id='q1'></div>
        <script >

            $(document).ready(function() {
                renderInfoTable( 'feed_queue_16_to_18', '#q1',1,0 );     
            });


        </script>
    
    </div>
    
    <div class='columns large-3'>
        
        <div class="callout primary text-center qHeader" >
            19+ Enrolment
        </div>
        
        <div id='q2'></div>
        <script >

            $(document).ready(function() {
                renderInfoTable( 'feed_queue_19_plus', '#q2',1,0 );     
            });


        </script>
    
    </div>
    
    <div class='columns large-3'>
        
        <div class="callout primary text-center qHeader">
            Missed Appointments
        </div>
    
        <div id='q3'></div>
        <script >

            $(document).ready(function() {
                renderInfoTable( 'feed_queue_missed_appointments', '#q3',1,0 );     
            });


        </script>
    
    </div>
    
    <div class='columns large-3'>
        
        <div class="callout primary text-center qHeader">
            Ready To Enrol
        </div>
    
        <div id='q4' class="callouts"></div>
        <script >

            $(document).ready(function() {

                renderInfoTable( 'feed_callouts', '#q4' );     
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

            doRollingMessages('feed_queue_19_plus');
        });

    </script>
  
</div>

@stop