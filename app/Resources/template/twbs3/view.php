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
 
 <!-- Responsive Calendar -->
<script src="asset/widget/responsiveCal/responsiveCal.js"></script>

<!-- main app javascript -->
<script src="asset/js/view.js"></script>

<!-- Modal listing all of day's schedule items -->
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
      <div class="modal-footer">
      <button style="float:left;" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal for view/edit details of existing schedule item -->
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
        <a style="display: none;" role="button" class="btn btn-primary" href="javascript:return false;" target="_blank" id="cc-sentry-modal-tel-link"><i class="fa fa-phone" aria-hidden="true" ></i>&nbsp;Call&nbsp;<span data-field="customer_phone"></span></a>
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

<!-- hacky phone call feature detection -->
<div style="position: absolute; left:-9999px;top:-9999px;" id="cc-has-phone-test">1-800-555-1234</div>
<script>
(function() {
   var hasPhone = $('#test-phone-calls').find("a[href*='tel:']").length > 0;
   if (!hasPhone) {
      var check = false;
      (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
      if (check) hasPhone=true;
   }
   //cc-sentry-modal-tel-link
   if (hasPhone) $('#cc-sentry-modal-tel-link').show();
})();
</script>

    </body>
</html>





