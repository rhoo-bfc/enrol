@extends('layouts.page')
@section('content')

<div class="reveal" id="notesModal" data-reveal data-close-on-click="false" data-close-on-esc="false">
    
  <fieldset class="fieldset">
    
    <legend>Enrollee Notes</legend>
    
    <p class="lead">Please select the reason why enrolment cannot be completed:</p>
    <select name="reason">
        
        <optgroup label="IAG">
        <option value="1">No enrolment form</option>
        <option value="2">No register group on form</option>
        <option value="3">No course code on form</option>
        <option value="4">Incorrect course code on form</option>
        <option value="5">No waiver code on form</option>
        <option value="6">Invalid course code - W/D</option>
        <option value="7">No learning support group</option>
        <option value="8">No prior qual's form</option>
        <option value="9">Incorrect IAG</option>
        <option value="10">Student unsure of course wanted to cancel</option>
        </optgroup>
        
        <optgroup label="STUDENT">
        <option value="11">No benefit evidence of fee waiver</option>
        <option value="12">No method of payment</option>
        <option value="13">No adv loan evidence</option>
        <option value="14">Unwilling to pay course fees</option>
        <option value="15">Safeguarding issue</option>
        <option value="16">Immigration issue</option>
        </optgroup>
        
        <optgroup label="AGENT">
        <option value="17">Student enrolled - pressed wrong button</option>
        <option value="18">Student no show - pressed wrong button</option>
        </optgroup>
        
        <optgroup label="QMGT">
        <option value="19">Not an enrolment - ref'd to as careers</option>
        <option value="20">Student already enrolled</option>
        <option value="21">Not an enrolment - ref'd to IAG</option>
        </optgroup>
        
    </select>

    <p class="lead">Please provide a brief explanation of why enrolment cannot be completed:</p>
    
    <div class="callout alert" style="display:none;" id="completeDescription">
        <p>Please complete required field.</p>
    </div>
    
    <label>
        <textarea name="notes" rows="5"></textarea>
    </label>

    <a class="expanded button" href="#" id="submitNotes">Submit</a>
  
  </fieldset>

</div>

<div class="row">
    
    <div class="columns small-12 " >
 
        <a href="/logout" class="button float-right">Logout</a>
    
    </div>
    
</div>

<div class="row" data-equalizer="p" >
    
    <div class="columns small-6 " >
        <div class="callout" data-equalizer-watch="p" >
        
        <h3>{{ $desk->src_centre_name }}</h3>
        
        <h4>{{ displayFriendly($attendant->att_first_name) }} {{ displayFriendly( $attendant->att_second_name ) }}</h4>
        
        <label>
        <strong>Serving Queue:</strong>
        
        @if( !$enrollee )
        <?php echo Form::select('ats_que_id',  $queues, $queue->que_id, [ 'data-queue' => 'SWITCH' ] ); ?>
        @else
        {{{ $queue->que_name }}}
        @endif
        </label>
        
        </div>
        
    </div>
    
    
    <div class="small-6 columns" >
        
        <div class="callout secondary" data-equalizer-watch="p">
            @if( $enrollee ) 
            Currently enrolling : <br />
            <h2>{{ displayFriendly( $enrollee->reg_first_name ) }} {{ displayFriendly( $enrollee->reg_last_name ) }}</h2>
            <h2>DOB : {{ to_uk_date($enrollee->reg_dob) }}</h2>
            @else
            <h2>No enrollee assigned</h2>
            <script>
            
            setTimeout( function() { modal.pollRefresh(); } , 5000);
            
            </script>
            @endif
        </div>
        
    </div>
    
</div>

@if( $enrollee )
<input type="hidden" name="id" value="{{ $enrollee->asn_id }}" />
<div class="row">
    
    <div class="small-12 large-3 columns">
        
        <a class="secondary button mega-button small-12 @if($enrollee->asn_status === 'STA') disabled @endif" data-action="STA" >Started <br />Enrolment</a>       
        
    </div>
    
    <div class="small-12 large-2 columns">
        
            <a class="button mega-button small-12 @if($enrollee->asn_status === 'STA') disabled @endif" data-action="NOS" >No <br />Show</a>      
        
    </div>
    
    <div class="small-12 large-2 columns">
        
              <a class="warning mega-button button small-12 @if($enrollee->asn_status === 'STA') disabled @endif" data-action="NEX" >Next <br />Enrolee</a>
         
    </div>

    <div class="small-12 large-2 columns">
        
             <a class="success mega-button button small-12 @if(!$enrollee->asn_status) disabled @endif" data-action="COM" >Enrolment <br />Completed&nbsp;</a>
           
    </div>
    
    <div class="small-12 large-3 columns">
        
              <a class="warning mega-button button small-12 @if(!$enrollee->asn_status) disabled @endif" data-action="FAI" >Unable to <br />Enrol</a>
         
    </div>
    
</div>
@endif

@stop