(function() {
   
   $('[data-role="month-wrap"]').empty();
   //$('[ data-role="rescal-tmpl"] [data-tmpl="month-example"]').appendTo($('[data-role="month-wrap"]'));
   
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
   
//   console.log('start of display...');
//   console.debug(curMoment.toISOString());   
//   
//   console.log('end of cal...');
//   console.debug(calEndMom.toISOString());      
   var curW;
   var weekUI;
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
   }
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
})();