"use strict"

var map,lat,lng,address;
var geocoder = new google.maps.Geocoder();

(function($){

$.fn.kwikLocationPicker = function(options){

  var parent = this;
  var settings = $.extend({
    kwikColour:'#FF5722',
    kwikInstructions:'To obtain the address details of a location drag the map marker to your desired location.',
    kwikLatLong:{'lat':51.50062609624592,'lng':-0.12415471279291523},
    kwikMarkerTitle:'Drag me to a location',
    kwikTitle:'Location picker',
    kwikZoom:10,
  },options);

  if( options == undefined ){ options = ''; }

  function kwikLocator(){
    var mapOptions = {
      zoom:settings.kwikZoom,
      center:settings.kwikLatLong,
      mapTypeId:google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("kwik-map-picker"),mapOptions);
    var marker = new google.maps.Marker({
      draggable:true,
      animation:google.maps.Animation.DROP,
      position:settings.kwikLatLong,
      map:map,
      title:settings.kwikMarkerTitle
    });
    google.maps.event.addListener(marker,'dragend',function(){
      map.setCenter(this.getPosition());
      updatePosition(this.getPosition().lat(),this.getPosition().lng());
    });
    google.maps.event.addListener(map,'dragend',function(){
      marker.setPosition(this.getCenter());
      updatePosition(this.getCenter().lat(),this.getCenter().lng());
    });
    function updatePosition(lat,lng){
      geocoder.geocode({'latLng':marker.getPosition()},function(results,status){
        var data = {}
        data['address'] = results[0].formatted_address
        data['place_id'] = results[0].place_id
        data['lat'] = lat;
        data['lng'] = lng;
        $('#kwik-map-result').html('Address<br/>'+data['address']+'<p/>Latitude<br/>'+ data['lat']+'<p/>Longditude<br/>'+data['lng']+'<p/>Place ID<br/>'+data['place_id']+'<p/><button class="kwik-map-close" style="background:'+settings.kwikColour+'">Set &amp; close</button>')
        if ( options.kwikLocationCallback && typeof options.kwikLocationCallback == 'function'){
          options.kwikLocationCallback.call(this,data);
        } else {
          $(parent).val( results[0].formatted_address )
        }
      })
    }
  }

  function endView(){
    $('#kwik-map-holder:visible').remove();
  }

  this.on('mousedown',function(e){
    e.stopPropagation();
    endView();
    var txt = '<div id="kwik-map-holder">'
    txt += '<div class="kwik-map-holder">'
    txt += '<div class="kwik-map-holder-header" style="background:'+settings.kwikColour+'">'+settings.kwikTitle+'<span class="kwik-map-close">&times;</span></div>'
    txt += '<div id="kwik-map-picker"></div>'
    txt += '<div id="kwik-map-result">'+settings.kwikInstructions+'</div>'
    txt += '</div></div>'
    $('body').append(txt)
    kwikLocator();
  })

  $(document).on('click','.kwik-map-close',function(e){
    endView();
  })

  $('body').on('mousedown',function(e){
    e.stopPropagation();
    if($('#kwik-map-holder:visible').has(e.target).length> 0 ){} else { endView(); }
  })
}

})(jQuery);