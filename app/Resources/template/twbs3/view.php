<!doctype html>
<html lang="en" >
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">


<title>ClientCal</title>

<!-- Bootstrap -->
<link href="components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link href="components/font-awesome/css/font-awesome.min.css" rel="stylesheet">

<!-- https://fonts.googleapis.com/css?family=Share+Tech+Mono -->
<link href='https://fonts.googleapis.com/css?family=Roboto|Droid+Sans+Mono' rel='stylesheet' >

<link href="asset/widget/responsiveCal/responsiveCal.css" rel="stylesheet">

<link href="asset/css/view.css" rel="stylesheet">



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
<div id="cc-sched-wrap" class="mcal-wrap">
   <input type="date" data-month-formatdate-val="YYYY-MM-DD" id="cc-sched-monthpick">
   <button type="button" class="btn btn-secondary mcal-month-nav-button" data-role="prior-month">
      <i class="fa fa-caret-up" aria-hidden="true"></i>
   </button>   
   <div class="mcal" data-role="month-wrap"></div>
   <button type="button" class="btn btn-secondary mcal-month-nav-button" data-role="next-month">
      <i class="fa fa-caret-down" aria-hidden="true"></i>
   </button>   
</div><!--/mcal-wrap -->

<div class="sentrytype-badge-legend" >
   <i class="fa fa-usd" aria-hidden="true" data-for-estimate></i>&nbsp;estimate
      <div>   
   <img class="badge-img" src="asset/img/recycle.png" data-for-refinish>&nbsp;refinish
      </div>    
      <div>   
   <img class="badge-img" src="asset/img/diagfloor.png" data-for-install>&nbsp;install
      </div>   
      <div>
   <img class="badge-img" src="asset/img/paintbrush.png" data-for-finish>&nbsp;finish
      </div>      
      <div>   
   <img class="badge-img" src="asset/img/wreckingball.png" data-for-tear_out>&nbsp;tear&#45;out
      </div>            
      <div>
   <img class="badge-img" src="asset/img/wrench.png" data-for-repair>&nbsp;repair
      </div>
      <div>   
   <img class="badge-img" src="asset/img/sandbelt.png" data-for-sand_only>&nbsp;sand&#45;only
      </div>
</div>




<div style="display:none;" data-role="mcal-tmpl" id="cc-mcal-tmpl">
   <ul>
     <li data-tmpl="sentry-monthview" class="mcal-entry-item"><span data-role="badge-wrap"></span>&nbsp;<span data-field="label"></span></li>      
   </ul>
   <button data-tmpl="sentry-dayview" class="btn mcal-entry-item mcal-entry-item-dayview" >
      <div>
         <p class="mcal-entry-item-dayview-heading"><span data-role="badge-wrap"></span>&nbsp;<span data-sentry-dateformat="h:ssa"></span>&nbsp;<span data-role="type-label"></span></p>
         <p data-role="label-display"></p>
         <p><i class="fa fa-user-circle" aria-hidden="true"></i>&nbsp;<span data-field="customer_full_name"></span></p>
         <p data-role="customer-phone-display" ><i class="fa fa-phone-square" aria-hidden="true"></i>&nbsp;<span data-field="customer_phone"></span></p>
         <p data-role="directions-display"><i class="fa fa-map" aria-hidden="true"></i>&nbsp;<span data-field="directions" class="mcal-entry-directions"></span></p>
         <p data-role="streetaddr-display"><i class="fa fa-home" aria-hidden="true"></i>&nbsp;<span data-field="streetaddr"></span></p>
         <p><i class="fa fa-building" aria-hidden="true"></i>&nbsp;<span data-role="citystatezip"></span></p>
      </div>
   </button>    
   <div data-tmpl="badge-wrap">
      <i class="fa fa-usd" aria-hidden="true" data-for-estimate></i>
      <img class="badge-img" src="asset/img/wrench.png" data-for-repair>
      <img class="badge-img" src="asset/img/paintbrush.png" data-for-finish>
      <img class="badge-img" src="asset/img/sandbelt.png" data-for-sand_only>
      <img class="badge-img" src="asset/img/wreckingball.png" data-for-tear_out>
      <img class="badge-img" src="asset/img/diagfloor.png" data-for-install>
      <img class="badge-img" src="asset/img/recycle.png" data-for-refinish>
   </div>
