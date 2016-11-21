<!doctype html>
<html lang="en" >
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">


<title>ClientCal</title>

<!-- Bootstrap -->
<link href="components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Angular Bootstrap Calendar-->
<link href="components/font-awesome/css/font-awesome.min.css" rel="stylesheet">

<!-- https://fonts.googleapis.com/css?family=Share+Tech+Mono -->
<link href='https://fonts.googleapis.com/css?family=Roboto|Droid+Sans+Mono' rel='stylesheet' >


<link href="asset/css/rescal.css" rel="stylesheet">
  </head>
  <body>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">ClientCal</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="dropdown" data-role="dropdown1-wrap">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" data-role="dropdown1-label">Dropdown1 <span class="caret"></span></a>
          <ul class="dropdown-menu" data-role="dropdown1-list">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">One more separated link</a></li>
          </ul>
        </li>
      </ul>
      <form class="navbar-form navbar-left">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
      </form>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#">Link</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" data-role="dropdown2-label">Dropdown2 <span class="caret"></span></a>
          <ul class="dropdown-menu" data-role="dropdown2-list">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>  


<div data-role="sched-wrap">
   <header>
       <h1 data-role="month-year-label"></h1>
   </header>
   <div class="rescal" data-role="month-wrap">
   

   
   </div><!-- /.rescal -->
</div><!-- /[data-role="sched-wrap"] -->
   <div>
      <div class="label rescal-entry-label sentrytype-estimate" >estimate</div>
      <div class="label rescal-entry-label sentrytype-install" >install</div>
      <div class="label rescal-entry-label sentrytype-refinish" >refinish</div>
      <div class="label rescal-entry-label sentrytype-finish" >finish</div>
      <div class="label rescal-entry-label sentrytype-repair" >repair</div>
      <div class="label rescal-entry-label sentrytype-tear_out" >tear_out</div>
      <div class="label rescal-entry-label sentrytype-sand_only" >sand_only</div>
   </div>

<div style="display:none;" data-role="rescal-tmpl">
   <ul data-tmpl="week" class="rescal-week"></ul>
   <ul>
     <li data-tmpl="day" class="rescal-day" data-othermonthclass="rescal-other-month" >
         <div class="rescal-day-header" data-seldayclass="rescal-selday-header">
            <div class="rescal-date" data-formatdate="D"></div>
            <div class="rescal-day-dow-label" data-formatdate="ddd"></div>
            <div class="rescal-day-dow-separator"></div>
         </div>
         <ul class="rescal-day-items"></ul>
         <div class="rescal-day-summary">
            <i style="display: none;" class="fa fa-usd" aria-hidden="true" data-has-estimate></i>
            <i style="display: none;" class="fa fa-truck" aria-hidden="true" data-has-install data-has-refinish data-has-finish data-has-repair data-has-tear_out data-has-sand_only></i>
         </div>
     </li>
     <li data-tmpl="sentry" class="rescal-entry-label"></li>         
   </ul>
   <div data-tmpl="rescal-day-summary-example" class="rescal-day-summary">

   </div>
</div><!--/rescal-tmpl-->

    <!-- Moment -->
    <script src="components/moment/min/moment.min.js"></script>
    
    <!-- jQuery -->
    <script src="components/jquery/dist/jquery.min.js"></script>
    
    <!-- Bootstrap 3 -->
    <script src="components/bootstrap/dist/js/bootstrap.min.js"></script>
    


   <script src="asset/js/view.js"></script>

    </body>
</html>





