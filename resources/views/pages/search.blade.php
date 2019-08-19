@extends('layouts.page')
@section('content')

@if (count($errors) > 0)
  <div class="row">
  	 <div class="small-12 large-6 small-centered columns callout alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
     </div>
  </div>
@endif

<div class="row">
  <div class="small-12 large-6 small-centered columns"> 
      
    <div class="row">
        <div class="columns small-12">
            <h1>Enrollee Search</h1>
        </div>
    </div>
   
   <div class="row">
       
       <div class="columns small-12" >
           <div class="alert callout" style="display:none;" data-closable id="errorMessage">
                Please enter either first and/or last name.
                <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                  <span aria-hidden="true">&times;</span>
                </button>
           </div>
       </div>
       
       <div class="columns small-12">
           <label><strong>Name</strong></label>
       </div>
       
       <div class="columns small-12 large-6">
            <label>
             <?php echo Form::text('reg_first_name' ,null,['placeholder'=>'First name'] ); ?>
            </label>    
       </div>
       
       <div class="columns small-12 large-6">
            <label>
             <?php echo Form::text('reg_last_name' ,null,['placeholder'=>'Second name']); ?>
            </label>    
       </div>
       
   </div>
   
   <!--
   <div class="row">
       
       <div class="columns small-12">
           <label><strong>Date of Birth</strong></label>
       </div>
       
       <div class="columns small-12 large-4">
        <label>
             <?php echo Form::number('reg_birth_day',null,[ 'maxlength' => 2, 'min' => 1, 'max' => 31, 'placeholder'=>'Day' ] ); ?>
        </label>
       </div>
       
       <div class="columns small-12 large-4">
        <label>
             <?php 
             
             $months = [null => 'Month',
                        '1'  => 'January',
                        '2'  =>'February',
                        '3'  =>'March',
                        '4'  =>'April',
                        '5'  =>'May',
                        '6'  =>'June',
                        '7'  =>'July',
                        '8'  =>'August',
                        '9'  =>'September',
                        '10' =>'October',
                        '11' =>'November',
                        '12' =>'December'];
             
             echo Form::select('reg_birth_month',$months); ?>
        </label>
       </div>
       
       <div class="columns small-12 large-4">
        <label>
             <?php echo Form::number('reg_birth_year',null,[ 'maxlength' => 4, 'min' => date("Y") - 100, 'max' => date("Y") - 16 , 'placeholder'=>'Year'] ); ?>
        </label>
       </div>
       
   </div>
   -->
   
   <div class="row">
   
    <div class="columns small-6 ">
        <input type="submit" class="button expanded" value="Clear" id="clear" >
    </div>
       
    <div class="columns small-6 ">
        <input type="submit" class="button expanded" value="Search" id="search" >
    </div>
   
   </div>
   
  <div class="reveal" id="revertModal" data-reveal>
  <h1>Restore to Queue at :</h1>
  
  <div class="row">
    
    <input type="hidden" name="revertId" id="revertId" value="" />
    <div class="small-12 large-4 columns">
        <a class="secondary button mega-button small-12" data-revert-position='F' >Front</a>       
    </div>
    
    <div class="small-12 large-4 columns">
            
            <a class="secondary button mega-button small-12" data-revert-position='C' >Current</a>  
    </div>
    
    
    <div class="small-12 large-4 columns">
        
             <a class="secondary button mega-button small-12" data-revert-position='B' >Back</a>      
    </div>
  </div>
  
  
  <button class="close-button" data-close aria-label="Close modal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
   
   <script>
       
           $('[data-revert-position]').click(function() {
      
       var position = $(this).attr('data-revert-position');
      
       modal.message('Refreshing - please wait');
       modal.show();
       
       var rmTr = $("[data-revert=" + $('#revertId').val() + "]").parent().parent();
       $.getJSON( '/admin/reinstate/' + $('#revertId').val() + '/' + position , function( data ) {
           
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
       
        $('#clear').click(function() {
            
            $('#resultsPane').empty();
            $('[name=reg_first_name]').val('');
            $('[name=reg_last_name]').val('');
        });
        
         $( document ).on( "click", "[data-revert]", function() {
       
            $('#revertId').val( $(this).attr( 'data-revert' ) );  
            $('#revertModal').foundation('open');
            return;
       
        });
       
       $('#search').click(function() {
         
         $('#errorMessage').hide();
         
         if ( ( !$.trim($('[name=reg_first_name]').val()) ) && ( !$.trim($('[name=reg_last_name]').val()) ) ) {
             $('#errorMessage').show();
             return;
         }
         
         $.post( "/index.php/search", { f: $('[name=reg_first_name]').val(), l: $('[name=reg_last_name]').val() }, function( data ) {
            
            $('#resultsPane').empty();
            $.each(data, function(k, v) {
                
                $('#resultsPane').append('<tr><td>' + v.first_name + ' ' + v.last_name + '<br/><strong>dob : </strong>' + v.dob_uk + '</td><td>' + v.message + '</td><td>' + v.action + '</td>');        
            });
            
         });
         
       });
       
   </script>
   
   <hr />
   
  </div>
    
    
<div class="row">
    
    <div class="small-12 large-9 small-centered columns"> 
    <table>
      <thead>
        <tr>
          <th>Enrollee Details</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="resultsPane">
      </tbody>
   </table>
   </div>

</div>  
    
</div>



@stop