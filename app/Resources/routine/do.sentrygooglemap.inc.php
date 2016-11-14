<?php
/*
 *
 * do.sentrygooglemap.php of schedule
 *
 */

   $gapikey = "";
   $ret .= "
<script type=\"text/javascript\" src=\"http://maps.google.com/maps/api/js?sensor=false&amp;key=$gapikey\"></script>

<script type=\"text/javascript\">
  function GoogleMap() {
    var myLatlng = new google.maps.LatLng($sLat, $sLon);
    var myOptions = {
      zoom: $sZoom,
      center: new google.maps.LatLng($sLat, $sLon),
      mapTypeControl: true,
      mapTypeControlOptions: {
        style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
      },
      zoomControl: true,
      zoomControlOptions: {
        style: google.maps.ZoomControlStyle.SMALL
      },
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }

    var map = new google.maps.Map(document.getElementById(\"mapDiv\"), myOptions);
    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        title:\"$sSite_streetaddr\"
    })
  }
</script>
<div id='mapDiv' style='position:relative; width:400px; height:400px;'></div>
<script type=\"text/javascript\">
GoogleMap();
</script>
   ";