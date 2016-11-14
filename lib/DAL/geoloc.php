<?php

namespace clientcal;

/*
 *
 * geocode.php of bingmaps
 *
 */

/**
 * BingMapsGeocodeSoapService
 */
class BingMapsGeocodeSoapService {
    /* Member variables */
    const API_KEY       = "";
    const GEOCODE_WSDL  = "http://dev.virtualearth.net/webservices/v1/metadata/geocodeservice/geocodeservice.wsdl";

    /**
     * __construct
     *
     * Ctor.
     *
     * @access:
     * @param:
     * @return:
     */
    public function __construct() {
        /* void */
    }

    /**
     * geocode_lookup_raw
     *
     * Performs a standard geocode lookup using the query, returns the response that is
     * returned from performing the geocode lookup.
     *
     * @access: public
     * @param:  string
     * @return: mixed       Returns false on failure, else returns an object.
     */
    public function geocode_lookup_raw($query) {
        return $this->_geocode_lookup($query);
    }

    /**
     * geocode_lookup
     *
     * Performs a standard geocode lookup using the query, returns an array
     * of objects containing only latitudes, longitudes, formatted query and the locality.
     *
     * @access: public
     * @param:  string
     * @return: mixed       Returns false on failure, else returns array
     */
    public function geocode_lookup($query) {
        $geocode_results = $this->_geocode_lookup($query);
        if( $geocode_results ) {
            try {
                $geocode_results = &$geocode_results->GeocodeResult->Results;

                if( is_array($geocode_results->GeocodeResult) ) {
                    $results = array();
                    foreach( $geocode_results->GeocodeResult as &$result ) {
                        $o = new stdClass;
                        $o->formatted_address = $result->Address->FormattedAddress;
                        $o->locality = $result->Address->Locality;

                        if( is_array($result->Locations->GeocodeLocation) ) {
                            $o->latitude = $result->Locations->GeocodeLocation[0]->Latitude;
                            $o->longitude = $result->Locations->GeocodeLocation[0]->Longitude;
                        } else {
                            $o->latitude = $result->Locations->GeocodeLocation->Latitude;
                            $o->longitude = $result->Locations->GeocodeLocation->Longitude;
                        }

                        $results[] = $o;
                    }

                    return $results;

                } else {
                    if( isset($geocode_results->GeocodeResult->Locations->GeocodeLocation) ) {
                  $o = new stdClass;
                  $o->formatted_address = $geocode_results->GeocodeResult->Address->FormattedAddress;
                  $o->locality = $geocode_results->GeocodeResult->Address->Locality;

                  if( is_array($geocode_results->GeocodeResult->Locations->GeocodeLocation) ) {
                     $o->latitude = $geocode_results->GeocodeResult->Locations->GeocodeLocation[0]->Latitude;
                     $o->longitude = $geocode_results->GeocodeResult->Locations->GeocodeLocation[0]->Longitude;
                  } else {
                     $o->latitude = $geocode_results->GeocodeResult->Locations->GeocodeLocation->Latitude;
                     $o->longitude = $geocode_results->GeocodeResult->Locations->GeocodeLocation->Longitude;
                  }

                        return array($o);
                    }

                    return false;
                }
            }
            catch( Exception $e ) {
                // throw $e;
                return false;
            }
        }

        return false;
    }

    /**
     * _geocode_lookup
     *
     * @access: protected
     * @param:  string
     * @return: mixed
     */
    final protected function _geocode_lookup($query) {
        $query = trim($query);
        if( $query ) {
            try {
                $soap_client = new SoapClient(self::GEOCODE_WSDL);
                $geocode_response = $soap_client->Geocode(array(
                    'request' => array(
                        'Credentials' => array('ApplicationId' => self::API_KEY),
                        'Query' => $query
                     )
                ));

                return $geocode_response;
            }
            catch( Exception $e ) {
                throw new Exception("An error has occurred with the SOAP client request.", 0, $e);
            }
        }

        return false;
    }
}

function GetGeoReverse($Address,&$pLat,&$pLon) {
   $geo = new BingMapsGeocodeSoapService();
   $result = $geo->geocode_lookup($Address);

   //print_r($result);
   if (isset($result[0]->latitude)) {
      $pLat = $result[0]->latitude;
   } else {
      $pLat = 0;
   }
   if (isset($result[0]->longitude)) {
      $pLon = $result[0]->longitude;
   } else {
      $pLon = 0;
   }
   return 0;
}





