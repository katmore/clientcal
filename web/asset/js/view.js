(function() {
   
   $('[data-role="month-wrap"]').empty();
   
   var startDOW=1;
   var endDOW=0;
   
   var reqMoment=moment();
   var selDay=moment().dayOfYear();
   var reqMonth=moment().format('M');
   
   var calStartMom = moment(reqMonth,'M');

   // Clone the value before .endOf()
   var calEndMom = moment(calStartMom).endOf('month').add(-1,'day');   
   
   var curMoment=calStartMom;
   
//   console.log('first day of Month...');
//   console.debug(curMoment.toISOString());
//   console.log('it is day-of-week num...');
//   console.debug(curMoment.format('e'));
   
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
   var sentryTmpl = $('[data-role="rescal-tmpl"] [data-tmpl="sentry"]').clone();
   for(;;) {
      /*
       * do stuff...
       */
      if (curMoment.format('e')==startDOW) {
         curW = curMoment.format('W');
         if (weekUI) {
            $('[data-role="month-wrap"]').append(weekUI);
         }
         weekUI = $('[data-role="rescal-tmpl"] [data-tmpl="week"]').clone().attr('data-weekno',curW);
      }
      var dayUI = $('[data-role="rescal-tmpl"] [data-tmpl="day"]').clone();
      
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
         $('[data-role="month-wrap"]').append(weekUI);
         break;
      }
      curMoment.add(1,'day');
   }/*finished creating empty 'rescal'*/
   
   var jqxhr = $.ajax({
      type: "GET",
      url: '/clientcal/api/calendar.php',
      data : {
         month : reqMoment.format('YM'),
      },
   })
   .done(function(data, textStatus, jqXHR) {
      console.log('complete...');
      console.debug(data);
      var dayUI;
      for(var i=0;i<data.length;i++) {
         var sentry=data[i];
         var timeMom = moment(sentry.time,moment.ISO_8601);
         var target = {
            dayofmonth : timeMom.format('D'),
            year : timeMom.format('Y'),
            month : timeMom.format('M'),
         };
         dayUI=$('[data-dayofmonth="'+target.dayofmonth+'"][data-year="'+target.year+'"][data-month="'+target.month+'"] .rescal-day-items');
         var sentryUI = sentryTmpl.clone();
         sentryUI.text(sentry.label);
         dayUI.find('[data-has-estimate]').length;
         
         dayUI.append(sentryUI);
      }
   })
   .fail(function(jqXHR, textStatus, errorThrown) {
      console.log('failed');
   }); 
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
})();