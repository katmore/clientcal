<?php
/*
 *
 * do.bingmap.php of schedule
 *
 */

         $ret .= "


<script type=\"text/javascript\" src=\"http://ecn.dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=7.0\"></script>
<script type=\"text/javascript\">
function BingMap() {
   var mapOptions = {
   credentials: \"AqDTKhvuw7F2nEctDzqAOTVW2v_mIxGJ8n_QwCT8CeVfRIzhkSlz3KnpvaGxZgQk\",
   mapTypeId: Microsoft.Maps.MapTypeId.road,
   enableClickableLogo: 0,
   showLogo: false,
   showCopyright: false,
   showDashboard: true,
   showScalebar: false
   }

   var map = new Microsoft.Maps.Map(
      document.getElementById('mapDiv'),
      mapOptions
   );

   // Define the pushpin location
   var loc = new Microsoft.Maps.Location($sLat, $sLon);

   // Add a pin to the map
   var pin = new Microsoft.Maps.Pushpin(loc);
   map.entities.push(pin);

   // Center the map on the location
   map.setView({center: loc, zoom: $sZoom});

   //map.setView({
   //   center: new Microsoft.Maps.Location($sLat, $sLon),
   //   zoom:12
   //});









}

</script>
            <div id='mapDiv' style='position:relative; width:400px; height:400px;'></div>
<script type=\"text/javascript\">
BingMap();
</script>
";