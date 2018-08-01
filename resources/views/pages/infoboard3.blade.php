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
        <li>// Good luck as you begin your future career with B&FC //</li>
        
        <li>// Congratulations on choosing one of England’s leading general further 
                education colleges //</li>
        
        <li>// Did you know that as a student at B&FC you can get loads of discounts 
               with the NUS Card //</li>
        
        <li>// Your Students’ Union is here to represent you and make your time at 
                B&FC as interesting and enjoyable as possible //</li>
        
        <li>// Students are entitled to monthly membership of our Inspirations 
                gym for just £10. Visit the Sports Centre at Bispham for details //</li>
        
        <li>// The Hair and Beauty Project is an on-site salon at Bispham offering 
                a range of treatments. Call in for a list of treatments and prices //</li>
        
        <li>// Staff in our Loop learning resource centres are happy to answer 
               any questions about resources to support your study //</li>
         
        <li>// B&FC refectories offer a range of delicious hot and cold meals 
                with plenty of healthy options available //</li>
         
        <li>// Enhance your employment skills with site visits, sports teams 
                and extra-curricular activities to make you stand out //</li>
         
        <li>// Special occasion or long lunch break? Dine in our Level 6 restaurant 
                and enjoy cuisine cooked and served by B&FC students //</li>
         
        <li>// Our careers team can work with you to help you progress to your 
                dream career //</li>
         
        <li>// Your time at B&FC will fly by – be sure to make the most of every 
                opportunity and enjoy learning new skills //</li>
    </ul>
    </div>
  
</div>

@stop