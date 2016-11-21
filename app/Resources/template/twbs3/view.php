<!doctype html>
<html lang="en" >
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">


<title>ClientCal</title>

<!-- Bootstrap -->
<link href="components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Angular Bootstrap Calendar-->
<link href="components/angular-bootstrap-calendar/dist/css/angular-bootstrap-calendar.min.css" rel="stylesheet">

<!-- https://fonts.googleapis.com/css?family=Share+Tech+Mono -->
<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' >


  <style>
body {
  font-family: Tahoma;
}

header {
  text-align: center;
}

.rescal {
  width: 100%;
}

.rescal a {
  color: #8e352e;
  text-decoration: none;
}

.rescal ul {
  list-style: none;
  padding: 0;
  margin: 0;
  width: 100%;
}

.rescal li {
  display: block;
  float: left;
  width: 14.342%;
  box-sizing: border-box;
  border: 1px solid #ccc;
  margin-right: -1px;
  margin-bottom: -1px;
}

.rescal ul.rescal-weekdays {
  height: 40px;
  background: #8e352e;
}

.rescal ul.rescal-weekdays li {
  text-align: center;
  text-transform: uppercase;
  line-height: 20px;
  border: none !important;
  color: #fff;
  font-size: 13px;
}

.rescal-week li {
  height: 180px;
}

.rescal-week li:hover {
  background: #d3d3d3;
}

.rescal-day-header {
   font-family: 'Roboto';
}

.rescal-date {
  width: 20px;
  float: left;
  
}

.rescal-day-dow-label {
   border-left: 1px solid #666; 
   float: left;
}

.rescal-day-dow-separator {
   border-bottom: 1px solid #666; 
   width:100%;
   float:left;
}

.rescal-other-month {
  background: #f5f5f5;
  color: #666;
  
}

.rescal-other-month .rescal-day-header {
   visibility: hidden;
}

/* ============================
                Mobile Responsiveness
   ============================*/
@media (max-width: 768px) {
  .rescal-weekdays, .rescal .rescal-other-month {
    display: none;
  }

  .rescal li {
    height: auto !important;
    border: 1px solid #ededed;
    width: 100%;
    margin-bottom: -1px;
  }



}
  </style>
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
       <h1 data-role="month-year-label">August 2014</h1>
   </header>
   <div class="rescal" data-role="month-wrap">
   </div><!-- /.rescal -->


   
</div><!-- /[data-role="sched-wrap"] -->


<div style="display:none;" data-role="tmpl">
   <div data-tmpl="month">
       <!-- Days from previous month -->
       <ul class="rescal-week">
           <li class="rescal-day rescal-other-month">
               <div class="rescal-day-header">
                  <div class="rescal-date">27</div>
                  <div class="rescal-day-dow-label" >Sun</div>  
                  <div class="rescal-day-dow-separator"></div>
               </div>                    
           </li>
           <li class="rescal-day rescal-other-month">
               <div class="rescal-day-header">
               <div class="rescal-date">28</div>
               <div class="rescal-day-dow-label" >Mon</div>  
               <div class="rescal-day-dow-separator"></div>         
               </div> 
           </li>
           <li class="rescal-day rescal-other-month">
               <div class="rescal-day-header">
               <div class="rescal-date">29</div>     
               <div class="rescal-day-dow-label" >Tue</div>  
               <div class="rescal-day-dow-separator"></div>
               </div>                                           
           </li>
           <li class="rescal-day rescal-other-month">
               <div class="rescal-day-header">
               <div class="rescal-date">30</div>     
               <div class="rescal-day-dow-label" >Wed</div>  
               <div class="rescal-day-dow-separator"></div>  
               </div>                                         
           </li>
           <li class="rescal-day rescal-other-month">
               <div class="rescal-day-header">
               <div class="rescal-date">31</div>     
               <div class="rescal-day-dow-label" >Thu</div>  
               <div class="rescal-day-dow-separator"></div>  
               </div>                                          
           </li>

           <!-- Days in current month -->

           <li class="rescal-day">
               <div class="rescal-date">1</div>   
               <div class="rescal-day-dow-label" >Fri</div>  
               <div class="rescal-day-dow-separator"></div>                                              
           </li>
           <li class="rescal-day">
               <div class="rescal-date">2</div>
               <div class="rescal-day-dow-label" >Sat</div>  
               <div class="rescal-day-dow-separator"></div>                                               
           </li>
       </ul>

           <!-- Row #2 -->

       <ul class="rescal-week">
           <li class="rescal-day">
               <div class="rescal-date">3</div>                       
           </li>
           <li class="rescal-day">
               <div class="rescal-date">4</div>                       
           </li>
           <li class="rescal-day">
               <div class="rescal-date">5</div>                       
           </li>
           <li class="rescal-day">
               <div class="rescal-date">6</div>                       
           </li>
           <li class="rescal-day">
               <div class="rescal-date">7</div>                     
           </li>
           <li class="rescal-day">
               <div class="rescal-date">8</div>                       
           </li>
           <li class="rescal-day">
               <div class="rescal-date">9</div>                       
           </li>
       </ul>

           <!-- Row #3 -->

       <ul class="rescal-week">
           <li class="rescal-day">
               <div class="rescal-date">10</div>                      
           </li>
           <li class="rescal-day">
               <div class="rescal-date">11</div>                      
           </li>
           <li class="rescal-day">
               <div class="rescal-date">12</div>                      
           </li>
           <li class="rescal-day">
               <div class="rescal-date">13</div>                      
           </li>
           <li class="rescal-day">
               <div class="rescal-date">14</div>                     
           </li>
           <li class="rescal-day">
               <div class="rescal-date">15</div>                      
           </li>
           <li class="rescal-day">
               <div class="rescal-date">16</div>                      
           </li>
       </ul>

           <!-- Row #4 -->

       <ul class="rescal-week">
           <li class="rescal-day">
               <div class="rescal-date">17</div>                      
           </li>
           <li class="rescal-day">
               <div class="rescal-date">18</div>                      
           </li>
           <li class="rescal-day">
               <div class="rescal-date">19</div>                      
           </li>
           <li class="rescal-day">
               <div class="rescal-date">20</div>                      
           </li>
           <li class="rescal-day">
               <div class="rescal-date">21</div>                      
           </li>
           <li class="rescal-day">
               <div class="rescal-date">22</div>                     
           </li>
           <li class="rescal-day">
               <div class="rescal-date">23</div>                      
           </li>
       </ul>

               <!-- Row #5 -->

       <ul class="rescal-week">
           <li class="rescal-day">
               <div class="rescal-date">24</div>                      
           </li>
           <li class="rescal-day">
               <div class="rescal-date">25</div>                     
           </li>
           <li class="rescal-day">
               <div class="rescal-date">26</div>                      
           </li>
           <li class="rescal-day">
               <div class="rescal-date">27</div>                      
           </li>
           <li class="rescal-day">
               <div class="rescal-date">28</div>                      
           </li>
           <li class="rescal-day">
               <div class="rescal-date">29</div>                      
           </li>
           <li class="rescal-day">
               <div class="rescal-date">30</div>                      
           </li>
       </ul>
  </div>
</div>

    <!-- Moment -->
    <script src="components/moment/min/moment.min.js"></script>
    
    <!-- jQuery -->
    <script src="components/jquery/dist/jquery.min.js"></script>
    
    <!-- Bootstrap 3 -->
    <script src="components/bootstrap/dist/js/bootstrap.min.js"></script>
    


   <script src="asset/js/view.js"></script>

    </body>
</html>





