<?php 

namespace clientcal;

   function globalizesitepostvars() {
      global $mSite_streetaddr,$mSite_city,$mSite_state,$mSite_zip,$mSite_sdirections;
      if (isset($_POST["streetaddr"])) {
         $mSite_streetaddr = $_POST["streetaddr"];
      }
      if (isset($_POST["city"])) {
         $mSite_city = $_POST["city"];
      }
      if (isset($_POST["state"])) {
         $mSite_state = $_POST["state"];
      }
      if (isset($_POST["zip"])) {
         $mSite_zip = $_POST["zip"];
      }
      if (isset($_POST["sdirections"])) {
         $mSite_sdirections = $_POST["sdirections"];
      }
   }
   function sitesuggest($caption,$action,$cancel) {
      global $mSite_streetaddr,$mSite_city,$mSite_state,$mSite_zip,$mSite_sdirections;
      $ret = "
      <table width=\"400\">
         <tr>
            <td>
            <form method=\"POST\" action=\"$action\">
            <table width=\"100%\">
               <tr>
                  <td class=\"heading\">$caption</td>
               </tr>
               <tr>
                  <td class=\"project\">
                  streetaddr:<br />
                  <textarea name=\"streetaddr\" cols=\"40\" rows=\"2\">$mSite_streetaddr</textarea>
                  </td>
               </tr>
               <tr>
                  <td class=\"project\">
                  <table>
                     <tr>
                        <td>
                        city:<br />
                        <input type=\"text\" size=\"40\" name=\"city\" value=\"$mSite_city\">
                        </td>
                        <td>
                        state:<br />
                        <input type=\"text\" size=\"2\" name=\"state\" value=\"$mSite_state\">
                        </td>
                        <td>
                        zip:<br />
                        <input type=\"text\" size=\"10\" name=\"zip\" value=\"$mSite_zip\">
                        </td>
                     </tr>
                  </table>
                  </td>
               </tr>
               <tr>
                  <td class=\"project\">
                  any special direction info:<br />
                  <textarea name=\"sdirections\" cols=\"40\" rows=\"4\">$mSite_sdirections</textarea>
                  </td>
               </tr>
               <tr>
                  <td class=\"project\">
                  <input type=\"submit\" value=\"submit\">";
                  if ($cancel != "") {
      $ret .= "
                  <b><a href=\"$cancel\">[cancel]</a></b>";
                  }
      $ret .= "
                  </td>
               </tr>
            </table>
            </form>
            </td>
         </tr>
      </table>";
      return $ret;
   }
