@extends('layouts.page')
@section('content')

<div class="row">
    <div class="small-12 large-6 small-centered columns">
        
        <a class="expanded button" href="/registration/create">Enrollee Registration</a>
        
        <a class="expanded button" href="/attendant/signin">Attendant Sign In</a>
        
        <a class="expanded button" href="/infoboard/1">18 and Under - Information Screen</a>
        
        <a class="expanded button" href="/infoboard/2">19 Plus/Missed Appointment - Information Screen</a>
        
        <a class="expanded button" href="/infoboard/3">All Queues - Information Screen</a>
        
        <a class="expanded button" href="/infoboard/4">Now Serving & Next Up 18 and Under Enrolment</a>
        
        <a class="expanded button" href="/infoboard/5">Now Serving & University College Enrolment</a>

        <a class="expanded button" href="/infoboard/6">2019 - All Queues</a>
        
        <a class="expanded button" href="/search">Search</a>
        
        <a class="expanded button" href="/dashboard">Dashboard</a>

        <a class="expanded button" href="/admin">Admin - Service Desks & Attendants</a>
        
    </div>
</div>

@stop