(function() {
   
   var ccmcalTmplTarget = '#cc-mcal-tmpl';
   var ccmcalWrapTarget = '#cc-sched-wrap';
   
   var modalDayTarget = '#cc-day';
   $(modalDayTarget).modal({keyboard: false});
   
   var populateMonth = function(config) {
      
      var param = {
         reqMoment:moment()
      };
      
      if (typeof(config)=='object') {
         for(var p in param) {
            if (typeof(config[p])!=='undefined') {
               param[p]=config[p];
            }
         }
         delete p;
      }
      
      //data-month-formatdate-val="YYYY-MM-DD" id="cc-sched-monthpick"
      $(ccmcalWrapTarget+' [data-month-formatdate-val]').each(function() {
         $(this).val(param.reqMoment.format($(this).data('monthFormatdateVal')));
      });
      
      var sentryTmpl = $(ccmcalTmplTarget+' [data-tmpl="sentry-monthview"]').clone();
      var badgeTmpl = $(ccmcalTmplTarget+' [data-tmpl="badge-wrap"]').clone();
      
      $(ccmcalTmplTarget).data('reqMoment',param.reqMoment);
      $(ccmcalTmplTarget+' .mcal-day').off('click');
      //cc-sched-monthpick
      
      var sortDayModal = function(dayUI) {
         var modalDay = $(modalDayTarget);
         $wrap=$(modalDayTarget+' [data-role="sentrylist"]');
         $mbr=$wrap.find('[data-sentry-sort]');
         $mbr.sort(function(a,b){
            console.log('asdf...');
            var an = +a.getAttribute('data-sentry-sort');
            var bn = +b.getAttribute('data-sentry-sort');
            console.debug(an);
            console.debug(bn);
            return +a.getAttribute('data-sentry-sort') - +b.getAttribute('data-sentry-sort');
         }).appendTo($wrap);
         
         $wrap = dayUI.find('.mcal-day-items');
         $mbr=$wrap.find('[data-sentry-sort]');
         $mbr.sort(function(a,b){
            console.log('asdf...');
            var an = +a.getAttribute('data-sentry-sort');
            var bn = +b.getAttribute('data-sentry-sort');
            console.debug(an);
            console.debug(bn);
            return +a.getAttribute('data-sentry-sort') - +b.getAttribute('data-sentry-sort');
         }).appendTo($wrap);
      };
      
      var jqxhr = $.ajax({
         type: "GET",
         url: '/clientcal/api/calendar.php',
         data : {
            month : param.reqMoment.format('YM'),
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
            
            
            sentryUI.attr('data-sentry-sort',moment(sentry.time,moment.ISO_8601).format('X'));
            sentryUI.attr('data-sentryId',sentry.id);
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
            var dMoment=moment({date:$(this).data('dayofmonth'),year:$(this).data('year'),month:$(this).data('month')-1});
            if (!$(this).hasClass('mcal-day-selected')) {
               $(ccmcalWrapTarget+' .mcal-day').removeClass('mcal-day-selected');
               $(ccmcalWrapTarget+' .mcal-day-header').removeClass('mcal-selday-header');
               $(this).find('[data-seldayclass]').each(function() {
                  $(this).addClass($(this).data('seldayclass'));
               });
               $(this).addClass('mcal-day-selected');
               $('#cc-sched-monthpick').val(dMoment.format('YYYY-MM-DD'));
               return;
            }
            
            var modalDay = $(modalDayTarget);
            //data-dayofmonth="3" data-year="2016" data-month="11"
            
            modalDay.find('[data-day-formatdate]').each(function(){
               $(this).text(dMoment.format($(this).data('dayFormatdate')));
            });
            modalDay.find('[data-role="sentrylist"]').empty();
            if ( (typeof($(this).data('sentry'))!=='undefined') && Array.isArray($(this).data('sentry'))) {
               for(var i=0;i<$(this).data('sentry').length;i++) {
                  var cSentry=$(this).data('sentry')[i];
                  console.log('sentry...');
                  console.debug(cSentry);
                  var sUI = $(ccmcalTmplTarget+' [data-tmpl="sentry-dayview"]').clone();
                  
                  sUI.attr('data-sentry-dayview',cSentry.id);
                  sUI.attr('data-sentry-sort',moment(cSentry.time,moment.ISO_8601).format('X'));
                  
                  sUI.data('sentry',cSentry);
                  sUI.addClass('sentrytype-'+cSentry.type);
                  var badgeUI = badgeTmpl.find('[data-for-'+cSentry.type+']').clone();
                  sUI.find('[data-role="badge-wrap"]').append(badgeUI);
                  
                  sUI.find('[data-field]').each(function() {
                     if ($(this).data('field') && typeof(cSentry[$(this).data('field')])!=='undefined') {
                        $(this).text(cSentry[$(this).data('field')]);
                     }
                  });
                  
                  if (cSentry.label!=cSentry.customer_full_name) {
                     sUI.find('[data-role="label-display"]').text(cSentry.label);
                  }
                  
                  if (!cSentry.customer_phone) {
                     sUI.find('[data-role="customer-phone-display"]').hide();
                  }
                  if (!cSentry.directions) {
                     sUI.find('[data-role="directions-display"]').hide();
                  }
                  if (!cSentry.streetaddr) {
                     sUI.find('[data-role="streetaddr-display"]').hide();
                  }
                  
                  var citystatezip=[];
                  if (cSentry.city) citystatezip.push(cSentry.city);
                  if (cSentry.state) citystatezip.push(cSentry.state);
                  if (cSentry.zip) citystatezip.push(cSentry.zip);
                  
                  if (citystatezip.length) {
                     sUI.find('[data-role="citystatezip"]').text(citystatezip.join(' '));
                  } else {
                     sUI.find('[data-role="citystatezip"]').hide();
                  }
                  
                  sUI.find('[data-role="type-label"]').text(cSentry.type.replace('_',' '));
                  sUI.find('[data-sentry-dateformat]').each(function() {
                     $(this).text(moment(cSentry.time,moment.ISO_8601).format($(this).data('sentryDateformat')));
                  });
                  var modalEntry = $('#cc-sentry-modal');
                  sUI.on('click',function(e) {
                     e.preventDefault();
                     
                     var eSentry = $(this).data('sentry');
                     
                     
                     modalEntry.find('[data-field]').each(function() {
                        if ($(this).data('field') && typeof(eSentry[$(this).data('field')])!=='undefined') {
                           $(this).text(eSentry[$(this).data('field')]);
                        }
                     });
                     
                     modalEntry.find('[data-fieldval]').each(function() {
                        if ($(this).data('fieldval') && typeof(eSentry[$(this).data('fieldval')])!=='undefined') {
                           $(this).val(eSentry[$(this).data('fieldval')]);
                        }
                     });                     
                     //<span data-role="type-label"></span>
                     modalEntry.find('[data-role="type-label"]').text(eSentry.type.replace('_',' '));
                     
                     modalEntry.find('[data-role="badge-wrap"]').empty();
                     var badgeUI = badgeTmpl.find('[data-for-'+eSentry.type+']').clone();
                     modalEntry.find('[data-role="badge-wrap"]').append(badgeUI);
                     
                     eMoment = moment(eSentry.time,moment.ISO_8601);
                     modalEntry.find('#cc-sentry-time-input').val(eMoment.format('HH:mm'));
                     modalEntry.find('#cc-sentry-date-input').val(eMoment.format('YYYY-MM-DD'));
                     
                     if (eSentry.customer_phone) {
                        modalEntry.find('#cc-sentry-modal-tel-link').removeClass('disabled');
                        modalEntry.find('#cc-sentry-modal-tel-link').attr('href','tel:'+eSentry.customer_phone);
                     } else {
                        modalEntry.find('#cc-sentry-modal-tel-link').addClass('disabled');
                        modalEntry.find('#cc-sentry-modal-tel-link').attr('href','javascript:return false;');
                     }
                     
                     if ( (!eSentry.streetaddr) || (!eSentry.city) || (!eSentry.state)) {
                        modalEntry.find('#cc-sentry-modal-maps-link').attr('href','javascript:return false;');
                        modalEntry.find('#cc-sentry-modal-maps-link').addClass('disabled');
                     } else {
                        modalEntry.find('#cc-sentry-modal-maps-link').removeClass('disabled');
                        var statezip = [eSentry.state];
                        if (eSentry.zip) {
                           statezip.push(eSentry.zip);
                        }
                        statezip = statezip.join(' ');
                        var fullAddr = [eSentry.streetaddr,eSentry.city,statezip].join(', ');
                        //encodeURIComponent
                        modalEntry.find('#cc-sentry-modal-maps-link').attr('href','https://maps.google.com/maps?q='+encodeURIComponent(fullAddr));
                     }
                     
                     modalEntry.find('[data-role="save-changes"]').attr('disabled',true);
                     
                     var saveData = {};
                     modalEntry.find('[data-save]').each(function() {
                        if ($(this).data('save')) {
                           saveData[$(this).data('save')]=$(this).val();
                        }
                     });
                     
                     modalEntry.data('saveData',saveData);
                     modalEntry.find('[data-save]').off('change keyup paste');
                     modalEntry.find('[data-save]').on('change keyup paste',function(e) {
                        var foundChange = false;
                        modalEntry.find('[data-save]').each(function() {
                           if ($(this).data('save')) {
                              if (saveData[$(this).data('save')]!=$(this).val()) {
                                 foundChange=true;
                                 return false;
                              }
                           }
                        });
                        if (foundChange) {
                           modalEntry.find('[data-role="save-changes"]').attr('disabled',false);
                        } else {
                           modalEntry.find('[data-role="save-changes"]').attr('disabled',true);
                        }
                     });
                     
                     modalEntry.find('[data-role="save-changes"]').off('click');
                     modalEntry.find('[data-role="save-changes"]').on('click',function(e) {
                        e.preventDefault();
                        var requestData = {
                            id : eSentry.id
                        };
                        var foundChange = false;
                        modalEntry.find('[data-save]').each(function() {
                           if ($(this).data('save')) {
                              if (saveData[$(this).data('save')]!=$(this).val()) {
                                 foundChange=true;
                                 requestData[$(this).data('save')]=$(this).val();
                              }
                           }
                        });
                        var jqxhr = $.ajax({
                           type: "PUT",
                           url: '/clientcal/api/sentry.php',
                           data : requestData,
                        })
                        .done(function(data, textStatus, jqXHR) {
                           if (data && (typeof data==='object')) {
                              if (data.updated && (Array.isArray(data.updated)) && data.updated.length) {
                                 var changedDate=false;
                                 modalDay.find('[data-sentry-dayview="'+requestData.id+'"]').data('sentry',data.sentry);
                                 if ((data.updated.indexOf('time')!=-1) && (data.updated.indexOf('date')==-1)) {
                                    modalDay.find('[data-sentry-dayview="'+requestData.id+'"] [data-sentry-dateformat]').each(function() {
                                       if ($(this).data('sentryDateformat')) {
                                          $(this).text(moment(requestData.time,'HH:mm').format($(this).data('sentryDateformat')));
                                       }
                                    });
                                    var tMoment = moment(data.sentry.time,moment.ISO_8601);
                                    modalDay.find('[data-sentry-dayview="'+requestData.id+'"]').attr('data-sentry-sort',tMoment.format('X'));
                                    var tUI = $(ccmcalWrapTarget+' [data-dayofmonth="'+tMoment.format('D')+'"][data-year="'+tMoment.format('YYYY')+'"][data-month="'+tMoment.format('M')+'"]');
                                    tUI.find('[data-sentryId="'+requestData.id+'"]').attr('data-sentry-sort',tMoment.format('X'));
                                    sortDayModal(tUI);
                                    data.updated.splice(data.updated.indexOf('time'), 1);
                                 } else {
                                    if (data.updated.indexOf('date')!=-1) {
                                       modalDay.find('[data-sentry-dayview="'+requestData.id+'"]').remove();
                                       changedDate=true;
                                       data.updated.splice(data.updated.indexOf('date'), 1);
                                    }
                                 }
                                 if (!changedDate) {
                                    for(var i=0;i<data.updated.length;i++) {
                                       
                                       var saved=data.updated[i];
                                       
                                       
                                       
                                    }
                                 }
                              }
                           }
                           modalEntry.modal('hide');
                        })
                        .fail(function(jqXHR, textStatus, errorThrown) {
                           console.log('failed');
                        }); 
                     });
                     
                     modalEntry.modal('show');
                  });
                  modalDay.find('[data-role="sentrylist"]').append(sUI);
               }
            }
//            console.log('sentries...');
//            console.debug($(this).data('sentry'));
            modalDay.modal('show');
         });
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
         console.log('failed');
      }); 
   };
   
   //#cc-sched-wrap .mcal
   responsiveCal().generate('month');
   
   populateMonth({monthWrapTarget: '#cc-sched-wrap .mcal'});
   
   
   
   //data-role="prior-month"
   $(ccmcalWrapTarget+' [data-role="prior-month"]').off('click');
   $(ccmcalWrapTarget+' [data-role="prior-month"]').on('click',function(e) {
      e.preventDefault();
      var reqMoment = $(ccmcalTmplTarget).data('reqMoment');
      reqMoment.add(-1,'months');
      
      responsiveCal({reqMoment: reqMoment}).generate('month');
      
      populateMonth({reqMoment: reqMoment,monthWrapTarget: '#cc-sched-wrap .mcal'});
   });
   
   $(ccmcalWrapTarget+' [data-role="next-month"]').off('click');
   $(ccmcalWrapTarget+' [data-role="next-month"]').on('click',function(e) {
      e.preventDefault();
      var reqMoment = $(ccmcalTmplTarget).data('reqMoment');
      reqMoment.add(1,'months');
      
      responsiveCal({reqMoment: reqMoment}).generate('month');
      
      populateMonth({reqMoment: reqMoment,monthWrapTarget: '#cc-sched-wrap .mcal'});
   });   

   
   $('#cc-sched-monthpick').on('change',function(e) {
      e.preventDefault();
      console.log('changed...');
      var reqMoment=moment($(this).val(),"YYYY-MM-DD");
      if (reqMoment.isValid()) {
         responsiveCal({reqMoment: reqMoment}).generate('month');
         populateMonth({reqMoment: reqMoment,monthWrapTarget: '#cc-sched-wrap .mcal'});
      }
   });
   
   /*
    * multiple modals fix
    */
   $(document).on({
      'shown.bs.modal': function () {
          var zIndex = 1040 + (10 * $('.modal:visible').length);
          $(this).css('z-index', zIndex);
          $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
      },
      'hidden.bs.modal': function() {
          if ($('.modal:visible').length > 0) {
              // restore the modal-open class to the body element, so that scrolling works
              // properly after de-stacking a modal.
             $(document.body).addClass('modal-open');
          }
      }
  }, '.modal');
   
   
   
   
   
})();