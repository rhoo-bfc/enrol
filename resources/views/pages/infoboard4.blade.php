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