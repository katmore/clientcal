(function() {
   
   var ccmcalTmplTarget = '#cc-mcal-tmpl';
   var ccmcalWrapTarget = '#cc-sched-wrap';
   $('#cc-day').modal({keyboard: false});
   var populateMonth = function(config) {
      var reqMoment=moment();
      var sentryTmpl = $(ccmcalTmplTarget+' [data-tmpl="sentry"]').clone();
      var badgeTmpl = $(ccmcalTmplTarget+' [data-tmpl="badge-wrap"]').clone();   
      
      $(ccmcalTmplTarget+' .mcal-day').off('click');
      
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
            
            var badgeUI = badgeTmpl.find('[data-for-'+sentry.type+']').clone();
            
            sentryUI.addClass('sentrytype-'+sentry.type);
            
            dayUI.find('.mcal-day-items').append(sentryUI);
            
            sentryUI.find('[data-role="badge-wrap"]').append(badgeUI.clone());
            
            dayUI.find('.mcal-day-summary').append(badgeUI.clone());
            if (typeof(dayUI.data('sentry'))==='undefined') {
               dayUI.data('sentry',[sentry]);
            } else {
               dayUI.data('sentry').push(sentry);
            }
         }
         
         $(ccmcalWrapTarget+' .mcal-day').on('click',function() {
            if (!$(this).hasClass('mcal-day-selected')) {
               
               $(ccmcalWrapTarget+' .mcal-day-header').removeClass('mcal-selday-header');
               $(this).find('[data-seldayclass]').each(function() {
                  $(this).addClass($(this).data('seldayclass'));
               });
               $(this).addClass('mcal-day-selected');
               return;
            }
            var modalTarget = '#cc-day';
            var modal = $(modalTarget);
            //data-dayofmonth="3" data-year="2016" data-month="11"
            var dMoment=moment({date:$(this).data('dayofmonth'),year:$(this).data('year'),month:$(this).data('month')-1});
            modal.find('[data-day-formatdate]').each(function(){
               $(this).text(dMoment.format($(this).data('dayFormatdate')));
            });
            modal.find('[data-role="sentrylist"]').empty();
            if ( (typeof($(this).data('sentry'))!=='undefined') && Array.isArray($(this).data('sentry'))) {
               for(var i=0;i<$(this).data('sentry').length;i++) {
                  var cSentry=$(this).data('sentry')[i];
                  console.log('sentry...');
                  var sUI = $(ccmcalTmplTarget+' [data-tmpl="sentry-dayview"]').clone();
                  sUI.addClass('sentrytype-'+cSentry.type);
                  var badgeUI = badgeTmpl.find('[data-for-'+cSentry.type+']').clone();
                  sUI.find('[data-role="badge-wrap"]').append(badgeUI);
                  sUI.find('[data-field="label"]').text(cSentry.label);
                  sUI.find('[data-sentry-dateformat]').each(function() {
                     $(this).text(moment(cSentry.time,moment.ISO_8601).format($(this).data('sentryDateformat')));
                  });
                  modal.find('[data-role="sentrylist"]').append(sUI);
               }
            }
            console.log('sentries...');
            console.debug($(this).data('sentry'));
            modal.modal('show');
         });
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
         console.log('failed');
      }); 
   };
   
   //#cc-sched-wrap .mcal
   populateMonth({monthWrapTarget: '#cc-sched-wrap .mcal'});
   
   responsiveCal().generate('month');
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
})();