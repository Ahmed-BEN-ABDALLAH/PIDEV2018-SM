(function($){  
  "use strict";

  $.fn.kwikDatePicker = function( options ){

    var settings = $.extend({
      kwikColour:'#CCCCCC',
      defaultLang:'en',
      dayTitleFormat:'DD MMMM YYYY',
      monthTitleFormat:'MMMM YYYY',
      timeViewAllow:true,
      startDate:false,
      minDate:false,
      maxDate:false,
      startHourTime:'9',
      endHourTime:'17',
      startMinuteTime:'0',
      endMinuteTime:'0',
      sessionTime:'15',
      updateTimeFormat:'HH:mm DD MMMM YYYY',
      updateDayFormat:'DD MMMM YYYY',
      closeOnSelect:true
    }, options );

    if( options == undefined ){ options = ''; }

    moment.locale(settings.defaultLang);

    var kwikStart = 0;
    var counter = 0;
    var parent = this

    function hexToRgb(hex){
      var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
      hex = hex.replace(shorthandRegex,function(m, r, g, b) { return r + r + g + g + b + b; });
      var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
      return result ? { r: parseInt(result[1], 16), g: parseInt(result[2], 16), b: parseInt(result[3], 16) } : null;
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


    function startView(){
      kwikStart = 0;
      customObject['calendar_month'](kwikStart);
    }

    function endView(){
      $('#kwik-date-picker:visible').remove();
      counter = 0
    }

    function getReturn(){
      var today =  moment().startOf('day').format('x')
      $('[data-id="'+today+'"]').css({'background':settings.kwikColour,'color':textBrightness(settings.kwikColour) })

      $('.kwik-date-picker-cell a').on('mouseenter',function(){
        $(this).css({'background':settings.kwikColour,'color':textBrightness(settings.kwikColour) })
      })
      $('.kwik-date-picker-cell a').on('mouseleave',function(){
         $(this).css({'background':'#FFFFFF','color':textBrightness('#FFFFFF') })
      })

      $('.close').on("click",function(){
        endView()
        return false;
      })
      $('#kwik-prev-view').on("click",function(){
        if( settings.minDate && settings.minDate < kwikStart ){
          kwikStart = kwikStart-1;
        } else if( settings.minDate && settings.minDate > kwikStart  ){
          kwikStart = kwikStart;
        } else {
          kwikStart = kwikStart-1;
        }
        customObject['calendar_month'](kwikStart);
        return false;
      });
      $('#kwik-next-view').on("click",function(){
        if( settings.maxDate && settings.maxDate > kwikStart ){
        kwikStart = kwikStart+1;
        } else if( settings.maxDate && settings.maxDate < kwikStart  ){
          kwikStart = kwikStart;
        } else {
          kwikStart = kwikStart+1;
        }
        customObject['calendar_month'](kwikStart);
        return false;
      });
      $('.kwik-date-picker-month a').on("click",function(e){
        e.preventDefault();
        if(settings.timeViewAllow){
          customObject['calendar_time']($(this).attr('data-id'));
        } else {
          var updateReturn = moment(parseInt(jQuery(this).attr('data-id'),10)).format(settings.updateDayFormat);
          $(parent).val( updateReturn )
          if(settings.closeOnSelect){
            endView()
          }
        }
       return false;
      })
    }

    function setAppt(){
      $('.kwik-date-picker-cell a').on('mouseenter',function(){
        $(this).css({'background':settings.kwikColour,'color':textBrightness(settings.kwikColour) })
        $('#kwik-appointment').html(moment(parseInt(jQuery(this).attr('data-id'),10)).format('HH:mm '+settings.dayTitleFormat));
      })
      $('.kwik-date-picker-cell a').on('mouseleave',function(){
         $(this).css({'background':'#FFFFFF','color':textBrightness('#FFFFFF') })
         $('#kwik-appointment').html(moment(parseInt(jQuery(this).attr('data-id'),10)).format('00:00 '+settings.dayTitleFormat));
      })

     $('.close').on("click",function(){
        endView()
        return false;
      })
    $('#kwik-back').on("click",function(){
      customObject['calendar_month'](kwikStart);
      return false;
    })
   
    $('.kwik-date-picker-cell a').on("hover",function(){
      console.log('456e4');
      $(this).css({'background':settings.kwikColour })
    })

    $('.kwik-date-picker-time a').on("click",function(){
      $('#kwik-appointment').html(moment(parseInt(jQuery(this).attr('data-id'),10)).format('HH:mm '+settings.dayTitleFormat));
      var updateReturn = moment(parseInt(jQuery(this).attr('data-id'),10)).format(settings.updateTimeFormat);
      $(parent).val( updateReturn )
      if(settings.closeOnSelect){
        endView()
      }
      return false;
    })
   }

    var customObject = {
      calendar_time:function(str){
        var startTime = parseInt(moment(parseInt(str,10)).set('hour',settings.startHourTime).set('minute',settings.startMinuteTime).format('x'));
        var endTime = parseInt(moment(parseInt(str,10)).set('hour',settings.endHourTime).set('minute',settings.endMinuteTime).format('x'));
        var period = settings.sessionTime*60000;
        var txt = '<div class="clearfix kwik-date-picker-header" style="background:'+settings.kwikColour+';color:'+textBrightness(settings.kwikColour)+'">';
        txt += '<div class="kwik-left">';
        txt += '<button id="kwik-back" style="background:'+settings.kwikColour+';color:'+textBrightness(settings.kwikColour)+'">&#x25C4; <span class="button-text">Back</span></button>';
        txt += '</div>';
        txt += '<span id="kwik-appointment">'+moment(parseInt(str,10)).format('HH:mm '+settings.dayTitleFormat)+'</span>';
        txt += '</div>';
        txt += '<div class="clearfix kwik-date-picker-time">';
        var c = 0;
        for (var i = startTime; i < endTime; i = i + period ){
          if(c % 7 == 0){
              txt += '<div class="kwik-date-picker-row"></div>';
            }
          txt += '<div class="kwik-date-picker-cell"><a href="javascript:void(0);" data-id="'+moment(i).format('x')+'">'+moment(i).format('HH:mm')+'</a></div>';
        c++
        }
        txt += '</div>';
        $('.kwik-date-picker').html(txt).promise().done( setAppt() );
      },
      calendar_month:function(str){
        var kwikDay = parseInt(moment().add(kwikStart,'day').startOf('day').format('x'),10);
        var startTime = parseInt(moment(kwikDay).set('hour',settings.startHourTime).set('minute',settings.startMinuteTime).format('x'),10);
        var endTime = parseInt(moment(kwikDay).set('hour',settings.endHourTime).set('minute',settings.endMinuteTime).format('x'),10);
        var period = settings.sessionTime*60000;
        var kwikMonth = parseInt(moment().add(kwikStart,'month').format('x'),10);
        var mondayLast = moment(kwikMonth).subtract(1,'months').endOf('month').startOf('isoweek').format('DD');
        var daysInLastMonth = moment(kwikMonth).subtract(1,'months').daysInMonth();
        var daysInCurrentMonth = moment(kwikMonth).daysInMonth();
        var s = 0;
        var dateArray = {}
        var dayText = moment.weekdaysMin();
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
          for (i = Object.keys(dateArray).length; i < 35; i++){
            var m = parseInt(moment(kwikMonth).add(1,'months').format('MM'),10);
            var y = parseInt(moment(kwikMonth).add(1,'months').format('YYYY'),10);
            dateArray[s] = moment(''+m+' '+d+' '+y+'','MM DD YYYY').startOf('day').format('x');
            s++;
            d++;
          }
        } else if ( Object.keys(dateArray).length < 42  ){
          var d = 1
          for (i = Object.keys(dateArray).length; i < 42; i++){
            var m = parseInt(moment(kwikMonth).add(1,'months').format('MM'),10);
            var y = parseInt(moment(kwikMonth).add(1,'months').format('YYYY'),10);
            dateArray[s] = moment(''+m+' '+d+' '+y+'','MM DD YYYY').startOf('day').format('x');
            s++;
            d++;
          }
        }
        var txt = '<div class="kwik-date-picker-header" style="background:'+settings.kwikColour+';color:'+textBrightness(settings.kwikColour)+'">';
        txt += '<div class="kwik-left">'
        txt += '<button id="kwik-prev-view" style="background:'+settings.kwikColour+';color:'+textBrightness(settings.kwikColour)+'">&#x25C4;</button>'
        txt += '<button id="kwik-next-view" style="background:'+settings.kwikColour+';color:'+textBrightness(settings.kwikColour)+'">&#x25BA;</button>'
        txt += '</div>'
        txt +=  moment(kwikMonth).format(settings.monthTitleFormat);
        txt += '</div>'
        txt += '<div class="kwik-date-picker-month">';
        for (i = 0; i < dayText.length; i++){
          if(dayText[i+1] != undefined){
            txt += '<div class="kwik-date-picker-cell kwik-date-picker-month-header">'+dayText[i+1] +'</div>';
          } else {
            txt += '<div class="kwik-date-picker-cell kwik-date-picker-month-header">'+dayText[0] +'</div>';
          }
        }
        for (var key in dateArray){
          var c = 0;
          if (dateArray.hasOwnProperty(key)){
            if(key % 7 == 0){
              txt += '<div class="kwik-date-picker-row"></div>';
            }
            txt += '<div class="kwik-date-picker-cell"><a href="javascript:void(0);" data-id="'+moment(parseInt(dateArray[key],10)).format('x')+'">'+moment(parseInt(dateArray[key],10)).format('D')+'</a></div>';
            c++;
          }
        }
        txt += '</div>';
        $('.kwik-date-picker').html(txt).promise().done( getReturn() );
        }
      };

      this.on("mousedown",function(e){
      e.stopPropagation();
      endView()
      if( counter == 0 ){
        counter = 1
        $('body').append( '<div id="kwik-date-picker"><div class="kwik-date-picker"></div></div>' ).promise().done( startView() );
        $('#kwikDayView').on("click",function(){
          kwikStart = 0;
          customObject['calendar_day'](kwikStart);
          jQuery('.kwik-date-picker:visible').attr('data-view','day')
        })
        $('#kwikMonthView').on("click",function(){
          kwikStart = 0;
          customObject['calendar_month'](kwikStart);
          jQuery('.kwik-date-picker:visible').attr('data-view','month')
        })
      } else {
        endView()
      }
    })

    $('body').on("mousedown",function(e){
     e.stopPropagation();
      if ( $('#kwik-date-picker:visible').has(e.target).length > 0){} else {
        endView()
      }
    })

}

})(jQuery);