</div><!--/mcal-tmpl-->

    <!-- Moment -->
    <script src="components/moment/min/moment.min.js"></script>
    
    <!-- jQuery -->
    <script src="components/jquery/dist/jquery.min.js"></script>
    
    <!-- Bootstrap 3 -->
    <script src="components/bootstrap/dist/js/bootstrap.min.js"></script>
    
   <script src="asset/widget/responsiveCal/responsiveCal.js"></script>

   <script src="asset/js/view.js"></script>


<div class="modal" tabindex="-1" role="dialog" id="cc-day" >
  <div class="modal-dialog" role="document">
    <div class="modal-content" >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" data-day-formatdate="dddd, MMM. D, Y"></h4>
      </div>
      <div class="modal-body">
        <div data-role="sentrylist">
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal" tabindex="-1" role="dialog" id="cc-sentry-modal" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" ><span data-role="badge-wrap"></span>&nbsp;<span data-role="type-label"></span>&nbsp;&#45;&nbsp;<span data-field="label"></span></h4>
      </div>

      <div class="modal-body">
         <div>
        <a role="button" class="btn btn-primary" href="javascript:return false;" target="_blank" id="cc-sentry-modal-maps-link"><i class="fa fa-car" aria-hidden="true" ></i>&nbsp;View in Maps</a>
        <a role="button" class="btn btn-primary" href="javascript:return false;" target="_blank" id="cc-sentry-modal-tel-link"><i class="fa fa-phone" aria-hidden="true" ></i>&nbsp;Call&nbsp;<span data-field="customer_phone"></span></a>
         </div>
        <div class="form-group">
          <label for="cc-sentry-time-input">Time</label>
          <input  id="cc-sentry-time-input" type="time" class="form-control" data-save="time">
        </div>
        <div class="form-group">
          <label for="cc-sentry-date-input">Date</label>
          <input  id="cc-sentry-date-input" type="date" class="form-control" data-save="date">
        </div> 
        <div class="form-group">
          <label for="cc-sentry-streetaddr-input">Address</label>
          <input  id="cc-sentry-streetaddr-input" type="text" class="form-control" data-fieldval="streetaddr" data-save="streetaddr">
        </div>        
        <div class="form-group">
          <label for="cc-sentry-city-input">City</label>
          <input  id="cc-sentry-city-input" type="text" class="form-control" data-fieldval="city" data-save="city">
        </div>  
        <div class="form-group">
          <label for="cc-sentry-state-input">State</label>
          <input  id="cc-sentry-state-input" type="text" class="form-control" data-fieldval="state" data-save="state">
        </div>  
        <div class="form-group">
          <label for="cc-sentry-zip-input">Zip</label>
          <input  id="cc-sentry-zip-input" type="text" class="form-control" data-fieldval="zip" data-save="zip">
        </div>                 
        <div class="form-group">
          <label for="cc-sentry-direction-textarea">Directions</label>
          <textarea id="cc-sentry-direction-textarea" class="form-control" rows="3" data-fieldval="directions" data-save="directions"></textarea>
        </div>                 
        <div class="form-group">
          <label for="cc-sentry-jobtype-sel">Job Type</label>
          <select id="cc-sentry-jobtype-sel" class="form-control" data-save="type" data-fieldval="type">
            <option value="estimate">Estimate</option>
            <option value="install">Install</option>
            <option value="refinish">Refinish</option>
            <option value="finish">Finish</option>
            <option value="repair">Repair</option>
            <option value="tear_out">Tear Out</option>
            <option value="sand_only">Sand Only</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
         <button style="float:left;" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Cancel</button>
         <button class="btn btn-success" data-role="save-changes"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Save Changes</button>
         
      </div>      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


    </body>
</html>





