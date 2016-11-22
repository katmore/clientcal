var responsiveCal;
console.log('responsiveCal.js');
(function() {
   if (typeof(responsiveCal)!=='undefined') return;
   
   
   
   
   responsiveCal = function(config) {
      var param = {
            reqMoment : moment(),
            monthWrapTarget : '.mcal-wrap .mcal',
      };      
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
               weekUI = $('[data-role="mcal-tmpl"] [data-tmpl="week"]').clone().attr('data-weekno',curW);
            }
            var dayUI = $('[data-role="mcal-tmpl"] [data-tmpl="day"]').clone();
            
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