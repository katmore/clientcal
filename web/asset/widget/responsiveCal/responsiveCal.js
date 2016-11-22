var responsiveCal;
(function() {
   if (typeof(responsiveCal)!=='undefined') return;
   var heredoc = function (f) {
      return f.toString().match(/\/\*\s*([\s\S]*?)\s*\*\//m)[1];
   };
   var lut = []; for (var il=0; il<256; il++) { lut[il] = (il<16?'0':'')+(il).toString(16); }
   var uuid = function() {
      var d0 = Math.random()*0xffffffff|0;
      var d1 = Math.random()*0xffffffff|0;
      var d2 = Math.random()*0xffffffff|0;
      var d3 = Math.random()*0xffffffff|0;
      return lut[d0&0xff]+lut[d0>>8&0xff]+lut[d0>>16&0xff]+lut[d0>>24&0xff]+'-'+
        lut[d1&0xff]+lut[d1>>8&0xff]+'-'+lut[d1>>16&0x0f|0x40]+lut[d1>>24&0xff]+'-'+
        lut[d2&0x3f|0x80]+lut[d2>>8&0xff]+'-'+lut[d2>>16&0xff]+lut[d2>>24&0xff]+
        lut[d3&0xff]+lut[d3>>8&0xff]+lut[d3>>16&0xff]+lut[d3>>24&0xff];
    };
   
   var calTmplHtml = heredoc(function(){/*
<div style="display:none;" id="{calTmplId}">
   <ul data-tmpl="week" class="mcal-week"></ul>
   <ul>
     <li data-tmpl="day" class="mcal-day" data-othermonthclass="mcal-other-month" >
         <div class="mcal-day-header" data-seldayclass="mcal-selday-header">
            <div class="mcal-date" data-formatdate="D"></div>
            <div class="mcal-day-dow-label" data-formatdate="ddd"></div>
            <div class="mcal-day-dow-separator"></div>
         </div>
         <ul class="mcal-day-items"></ul>
         <div class="mcal-day-summary"></div>
     </li>      
   </ul>
</div><!--/mcal-tmpl-->
*/});
   
   responsiveCal = function(config) {
      var param = {
            reqMoment : moment(),
            monthWrapTarget : '.mcal-wrap .mcal',
            calTmplId : 'responsivecal-'+uuid(),
      };
      
      if (typeof(config)==='object') {
         for (var p in param) {
            
         }
         delete p;
      }
      var calTmplTarget = '#'+param.calTmplId;
      
      if (!$(calTmplTarget).length) {
         $('body').append(calTmplHtml.replace(/{calTmplId}/g,param.calTmplId));
      }
      
      
      
      var genMonth = function() {
         $(param.monthWrapTarget).empty();
         
         
         
         var startDOW=1;
         var endDOW=0;
         var selDay=moment().dayOfYear();
         
         var reqMonth=moment().format('M');
         
         var calStartMom = moment(reqMonth,'M');
      
         // Clone the value before .endOf()
         var calEndMom = moment(calStartMom).endOf('month').add(-1,'day');   
         
         var curMoment=calStartMom;
         
         for(;;) {
            if (curMoment.format('e')==startDOW) {
               break;
            }
            curMoment.add(-1,'day');
         }
         
         for(;;) {
            if (calEndMom.format('e')==endDOW) {
               break;
            }
            calEndMom.add(1,'day');
         }
          
         var curW;
         var weekUI;

         for(;;) {
            /*
             * do stuff...
             */
            if (curMoment.format('e')==startDOW) {
               curW = curMoment.format('W');
               if (weekUI) {
                  $(param.monthWrapTarget).append(weekUI);
               }
               weekUI = $(calTmplTarget+' [data-tmpl="week"]').clone().attr('data-weekno',curW);
            }
            var dayUI = $(calTmplTarget+' [data-tmpl="day"]').clone();
            
            dayUI.attr('data-dayofmonth',curMoment.format('D'));
            dayUI.attr('data-year',curMoment.format('Y'));
            dayUI.attr('data-month',curMoment.format('M'));
            
            //data-formatdate="DD"
            dayUI.find('[data-formatdate]').each(function() {
               var f=$(this).data('formatdate');
               $(this).text(curMoment.format(f));
            });
            
            if (curMoment.format('M')!=reqMonth) {
               dayUI.addClass(dayUI.data('othermonthclass'));
               //console.log('not req month: '+curMoment.toISOString());
            }    
            if (curMoment.dayOfYear()==selDay) {
               dayUI.find('[data-seldayclass]').each(function() {
                  $(this).addClass($(this).data('seldayclass'));
               });
            }
            
            dayUI.appendTo(weekUI);
            /*
             * advance and check if end of calendar
             */
            
            if (curMoment.format('YYYYMMDD')==calEndMom.format('YYYYMMDD')) {
               //console.log('found end: '+curMoment.toISOString());
               $(param.monthWrapTarget).append(weekUI);
               break;
            }
            curMoment.add(1,'day');
         }/*finished creating empty 'mcal'*/
         
      }; 
      return {
         'generate' : function(config) {
            if (typeof config === 'string' || config instanceof String) {
               if (config=='month') {
                  return genMonth();
               }
            }
         },
      };      
   };
})();