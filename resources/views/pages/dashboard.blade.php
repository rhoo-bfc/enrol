@extends('layouts.page')
@section('content')


<!-- message template -->
<div class="success callout" id="messageTemplate" style="display:none;" data-closable="">
  <span data-message=""></span>
  <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<!-- message template -->



<div class="row">
    <div class="columns small-3">
        
        <!--
        <label>System Online :</label>
        <div class="switch">
          <input class="switch-input" id="system-online" type="checkbox" name="systemStatus" checked>
          <label class="switch-paddle" for="system-online">
            <span class="show-for-sr">System Status</span>
            <span class="switch-active" aria-hidden="true">Yes</span>
            <span class="switch-inactive" aria-hidden="true">No</span>
          </label>
        </div>
        -->
        
        <button class="secondary button " id="expireServiceDesks">Expire Service Desks</button>
    </div>
    
    <div class="columns small-9" id="dash-messages-container">
        
    </div>
</div>

<div class="row text-center dash-summary" data-equalizer id="test-eq">
  <div class="medium-3 columns">
    <div class="callout " data-equalizer-watch>
      <h5> Enrolled </h5>
      <p>
          <span id="enrolCount">0</span>
          <span id="enrolCountIndicator" data-current="0" ></span> 
      </p>
    </div>
  </div>
  <div class="medium-3 columns">
    <div class="callout" data-equalizer-watch>
      <h5> Unable to Enrol </h5>
      <p>
          <span id="failedEnrolCount">0</span> 
          <span id="failedEnrolCountIndicator" data-current="0" ></span> 
      </p>
    </div>
  </div>
  <div class="medium-3 columns">
    <div class="callout" data-equalizer-watch>
     <h5> Predicted Wait Time </h5>
      <p>
          <span id="avgWaitTime">0</span>
          <i id="avgWaitTimeIndicator" data-current="0" class="fi-arrow-down large"></i> 
      </p>
    </div>
  </div>
  <div class="medium-3 columns">
    <div class="callout" data-equalizer-watch>
      <h5> Avg. Enrol Time </h5>
      <p>
          <span id="avgEnrolTime">0</span>
          <i id="avgEnrolTimeIndicator" data-current="0" class="fi-arrow-down large"></i>
      </p>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() { 
       doEnrolStats();
    });
</script>

<div class="row">
    <div class="small-12 large-12 small-centered columns">
       
        <ul class="tabs" data-tabs id="dashboard-tabs">
            <li class="tabs-title is-active"><a href="#panel1" aria-selected="true">16 - 18</a></li>
            <li class="tabs-title"><a href="#panel2">19 Plus</a></li>
            <li class="tabs-title"><a href="#panel3">Missed Appointment</a></li>
            <li class="tabs-title"><a href="#panel4">No Shows</a></li>
            <li class="tabs-title"><a href="#panel5">Active Service Desks</a></li>
            <li class="tabs-title"><a href="#panel6">Enrolling Now</a></li>
            <li class="tabs-title"><a href="#panel7">Enrolled</a></li>
            <li class="tabs-title"><a href="#panel8">Unable to Enrol</a></li>
            <li class="tabs-title"><a href="#panel9">Queues</a></li>
        </ul>
        
        <div class="tabs-content" data-tabs-content="dashboard-tabs">
            
            <div class="tabs-panel is-active" id="panel1" data-view="dash_16_to_18">
            </div>
            
            <div class="tabs-panel" id="panel2" data-view="dash_19_plus" >
            </div>
            
            <div class="tabs-panel" id="panel3" data-view="dash_missed_appointments">
            </div>
            
            <div class="tabs-panel" id="panel4" data-view="dash_no_shows">
            </div>
            
            <div class="tabs-panel" id="panel5" data-view="dash_active_service_desks">
            </div>
            
            <div class="tabs-panel" id="panel6" data-view="dash_enrolling_now">
            </div>
            
            <div class="tabs-panel" id="panel7" data-view="dash_enrolled">       
            </div>
            
            <div class="tabs-panel" id="panel8" data-view="dash_failed_enrollments">          
            </div>
            
            <div class="tabs-panel" id="panel9" data-view="dash_queues_attendants_count">          
            </div>
            
        </div>
        
    </div>
</div>

<script>
   
   $( document ).on( "click", "[data-revert]", function() {
       
       modal.message('Refreshing - please wait');
       modal.show();
       
       var rmTr = $(this).parent().parent();
       $.getJSON( '/admin/reinstate/' + $(this).attr( 'data-revert' ) , function( data ) {
           
           modal.hide();
           
           if ( data.STATUS === 'OK' ) {
                
                var message = 'Enrollee has been placed back in the queue.';
                var messageBox = $('#messageTemplate').clone().css({'display':'block'});
                $( messageBox ).find( '[data-message]' ).empty().append( message );
                $('#dash-messages-container').empty().append( messageBox );
                
                $(rmTr).remove();
            }
           
       });
       
   });
   
   $( document ).on( "click", "[data-expire]", function() {
       
       var rmTr = $(this).parent().parent();
       $.getJSON( '/admin/expire/attendant/' + $(this).attr( 'data-expire' ) , function( data ) {
           
           modal.hide();
           
           if ( data.STATUS === 'OK' ) {
                
                var message = 'The service desk has been deactivated.';
                var messageBox = $('#messageTemplate').clone().css({'display':'block'});
                $( messageBox ).find( '[data-message]' ).empty().append( message );
                $('#dash-messages-container').empty().append( messageBox );
                
                $(rmTr).remove();
            }
           
       });
   });
   
</script>

@stop