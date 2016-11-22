(function() {
   
   var ccmcalTmplTarget = '#cc-mcal-tmpl';
   var ccmcalWrapTarget = '#cc-sched-wrap';
   var populateMonth = function(config) {
      var reqMoment=moment();
      var sentryTmpl = $(ccmcalTmplTarget+' [data-tmpl="sentry"]').clone();
      var badgeTmpl = $(ccmcalTmplTarget+' [data-tmpl="badge-wrap"]').clone();   
      
      var jqxhr = $.ajax({
         type: "GET",
         url: '/clientcal/api/calendar.php',
         data : {
            month : reqMoment.format('YM'),
         },
      })
      .done(function(data, textStatus, jqXHR) {
//         console.log('complete...');
//         console.debug(data);
         var dayUI;
         for(var i=0;i<data.length;i++) {
            var sentry=data[i];
            var timeMom = moment(sentry.time,moment.ISO_8601);
            var target = {
               dayofmonth : timeMom.format('D'),
               year : timeMom.format('Y'),
               month : timeMom.format('M'),
            };
            dayUI=$(ccmcalWrapTarget+' [data-dayofmonth="'+target.dayofmonth+'"][data-year="'+target.year+'"][data-month="'+target.month+'"]');
            var sentryUI = sentryTmpl.clone();
            
            sentryUI.find('[data-field="label"]').text(sentry.label);
            
            sentryUI.find('[data-role="badge-wrap"]').append(badgeTmpl.find('[data-for-'+sentry.type+']').clone());
            
            
            sentryUI.addClass('sentrytype-'+sentry.type);
            
            dayUI.find('.mcal-day-items').append(sentryUI);
            
            dayUI.find('.mcal-day-summary [data-for-'+sentry.type+']').show();
         }
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
         console.log('failed');
      }); 
   };
   
   //#cc-sched-wrap .mcal
   populateMonth({monthWrapTarget: '#cc-sched-wrap .mcal'});
   
   responsiveCal().generate('month');
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
})();