<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    // Get home page
    public function getHome()
    {
      return view('geo.homepage');
    }

    // Post home page
    public function postHome() 
    {
      if ((!empty($_POST['lat'])) && (!empty($_POST['lon']))) {
        // Collect POST data
        $mylat = $_POST['lat'];
        $mylon = $_POST['lon'];

        // Load an XML file - CDATA to string
        $url = 'http://www.meteo.si/uploads/probase/www/observ/surface/text/sl/observation_si_latest.rss';
        $xml = simplexml_load_file($url, 'SimpleXMLElement', LIBXML_NOCDATA);

        $html = [];

        // Get coords from every item,
        // so you can then get a distance.
        for($i = 0; $i < 16; $i++) {
          $lat = $xml->channel->item[$i]->children('geo', true)->lat;
          $long = $xml->channel->item[$i]->children('geo', true)->long;
          list($city) = explode(':', $xml->channel->item[$i]->title);
          $html[$city] = $lat.'-'.$long;
        }

        $result = [];

        // Store the distance (KM) of every item in array
        foreach ($html as $key => $value) {
          list($clat, $clong) = explode('-', $html[$key]);
          $closest = $this->getDistance($mylat, $mylon, $clat, $clong, "K");
          $result[$key] = $closest;
        }

        // Get the city name from closest location
        $closest_city = array_keys($result, min($result));
        // Implode array to string, so you can compare it later
        $closest_city = implode($closest_city);

        // Loop through every item and compare titles,
        // to get the closest location,
        // so the data can be fetched.
        for($i = 0; $i < 16; $i++) {
          $title = $xml->channel->item[$i]->title[0];
          $title = strval($title);
          list($city, $weather) = explode(':', $title);
          $weather = str_replace('.', '', $weather);
            // Get the closest location
            if ($closest_city == $city) {
              $desc = $xml->channel->item[$i]->description;
              return view('geo.homepage', compact('city', 'weather', 'desc'));
              exit();
            }
        }

      } else {
        // If POST data not set, make a simple redirect.
        header("Location: /");
        exit();
      }
    }

    // Measure the distance
    public function getDistance($lat1, $lon1, $lat2, $lon2, $unit) 
    {
      $theta = $lon1 - $lon2;
      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $miles = $dist * 60 * 1.1515;
      $unit = strtoupper($unit);

      if ($unit == "K") {
        return ($miles * 1.609344);
      } else if ($unit == "N") {
        return ($miles * 0.8684);
      } else {
        return $miles;
      }
    }
}
