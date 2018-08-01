<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolment Ticketing System</title>
    
    <link rel="stylesheet" href="/css/foundation.css">
    <link rel="stylesheet" href="/css/foundation-icons/foundation-icons.css">
    <link rel="stylesheet" href="/css/app.css">
    
    <!--
    <link rel="stylesheet" href="/css/foundation-icons/foundation-icons.css">
    <link rel="stylesheet" href="{{ elixir("build/css/app.css") }}">
    -->
    
  </head>
  <body>
     

    <script src="/js/vendor/jquery.js"></script>
    <script src="/js/vendor/jquery-ui-1.12.0.custom/jquery-ui.js"></script>
    <script src="/js/vendor/jquery.clock.js"></script>
    <script src="/js/vendor/jquery.marquee.js"></script>
    <script src="/js/vendor/what-input.js"></script>
    <script src="/js/vendor/foundation.js"></script>
    <script src="/js/app.js"></script>
    <script src="/js/stopwatch.js"></script>
    
    <!-- <script src="{{ elixir("build/js/app.js") }}"></script> -->
    
    <div class="reveal busyModal" id="busyModal" data-reveal data-close-on-click="false" data-close-on-esc="false" >
        <div style="margin: auto;" class='uil-ring-css' style='transform:scale(0.58);'><div></div></div>
        <div class="busyMessage">Please Wait</div>
    </div>
    
    @include('shared.header')
    
    <!-- Start page content -->
    @yield('content')
    <!-- End page content -->
    
    <!-- Start page content -->
    @include('shared.footer')
    <!-- End page content -->
    
    <script>
        
    $(document).foundation();
    
    </script>
    
    
  </body>
</html>