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
  
  {!! Form::open(array('url' => 'signin')) !!}
  
  <label>Attendant Name:
  <?php echo Form::select('ats_att_id', [null => 'Please select your attendant details'] + $attendants ); ?>
  </label>
  
  <label>Service Desk:
  <?php echo Form::select('ats_src_id', [null => 'Please select service desk'] + $serviceDesks ); ?>
  </label>
  
  <label>Queues:
  <?php echo Form::select('ats_que_id',  [null => 'Please select queue'] + $queues ); ?>
  </label>
  
  <button class="expanded button">Sign In</button>
  
  {!! Form::close() !!}
  
  </div>
</div>

@stop