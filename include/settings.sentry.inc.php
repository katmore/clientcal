<?php
   $actionm_headingcells_height = 95;
   $actionm_headingcells_width = 100;
   $actionm_headingcells_format = "";
   $actionm_cellheading_class = "cellheading";
   //$actionm_cellheadingsel_class = "cellheadingsel";
   $actionm_cellcontent_class = "cellcontent";
   //$actionm_cellcontentsel_class = "cellcontentsel";
   //<div class=\"supervisor_%entry.supervisorkey%\">
   //./sentry.php?submitdupe=%entry.key%&amp;month=%date:n%&amp;day=%date:j%&amp;year=%date:Y%
   $actionm_entrycaption_format = "
         <div title=\"%entry.custname%: %entry.heading% - %site.streetaddr% (%entry.type%)\" class=\"entrycaption_%entry.supervisorkey%\">
            %entry.custname:limitchar:10%%dashifcustandheading%%entry.heading:limitchar:8%
         </div>
         ";
   $actionm_blankcell_format = "
   <div style=\"height:100%;border: 1px solid #000000;background:#D6D2CE;\">&nbsp;</div>";
   $actionm_daycell_format = "
   <div class=\"%cellcontentclass%\">
      <div title=\"select %date:l%, %date:F% %date:j%, %date:Y%\" class=\"%cellheadingclass%\" onclick=\"location.href='./sentry.php?%actionmselect%=%actionmentry.key%&amp;month=%date:n%&day=%date:j%&year=%date:Y%'\">
         <div style=\"float: left;width:15px;font-family:Tahoma;font-size:8pt;vertical-align:top;\">%date:j%</div>
         <div style=\"float:right;font-family:Tahoma;font-size:8pt;vertical-align:top;\">%entrycountabove0%</div>
         <div style=\"border-left: 1px solid #000000;margin-left:15px;font-family:Tahoma;font-size:8pt;vertical-align:top;\">%date:D%</div>
      </div>
      %entries%
   </div>";
   $actionm_start_dayofweekw = 1;
   //Sunday, June 4, 1972sssss
   //$actionm_headingcaption
   $actionm_headingcaption = "%actionmcaption%";
   $actionmcaption_style = "font-family:Tahoma;font-size:11pt;font-weight:bold;color:#000000;vertical-align:top;float:left;";
   $actionm_headingtools = "
   <a href=\"./sentry.php?%actionmbrowse%=%actionmentry.key%&amp;month=%date:n%&amp;year=%date:Y%&amp;%toggleshowsentries.toggle%\">[%toggleshowsentries.caption%]</a>
   %date:F% %date:Y%

   <a title=\"show selection for previous month %prevmonth.date:F% %prevmonth.date:Y%\" href=\"./sentry.php?%actionmbrowse%=%actionmentry.key%&amp;month=%prevmonth.date:n%&amp;year=%prevmonth.date:Y%\">&lt;</a>
   <a title=\"refresh this month\" href=\"./sentry.php?%actionmbrowse%=%actionmentry.key%&amp;month=%date:n%&amp;year=%date:Y%\">||</a>
   <a title=\"show selection for next month: %nextmonth.date:F% %nextmonth.date:Y%\" href=\"./sentry.php?%actionmbrowse%=%actionmentry.key%&amp;month=%nextmonth.date:n%&amp;year=%nextmonth.date:Y%\">&gt;</a>
   ";

   $monthv_headingcells_width = 100;
   $monthv_headingcells_height = 95;
   $monthv_headingcells_format = "";
   $monthv_cellheading_class = "cellheading";
   $monthv_cellheadingsel_class = "cellheadingsel";
   $monthv_cellcontent_class = "cellcontent";
   $monthv_cellcontentsel_class = "cellcontentsel";
   //<div class=\"supervisor_%entry.supervisorkey%\">
   $monthv_entrytype_legend_format = "
   <span class=\"entrycaption_entrytype_%entry.type%\">%entry.typebrief%</span>
   ";
   $monthv_entrycaption_format = "
         <div title=\"%entry.custname%: %entry.heading% - %site.streetaddr% (%entry.type%)\" onclick=\"location.href='./sentry.php?show=%entry.key%'\" class=\"entrycaption_entrytype_%entry.type%\">
            %entry.custname:limitchar:10%%dashifcustandheading%%entry.heading:limitchar:8%
         </div>
         ";
   $monthv_blankcell_format = "
   <div style=\"height:100%;border: 1px solid #000000;background:#D6D2CE;\">&nbsp;</div>";
   $monthv_daycell_format = "
   <div class=\"%cellcontentclass%\">
      <div title=\"select %date:l%, %date:F% %date:j%, %date:Y%\" class=\"%cellheadingclass%\" onclick=\"location.href='./sentry.php?showsentries=%date:n%&day=%date:j%&year=%date:Y%'\">
         <div style=\"float: left;width:15px;font-family:Tahoma;font-size:8pt;vertical-align:top;\">%date:j%</div>
         <div style=\"float:right;font-family:Tahoma;font-size:8pt;vertical-align:top;\">%entrycountabove0%</div>
         <div style=\"border-left: 1px solid #000000;margin-left:15px;font-family:Tahoma;font-size:8pt;vertical-align:top;\">%date:D%</div>
      </div>
      %entries%
   </div>";
   $monthv_start_dayofweekw = 1;
   //Sunday, June 4, 1972sssss
   $monthv_headingtools = "
   <a title=\"show entries for %date:l%, %date:F% %date:j%, %date:Y%\" href=\"sentry.php?showday&amp;month=%date:n%&amp;day=%date:j%&amp;year=%date:Y%\">[day]</a>
   <a href=\"./sentry.php?addwprocess&amp;cancel=showsentries&amp;month=%date:n%&amp;day=%date:j%&amp;year=%date:Y%\" title=\"create a new entry for %date:l%, %date:F% %date:j%, %date:Y%\">[new]</a>";
   $monthv_headingcaption = "
   <a title=\"show entries for previous month:%prevmonth.date:l%, %prevmonth.date:F% %prevmonth.date:j%, %prevmonth.date:Y%\" href=\"./sentry.php?showsentries=%prevmonth.date:n%&amp;year=%prevmonth.date:Y%&amp;day=%prevmonth.date:j%\">&lt;</a>
   <a title=\"refresh this month\" href=\"./sentry.php?showsentries=%date:n%&amp;year=%date:Y%&amp;day=%date:j%\">||</a>
   <a title=\"show entries for next month: %nextmonth.date:l%, %nextmonth.date:F% %nextmonth.date:j%, %nextmonth.date:Y%\" href=\"./sentry.php?showsentries=%nextmonth.date:n%&amp;year=%nextmonth.date:Y%&amp;day=%nextmonth.date:j%\">&gt;</a>
   %jumpmonthform%
   %date:l%, %date:F% %date:j%, %date:Y%";
   