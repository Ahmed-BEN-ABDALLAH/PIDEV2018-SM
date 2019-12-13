(function($){  
  "use strict";
  $.fn.kwikEventsCalendar = function( options ){
//setTimeout(reloadSousCategorie, 1500);
    var settings = $.extend({
      defaultView:'month',
      defaultLang:'fr',
      eventCurrency:'DT',
      eventsList:false,
      eventLocation:false,
      eventLocationKey:false,
      featuredBackground:'#F1F1F1',
      featuredEventCallback:false,
      featuredEventID:false,
      featuredPosition:'pos-top',
      featuredWidth:'30',
      maxDate:false,
      minDate:false,
      startDate:0
    }, options );

    if( options == undefined ){ options = options; }

    function compare(a,b) {
      if (a.eventDate < b.eventDate)
        return -1;
      if (a.eventDate > b.eventDate)
        return 1;
        return 0;
    }
   if( settings.eventsList != undefined ){
     settings.eventsList.sort(compare);
   }

    moment.locale(settings.defaultLang);

    var kwikStart = settings.startDate;
    var kwikDate = '';

    function launchIntoFullscreen(element){
      if(element.requestFullscreen){
        element.requestFullscreen();
      } else if(element.mozRequestFullScreen){
        element.mozRequestFullScreen();
      } else if(element.webkitRequestFullscreen){
        element.webkitRequestFullscreen();
      } else if(element.msRequestFullscreen){
        element.msRequestFullscreen();
      }
    }

    function exitFullscreen(){
      if(document.exitFullscreen){
        document.exitFullscreen();
      } else if(document.mozCancelFullScreen){
        document.mozCancelFullScreen();
      } else if(document.webkitExitFullscreen){
        document.webkitExitFullscreen();
      }
    }

    function hexToRgb(hex){
      var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
      hex = hex.replace(shorthandRegex,function(m, r, g, b) { return r + r + g + g + b + b; });
      var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
      return result ? { r: parseInt(result[1], 16), g: parseInt(result[2], 16), b: parseInt(result[3], 16) } : null;
    }

    function shadeColor(color,percent){
      var R = parseInt(color.substring(1,3),16);
      var G = parseInt(color.substring(3,5),16);
      var B = parseInt(color.substring(5,7),16);
      R = parseInt(R * (100 + percent) / 100);
      G = parseInt(G * (100 + percent) / 100);
      B = parseInt(B * (100 + percent) / 100);
      R = (R<255)?R:255;
      G = (G<255)?G:255;
      B = (B<255)?B:255;
      var RR = ((R.toString(16).length==1)?"0"+R.toString(16):R.toString(16));
      var GG = ((G.toString(16).length==1)?"0"+G.toString(16):G.toString(16));
      var BB = ((B.toString(16).length==1)?"0"+B.toString(16):B.toString(16));
      return "#"+RR+GG+BB;
    }

    function textBrightness(hex) {
      var brightness = (hexToRgb(hex).r * 299) + (hexToRgb(hex).g * 587) + (hexToRgb(hex).b * 114);
      brightness = brightness / 255000;
      if (brightness >= 0.5){
        var textColor = '#000000';
      } else {
        var textColor = '#FFFFFF';
      }
      return textColor;
    }

    var basetemplate = function template(){
      var i = 40;
      var textColor = textBrightness(shadeColor(settings.featuredBackground,-20));
      var featuredTextColor = textBrightness(settings.featuredBackground);
      var backgroundColor = shadeColor(settings.featuredBackground,-20);
      var txt = '<div class="kwik-calendar-menu">'
      txt += '<button data-view="filter" title="Filter events" style="background:'+backgroundColor+';color:'+textColor+'"><i class="fa fa-filter"></i></button>'
      txt += '<div id="kwik-filter-menu">Filter events by category'
      txt += '<a href="javascript:void(0);" data-category="kwik-viewall">View All</a>'
      $.each(settings.eventCategories,function(i,v){
        txt += '<a href="javascript:void(0);" data-category="'+i.replace(/\s+/g, '-').toLowerCase()+'">'+i+'</a>'
      })
      txt += '</div>'
      txt += '<button data-view="month" title="Month view" style="background:'+backgroundColor+';color:'+textColor+'"><i class="fa fa-calendar"></i></button>'
      txt += '<button data-view="agenda" title="Agenda view" style="background:'+backgroundColor+';color:'+textColor+'"><i class="fa fa-th"></i></button>'
      txt += '<button data-view="fullScreen" title="Full screen" style="background:'+backgroundColor+';color:'+textColor+'"><i class="fa fa-external-link"></i></button>'
      txt += '</div>'
      txt += '<div class="kwik-featured" style="background:'+settings.featuredBackground+';color:'+featuredTextColor+';width:'+settings.featuredWidth+'%">'
      txt += '<div class="kwik-featured-header" style="background:'+backgroundColor+';color:'+textColor+'">'
      txt += '<div class="kwik-featured-header-left">'
      txt += '<a href="javascript:void(0);" data-direction="prev"><i class="fa fa-chevron-left"></i></a>'
      txt += '<a href="javascript:void(0);" data-direction="next"><i class="fa fa-chevron-right"></i></a>'
      txt +='</div>'
      txt += '<div class="kwik-featured-header-right kwik-calendar-active-date"></div>'
      txt +='</div>'
      txt += '<div class="kwik-featured-content">'
      if(settings.featuredEventID){
      txt += '<div class="kwik-featured-content-image">'
      txt += '<img src="'+settings.eventsList[settings.featuredEventID].eventImage+'" class="kwik-img-fluid"/>'
      txt += '<div class="kwik-calendar-agenda-date">'
      txt += '<div class="kwik-calendar-agenda-date-holder" style="background:'+backgroundColor+';color:'+textColor+'">'
      txt += '<div><small>'+moment(parseInt(settings.eventsList[settings.featuredEventID].eventDate,10)).format('ddd')+'</small></div>'
      txt += '<div>'+moment(parseInt(settings.eventsList[settings.featuredEventID].eventDate,10)).format('Do')+'</div>'
      txt += '<div><small>'+moment(parseInt(settings.eventsList[settings.featuredEventID].eventDate,10)).format('MMM')+'</small></div>'
      txt += '</div>';
      txt += '</div>';
      txt += '<div class="countdown" style="color:'+featuredTextColor+'" data-id="'+i+'">&nbsp;<br/>&nbsp;</div>';
      txt += '</div>';
      txt += '<div class="kwik-calendar-event-details" data-id="'+settings.featuredEventID+'">'
      txt += '<div class="kwik-calendar-event-title">'+settings.eventsList[settings.featuredEventID].eventTitle+'</div>'
      txt += settings.eventsList[settings.featuredEventID].eventDescription.substr(0,250);
      txt += '<hr/>'
      txt += '<i class="fa fa-clock-o"></i> '+moment(parseInt(settings.eventsList[settings.featuredEventID].eventDate,10)).format('HH:mm dddd Do MMMM YYYY')
      txt += '<hr/>'
      txt += '<i class="fa fa-map-marker"></i> '+settings.eventsList[settings.featuredEventID].eventLocation
      txt += '<hr/>'
      txt += '<i class="fa fa-ticket"></i> '+settings.eventsList[settings.featuredEventID].eventBookingURL
      txt += '<hr/>'
      txt += '<button id="singledetails" data-id="'+settings.featuredEventID+'">More</button>'
      txt += '</div>'
      }
      txt += '</div>'
      txt +='</div>';
      txt += '<div class="kwik-calendar" data-view="month"></div>';
      return txt;
    }

    function cardtemplate(v,i){
      var txt = '<div class="kwik-calendar-agenda-list '+v.eventCategory.replace(/\s+/g, '-').toLowerCase()+'"" data-id="'+i+'" data-category="'+v.eventCategory+'">'
      txt += '<img src="'+v.eventImage+'" class="kwik-img-fluid"/>'
      txt += '<div class="kwik-calendar-agenda-date">'
      txt += '<div class="kwik-calendar-agenda-date-holder" style="background:'+ settings.eventCategories[v.eventCategory] +';color:'+textBrightness(settings.eventCategories[v.eventCategory])+'" >'
      txt += '<div><small>'+moment(parseInt(v.eventDate,10)).format('ddd')+'</small></div>'
      txt += '<div>'+moment(parseInt(v.eventDate,10)).format('Do')+'</div>'
      txt += '<div><small>'+moment(parseInt(v.eventDate,10)).format('MMM')+'</small></div>'
      txt += '</div>';
      txt += '</div>';
      txt += '<div class="countdown" data-id="'+i+'">&nbsp;<br/>&nbsp;</div>';
      txt += '<div class="kwik-calendar-event-details" data-id="'+i+'">'
      txt += '<div class="kwik-calendar-event-title">'+v.eventTitle+'</div>'
      txt += v.eventDescription.substr(0,250);
      txt += '<hr/>'
      txt += '<i class="fa fa-clock-o"></i> '+moment(parseInt(v.eventDate,10)).format('HH:mm dddd Do MMMM YYYY')
      txt += '<hr/>'
      txt += '<i class="fa fa-map-marker"></i> '+v.eventLocation
      txt += '<hr/>'
      txt += '<i class="fa fa-ticket"></i> '+v.eventBookingURL
      txt += '<hr/>'
      txt += '<button id="singledetails" data-id="'+i+'">More</button>'
      txt += '</div>'
      txt += '</div>'
      return txt;
    }

    function cardSingleTemplate(str){
      var txt = '<div class="kwik-calendar-agenda-list kwik-single-event '+str.replace(/\s+/g, '-').toLowerCase()+'" data-id="'+str+'" >'
      txt += '<img src="'+settings.eventsList[str].eventImage+'" class="kwik-img-fluid"/>'
      txt += '<div class="kwik-calendar-agenda-date">'
      txt += '<div class="kwik-calendar-agenda-date-holder" style="background:'+ settings.eventCategories[settings.eventsList[str].eventCategory] +';color:'+textBrightness(settings.eventCategories[settings.eventsList[str].eventCategory])+'" >'
      txt += '<div><small>'+moment(parseInt(settings.eventsList[str].eventDate,10)).format('HH:mm')+'</small></div>'
      txt += '<div><small>'+moment(parseInt(settings.eventsList[str].eventDate,10)).format('dddd')+'</small></div>'
      txt += '<div>'+moment(parseInt(settings.eventsList[str].eventDate,10)).format('Do')+'</div>'
      txt += '<div><small>'+moment(parseInt(settings.eventsList[str].eventDate,10)).format('MMMM')+'</small></div>'
      txt += '</div>';
      txt += '</div>';
      txt += '<div class="countdown" data-id="'+str+'">&nbsp;<br/>&nbsp;</div>';
      txt += '<div class="kwik-calendar-event-title">'+settings.eventsList[str].eventTitle+'</div>'
      txt += '<div class="kwik-calendar-event-details">'
      txt += '<div class="kwik-calendar-event-tabs" data-id="'+str+'">'
      txt += '<a href="#home-'+str+'" class="active" data-id="'+str+'">Details</a>'
      txt += '<a href="#location-'+str+'" data-id="'+str+'">Location</a>'
      txt += '<a href="#booking-'+str+'" data-id="'+str+'">Booking</a>'
      txt += '</div>'
      txt += '<div class="kwik-calendar-tab-content" data-id="'+str+'">'
      txt += '<div class="kwik-calendar-tab-pane active" id="home-'+str+'" role="tabpanel">'+settings.eventsList[str].eventDescription+'</div>'
      txt += '<div class="kwik-calendar-tab-pane" id="location-'+str+'" role="tabpanel">'+settings.eventsList[str].eventLocation
      txt += '<img src="https://maps.googleapis.com/maps/api/staticmap?center='+settings.eventsList[str].eventLatLng.lat+','+settings.eventsList[str].eventLatLng.lng+'&zoom=13&size=900x300&maptype=roadmap&markers=color:red%7Clabel:S%7C'+settings.eventsList[str].eventLatLng.lat+','+settings.eventsList[str].eventLatLng.lng+'&key='+settings.eventsLocationKey+'"/>'
      txt += '</div>'
      txt += '<div class="kwik-calendar-tab-pane" id="booking-'+str+'" role="tabpanel">'+settings.eventsList[str].eventBookingURL+'<p/>'+settings.eventsList[str].eventBookingDetails+'</div>'
      txt += '<div id="kwik-share-buttons">'
      //Digg -->
      txt += '<a href="http://www.digg.com/submit?url='+window.location.href +'" class="kwik-btn btn-digg" target="_blank" data-toggle="tooltip" title="Share on Digg">'
      txt += '<i class="fa fa-digg"></i>'
      txt += '</a>'
      //Email -->
      txt += '<a href="mailto:?Subject='+moment(parseInt(settings.eventsList[str].eventDate,10)).format('HH:mm dddd Do MMMM YYYY')+' - '+settings.eventsList[str].eventTitle+'&amp;Body='+settings.eventsList[str].eventDescription+'" class="kwik-btn btn-email" data-toggle="tooltip" title="Share by email">'
      txt += '<i class="fa fa-envelope"></i>'
      txt += '</a>'
      //Facebook -->
      txt += '<a href="http://www.facebook.com/sharer.php?u='+settings.eventsList[settings.featuredEventID].eventBookingURL+'" target="_blank" class="kwik-btn btn-facebook" data-toggle="tooltip" title="Share on Facebook">'
      txt += '<i class="fa fa-facebook"></i>'
      txt += '</a>'
      //Google+ -->
      txt += '<a href="https://plus.google.com/share?url='+settings.eventsList[settings.featuredEventID].eventBookingURL+'" target="_blank" class="kwik-btn btn-google" data-toggle="tooltip" title="Share on Google+">'
      txt += '<i class="fa fa-google-plus"></i>'
      txt += '</a>'
      //LinkedIn -->
      txt += '<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url='+settings.eventsList[settings.featuredEventID].eventBookingURL+'" target="_blank" class="kwik-btn btn-linkedin" data-toggle="tooltip" title="Share on Linkedin">'
      txt += '<i class="fa fa-linkedin"></i>'
      txt += '</a>'
      //Reddit -->
      txt += '<a href="http://reddit.com/submit?url='+settings.eventsList[settings.featuredEventID].eventBookingURL+'&amp;title='+moment(parseInt(settings.eventsList[str].eventDate,10)).format('HH:mm dddd Do MMMM YYYY')+' - '+settings.eventsList[str].eventTitle+'" target="_blank" class="kwik-btn btn-reddit" data-toggle="tooltip" title="Share on Reddit">'
      txt += '<i class="fa fa-reddit-alien"></i>'
      txt += '</a>'
      //StumbleUpon-->
      txt += '<a href="http://www.stumbleupon.com/submit?url='+settings.eventsList[settings.featuredEventID].eventBookingURL+'&amp;title='+moment(parseInt(settings.eventsList[str].eventDate,10)).format('HH:mm dddd Do MMMM YYYY')+' - '+settings.eventsList[str].eventTitle+'" target="_blank" class="kwik-btn btn-stumbleupon" data-toggle="tooltip" title="Share on Stumbleupon">'
      txt += '<i class="fa fa-stumbleupon"></i>'
      txt += '</a>'
      //Tumblr-->
      txt += '<a href="http://www.tumblr.com/share/link?url='+settings.eventsList[settings.featuredEventID].eventBookingURL+'&amp;title='+moment(parseInt(settings.eventsList[str].eventDate,10)).format('HH:mm dddd Do MMMM YYYY')+' - '+settings.eventsList[str].eventTitle+'" target="_blank" class="kwik-btn btn-tumblr" data-toggle="tooltip" title="Share on Tumblr">'
      txt += '<i class="fa fa-tumblr"></i>'
      txt += '</a>'
      //Twitter -->
      txt += '<a href="https://twitter.com/intent/tweet?text=The%20kwik%20events%20calendar%20is%20a%20great%20events%20calendar%20jQuery%20plugin&amp;hashtags=kwikBitz" target="_blank" class="kwik-btn btn-twitter" data-toggle="tooltip" title="Share on Twitter">'
      txt += '<i class="fa fa-twitter"></i>'
      txt += '</a>'
      txt += '</div>'
      txt += '</div>'
      txt += '</div>'
      txt += '</div>'
      return txt;
    }

    function popoverTemplate(str){
      var txt = '<div class="kwik-popover-content">'
      txt += '<img src="'+settings.eventsList[str].eventImage+'" class="kwik-img-fluid"/>'
      txt += '<p/><div class="kwik-calendar-event-title">'+settings.eventsList[str].eventTitle+'</div><p/>'
      txt += settings.eventsList[str].eventDescription.substr(0,150);
      txt += '<hr/>'
      txt += '<i class="fa fa-clock-o"></i> '+moment(parseInt(settings.eventsList[str].eventDate,10)).format('HH:mm dddd Do MMMM YYYY')
      txt += '<hr/>'
      txt += '<i class="fa fa-map-marker"></i> '+settings.eventsList[str].eventLocation
      txt += '<hr/>'
      txt += '<i class="fa fa-ticket"></i> '+settings.eventsList[str].eventBookingURL
      txt += '</div>'
      return txt;
    }

    function msnry(){
     if( $('.kwik-calendar').width() > 768 &&  $('.kwik-calendar-agenda-list').length > 1 ){ $('.kwik-calendar-agenda-list').css({'width':($('.kwik-calendar').width()/2)-20,'margin':'0 10px 10px 10px' }) } else { $('.kwik-calendar-agenda-list').css({'width':'100%' ,'margin':'0'  })  }
     $('.kwik-calendar-masonry').isotope({
        itemSelector:'.kwik-calendar-agenda-list',
        percentPosition:true,
        masonry: {
          columnWidth:'.kwik-calendar-agenda-list'
        }
      });
     $('.kwik-calendar-masonry').imagesLoaded().progress( function() {
       $('.kwik-calendar-masonry').isotope('layout');
     });
    }

    function calendar_month(str){
      $('.kwik-calendar-active-date').html(moment().add(kwikStart,'month').format('MMMM YYYY'))
      var kwikMonth = parseInt(moment().add(kwikStart,'month').format('x'),10);
      var mondayLast = moment(kwikMonth).subtract(1,'months').endOf('month').startOf('isoweek').format('DD');
      var daysInLastMonth = moment(kwikMonth).subtract(1,'months').daysInMonth();
      var daysInCurrentMonth = moment(kwikMonth).daysInMonth();
      var s = 0;
      var dateArray = {}
      if( jQuery('.kwik-calendar').width() > 700 ){
        var dayText = moment.weekdays();
      } else {
        var dayText = moment.weekdaysShort();
      }
      for (var i = mondayLast; i < daysInLastMonth+1; i++){
        var m = parseInt(moment(kwikMonth).subtract(1,'months').format('MM'),10);
        var y = parseInt(moment(kwikMonth).subtract(1,'months').format('YYYY'),10);
        dateArray[s] = moment(''+m+' '+i+' '+y+'' , 'MM DD YYYY').startOf('day').format('x');
        s++;
      }
      if(s >= 7){
        dateArray = {};
      }
      for (var i = 1; i < daysInCurrentMonth+1; i++){
        var m = parseInt(moment(kwikMonth).format('MM'),10);
        var y = parseInt(moment(kwikMonth).format('YYYY'),10);
        dateArray[s] = moment(''+m+' '+i+' '+y+'','MM DD YYYY').startOf('day').format('x');
        s++;
      }
      if( Object.keys(dateArray).length < 35 ){
        var d = 1
        for (var i = Object.keys(dateArray).length; i < 35; i++){
          var m = parseInt(moment(kwikMonth).add(1,'months').format('MM'),10);
          var y = parseInt(moment(kwikMonth).add(1,'months').format('YYYY'),10);
          dateArray[s] = moment(''+m+' '+d+' '+y+'','MM DD YYYY').startOf('day').format('x');
          s++;
          d++;
        }
      } else if ( Object.keys(dateArray).length < 42  ){
        var d = 1
        for (var i = Object.keys(dateArray).length; i < 42; i++){
          var m = parseInt(moment(kwikMonth).add(1,'months').format('MM'),10);
          var y = parseInt(moment(kwikMonth).add(1,'months').format('YYYY'),10);
          dateArray[s] = moment(''+m+' '+d+' '+y+'','MM DD YYYY').startOf('day').format('x');
          s++;
          d++;
        }
      }
      var txt = '<div class="clearfix kwik-calendar-month">';
      for (var i = 0; i < dayText.length; i++){
        if( i == 0 ){ var addClass = 'col1'; } else {  var addClass =''; }
        if( dayText[i+1] != undefined ){
          txt += '<div class="kwik-calendar-month-header '+addClass+'">'+dayText[i+1] +'</div>';
        } else {
          txt += '<div class="kwik-calendar-month-header '+addClass+'">'+dayText[0] +'</div>';
        }
      }
      for (var key in dateArray){
        var c = 0;
        if(moment(parseInt(dateArray[key],10)).weekday() == 1 ) { var addClass = 'col1';} else {  var addClass =''; }
        txt += '<div class="kwik-calendar-month-day clearfix '+addClass+'" data-id="'+moment(parseInt(dateArray[key],10)).format('x')+'">'
        txt += '<div class="kwik-calendar-date">'+moment(parseInt(dateArray[key],10)).format('D')+'</div>'
        txt += '</div>';
      }
      $('.kwik-calendar').html(txt).promise().done( function(){
        getEvents()
        countDown();
      });
     }

    function getCalendarList(str){
      if( str != '' ){
        str = str;
      } else {
        str = moment().startOf('day').format('x')
      }
      kwikDate = str;
      jQuery('.kwik-calendar-nav').attr('data-view','list')
      jQuery('#month_menu').removeClass('in')
      jQuery('#list_menu').addClass('in')
      jQuery('#day-link').html(moment(parseInt(str,10)).format('ddd Do MMMM YYYY'))
      var kcldw = $('.kwik-calendar').width()/9;
      var dateArray = {}
      dateArray[0] = moment(parseInt(str,10)).subtract(4,'days').format('x');
      dateArray[1] = moment(parseInt(str,10)).subtract(3,'days').format('x');
      dateArray[2] = moment(parseInt(str,10)).subtract(2,'days').format('x');
      dateArray[3] = moment(parseInt(str,10)).subtract(1,'days').format('x');
      dateArray[4] = str;
      dateArray[5] = moment(parseInt(str,10)).add(1,'days').format('x');
      dateArray[6] = moment(parseInt(str,10)).add(2,'days').format('x');
      dateArray[7] = moment(parseInt(str,10)).add(3,'days').format('x');
      dateArray[8] = moment(parseInt(str,10)).add(4,'days').format('x');
      if( jQuery('.kwik-calendar').width() < 700 ){
        delete dateArray[0];
        delete dateArray[1];
        delete dateArray[7];
        delete dateArray[9];
        var kcldw = $('.kwik-calendar').width()/6;
        }
      var txt = '<div class="kwik-day-holder" style="width:'+ $('.kwik-calendar').width() +'px">';
      for (var key in dateArray){
        if( moment(parseInt(dateArray[key],10)).startOf('day').format('x') == str ){ var addClass= 'active'; } else { var addClass= ''; }
        txt += '<div class="kwik-calendar-list-date '+addClass+'" style="width:'+kcldw+'px" data-id="'+moment(parseInt(dateArray[key],10)).format('x')+'" >'+moment(parseInt(dateArray[key],10)).format('ddd Do')+'</div>';
      }
      txt += '</div>';
      txt += '<ul></ul>';
      $('.kwik-calendar').html(txt).promise().done( function(){
        getDayEventsList(str)
        countDown();
      });
    }

    function getDayEventsList(str){
       var txt = '<div class="kwik-calendar-masonry">'
        $.each(settings.eventsList,function(i,v){
        if ( moment(parseInt(v.eventDate,10)).startOf('day').format('x')  ==  str ) {
          $(v).each(function(key,val){
            txt += cardtemplate(v,i)
          })
        }
      })
      txt += '</div>'
      $('.kwik-calendar').html(txt).promise().done( function(){
        msnry();
        countDown();
      });
    }

    function getEventsList(){
      $('.kwik-calendar-active-date').html(moment().add(kwikStart,'month').format('MMMM YYYY'))
      var kwikMonth = parseInt(moment().add(kwikStart,'month').format('x'),10);
      var kwikMonthStart = parseInt(moment(kwikMonth).startOf('month').format('x'),10);
      var kwikMonthEnd = parseInt(moment(kwikMonth).endOf('month').format('x'),10);
      var txt = '<div class="kwik-calendar-masonry">'
      $.each(settings.eventsList,function(i,v){
        if ( parseInt(v.eventDate,10)  >= kwikMonthStart && parseInt(v.eventDate,10) <= kwikMonthEnd) {
          $(v).each(function(key,val){
            txt += cardtemplate(v,i)
          })
        }
      })
      txt += '</div>'
      $('.kwik-calendar').html(txt).promise().done( function(){
        msnry();
        countDown();
      });
    }

    function countDown(){
      $('.countdown').each(function(i,v){
        var id = $(this).attr('data-id')
        var eventTime = settings.eventsList[id].eventDate;
        var currentTime = moment().format('x');
        var diffTime = eventTime - currentTime;
        var duration = moment.duration(diffTime,'milliseconds');
        var interval = 1000;
        setInterval(function(){
          duration = moment.duration(duration.asSeconds() - 1, 'seconds');
          var m = duration.months();
          if( m > 0 ){
            if(m > 1) {
              m = '<span>'+m+'<br/><small>Months</small></span>';
            } else {
              m = '<span>'+m+'<br/><small>Month</small></span>';
            }
          } else { m = ''; }
          var w = duration.weeks();
          if( w > 0 ){
            if(w > 1){
              w = '<span>'+w+'<br/><small>Weeks</small></span>';
            } else {
               w = '<span>'+w+'<br/><small>Week</small></span>';
            }
          } else { w = ''; }
          var d = duration.days();
          if( d > 0 ){
            if(d > 1){
              d = '<span>'+d+'<br/><small>Days</small></span>';
            } else {
              d = '<span>'+d+'<br/><small>Day</small></span>';
            }
          } else { d = ''; }
          var h = duration.hours();
          if( h > 0 ){
            if(h > 1){
              h = '<span>'+h+'<br/><small>Hours</small></span>';
            } else {
              h = '<span>'+h+'<br/><small>Hour</small></span>';
            }
          } else { h = ''; }
          var mi = duration.minutes();
          mi = '<span>'+mi+'<br/><small>Min</small></span>';
          var s = duration.seconds();
          s = '<span>'+s+'<br/><small>Sec</small></span>';
          if( duration > 0 ){
            jQuery('.countdown[data-id="'+id+'"]').html(m+' '+w+' '+d+' '+h+' '+mi+' '+s);
          } else {
            jQuery('.countdown[data-id="'+id+'"]').html('<span>Expired</span>');
          }
        },interval);
      })
    }

    function getEvents(){
      $('.kwik-calendar-month-day[data-id]:visible,.kwik-calendar-list[data-id]:visible').each(function(i,v){
        var start = moment(parseInt($(this).attr('data-id'),10)).format('x');
        var end = moment(parseInt($(this).attr('data-id'),10)).endOf('day').format('x');
        var x = 0;
        var dataarray = {};
        $.each(settings.eventsList,function(i,v){
          if ( v.eventDate >= start && v.eventDate <= end ){
            $('[data-id="'+start+'"] .kwik-calendar-date').addClass('active').html('<a href="javascript:void(0);" data-id="'+start+'">'+moment(parseInt(start,10)).format('D')+'</a>');
            $('.kwik-calendar-month-day[data-id="'+start+'"]').append('<div class="kwik-calendar-event '+v.eventCategory.replace(/\s+/g, '-').toLowerCase()+'" style="background:'+settings.eventCategories[v.eventCategory] +';color:'+textBrightness(settings.eventCategories[v.eventCategory])+'" data-id="'+i+'" data-event-id="'+v.eventDate+'">'+moment(parseInt(v.eventDate,10)).format('HH:mm')+' - '+v.eventTitle+'</div>')
          }
        })
      })
      var highestBox = 0;
      $('.kwik-calendar-month-day').each(function(){
        if($(this).height() > highestBox) {
          highestBox = $(this).height();
        }
      });
      $('.kwik-calendar-month-day',this).height(highestBox);
    }

   function initNav(){
     if($('.kwik-calendar-holder').width() < 900){  jQuery('.kwik-calendar-holder').attr('data-position','pos-top') } else { jQuery('.kwik-calendar-holder').attr('data-position',settings.featuredPosition) }
     $('a[data-direction]').on('click',function(e){
       e.stopPropagation();
       e.preventDefault();
       switch ($(this).attr('data-direction')){
         case 'next':
           if( settings.maxDate && settings.maxDate > kwikStart ){
             kwikStart = kwikStart+1;
           } else if( settings.maxDate && settings.maxDate < kwikStart  ){
             kwikStart = kwikStart;
           } else {
            kwikStart = kwikStart+1;
         }
         break;
         case 'prev':
           if( settings.minDate && settings.minDate < kwikStart ){
             kwikStart = kwikStart-1;
           } else if( settings.minDate && settings.minDate > kwikStart  ){
             kwikStart = kwikStart;
           } else {
             kwikStart = kwikStart-1;
           }
          break;
       }
       switch( jQuery('.kwik-calendar').attr('data-view') ){
       case 'month':
         $('.kwik-calendar').attr('data-view','month').promise().done( calendar_month(kwikStart) );
       break;
       case 'agenda':
         $('.kwik-calendar').attr('data-view','agenda').promise().done( getEventsList(kwikStart) );
       break;
      }
      return false;
     })

     $(document).on('click','.kwik-calendar-date a',function(e){
       e.preventDefault();
       getDayEventsList($(this).attr('data-id'));
       return false;
     })

     $('#list_menu a[data-direction]').on('click',function(e){
      e.stopPropagation();
      e.preventDefault();
      switch ($(this).attr('data-direction')){
         case 'next':
           kwikDate = moment(parseInt(kwikDate,10)).add(1,'days').format('x');
         break;
         case 'prev':
           kwikDate = moment(parseInt(kwikDate,10)).subtract(1,'days').format('x');
         break;
       }
      $('.kwik-calendar').attr('data-view','list').promise().done( getCalendarList(kwikDate) );
      return false;
     })

     $('button[data-view]').on('click',function(e){
       e.stopPropagation();
       e.preventDefault();
       $('a[data-direction]').attr('data-view',$(this).attr('data-view') );
       switch($(this).attr('data-view')){
         case 'month':
          $('.kwik-calendar').attr('data-view','month').promise().done( calendar_month(kwikStart) );
         break;
         case 'agenda':
           $('.kwik-calendar').attr('data-view','agenda').promise().done( getEventsList(kwikStart) );
         break;
         case 'filter':
           $('#kwik-filter-menu').css({'left':($(this).position().left+($(this).width())),'margin-left':'-7.5rem' }).slideToggle();
         break;
         case 'fullScreen':
           if($('.kwik-calendar-holder').hasClass('fullscreen')) {
             exitFullscreen();
           } else {
             launchIntoFullscreen(document.getElementById("kwik-calendar-holder"));
           }
           $('.kwik-calendar-holder').toggleClass('fullscreen');
         break;
       }
       return false;
     })
   }

   if(settings.defaultView == 'month' ){
     this.html( basetemplate ).promise().done(  calendar_month(0) );
     $('.kwik-calendar').attr('data-view','month')
   } else {
     this.html( basetemplate ).promise().done(  getEventsList(0) );
     $('.kwik-calendar').attr('data-view','agenda')
   }

   initNav()

   jQuery(window).resize(function(){
     switch( $('.kwik-calendar').attr('data-view') ) {
       case 'month':
         $('.kwik-calendar-holder').html( basetemplate ).promise().done( calendar_month(kwikStart) );
       break;
       case 'agenda':
          $('.kwik-calendar-holder').html( basetemplate ).promise().done( getEventsList(kwikStart)  );
       break;
     }
     initNav()
   })

   $(document).on('click','.kwik-calendar-event-tabs a',function(e){
     e.preventDefault();
     $('.kwik-calendar-tab-content[data-id="'+$(this).attr('data-id')+'"] .kwik-calendar-tab-pane').removeClass('active');
     $($(this).attr('href')).addClass('active');
     msnry();
     return false;
   })

   $(document).on('click','#singledetails,.kwik-calendar-event',function(e){
     $('.kwik-calendar').html(cardSingleTemplate($(this).attr('data-id'))).promise().done( function(){
       countDown();
     });
     return false;
   })

   $(document).on('click','#kwik-filter-menu a',function(e){
     e.preventDefault();
     var id = $(this).attr('data-category');
     if(id == 'kwik-viewall'){
       switch( $('.kwik-calendar').attr('data-view') ) {
         case 'month':
           $('.kwik-calendar-event,.kwik-calendar-agenda-list').show();
           msnry();
           $('#kwik-filter-menu:visible').slideToggle();
         break;
         case 'agenda':
           $('.kwik-calendar-masonry').isotope({ filter: '*' });
         break;
       }
     } else {
       switch( $('.kwik-calendar').attr('data-view') ) {
         case 'month':
           $('.kwik-calendar-event').hide();
           $('.kwik-calendar-event.'+id).show();
           msnry();
         break;
         case 'agenda':
           var filterValue = '.'+id;
           $('.kwik-calendar-masonry').isotope({ filter: filterValue });
         break;
       }
     }
     $('#kwik-filter-menu:visible').slideToggle();
     return false;
   })

   $(document).on('mouseover','.kwik-calendar-event',function(e){
     e.preventDefault();
     var id = $(this).attr('data-id');
     $(this).append('<div id="kwikPopover">'+popoverTemplate(id)+'</div>');
     return false;
   })

   $(document).on('mouseout','.kwik-calendar-event',function(e){
     e.stopPropagation();
     $('#kwikPopover:visible').remove();
   })

 }

})(jQuery);