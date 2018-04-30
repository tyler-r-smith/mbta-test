<?php
  class MBTA {
    //A store for all the trips once we have recived them from the api
    public static $trips = [];
    
    // A store for all the stops once we have recived them from the api
    public static $stops = [];


    //Get all the routes from the mbta api
    public static function requestRoutes(){
      $curl = curl_init('https://api-v3.mbta.com/routes');
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
      $result = curl_exec($curl);
      curl_close($curl);
      return json_decode($result);
    }

    //Organize all the routes by type
    public static function routesByType(){
      $routes = self::requestRoutes();
      $routesByType = [];
      foreach ($routes->data as $route) {
        $routesByType[$route->attributes->description] = $routesByType[$route->attributes->description] ?? [];
        array_push($routesByType[$route->attributes->description], $route);
      }
      return $routesByType;
    }

    // Get a train schedule by id
    public static function getScheduleById($id) {
      $curl = curl_init('https://api-v3.mbta.com/schedules?filter%5Broute%5D='.$id);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
      $result = curl_exec($curl);
      curl_close($curl);
      return json_decode($result);
    }

    // Group the schedules by id
    public static function getScheduleByIdGroupedByTrip($id){
      $schedule = self::getScheduleById($id);
      if (empty($schedule->data)) {
        //should really return the same error here
        return false;
      }
      $trips = [];
      foreach($schedule->data as $stop){
        $tripid = $stop->relationships->trip->data->id;
        $trips[$tripid] = $trips[$tripid] ?? [];
        array_push($trips[$tripid], $stop);
      }
      return $trips;
    }

    // Get A trip from the api
    public static function getTrip($id){
      if (!empty(self::$trips[$id])) {
        return self::$trips[$id];
      }
      $curl = curl_init('https://api-v3.mbta.com/trips/'.$id);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
      $result = curl_exec($curl);
      curl_close($curl);
      self::$trips[$id] = json_decode($result);
      return self::$trips[$id];
    }

    // Get a stop from the api
    public static function getStop($id){
      if (!empty(self::$stops[$id])) {
        return self::$stops[$id];
      }
      $curl = curl_init('https://api-v3.mbta.com/stops/'.$id);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
      $result = curl_exec($curl);
      curl_close($curl);
      self::$stops[$id] = json_decode($result);
      return self::$stops[$id];
    }

    // Return the stop name
    public static function getStopName($id){
      return self::getStop($id)->data->attributes->name;
    }

    // Return the trip name
    public static function getTripName($id){
      return self::getTrip($id)->data->attributes->name;
    }
  }
?>