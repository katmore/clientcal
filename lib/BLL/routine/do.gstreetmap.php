<?php
/*
 *
 * do.gstreetmap.php of schedule
 *
 */
namespace clientcal;

   $gapikey = (new config("geoloc"))->getValue("GOOGLE_API_KEY");
   $ret .= "
<script type=\"text/javascript\" src=\"http://maps.google.com/maps/api/js?sensor=false&amp;key=$gapikey\"></script>

<script type=\"text/javascript\">
  function StreetView() {
   var addr = new google.maps.LatLng($sLat, $sLon);
    var panoramaOptions = {
      position: addr,
      pov: {
        heading: 34,
        pitch: 10,
        zoom: 1
      }
    };
    var panorama = new  google.maps.StreetViewPanorama(document.getElementById('mapDiv'),panoramaOptions);
    var map = new google.maps.Map(document.getElementById('mapDiv'), {
    zoom: 14
  });
    map.setStreetView(panorama);

  }
</script>
<div id='mapDiv' style='position:relative; width:400px; height:400px;'></div>
<script type=\"text/javascript\">
StreetView();
</script>";