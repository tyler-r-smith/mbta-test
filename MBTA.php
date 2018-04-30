<?php
  class MBTA {
    public static function requestRoutes(){
      $curl = curl_init('https://api-v3.mbta.com/routes');
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
      $result = curl_exec($curl);
      curl_close($curl);
      return json_decode($result);
    }
    public static function routesByType(){
      $routes = self::requestRoutes();
      $routesByType = [];
      foreach ($routes->data as $route) {
        $routesByType[$route->attributes->description] = $routesByType[$route->attributes->description] ?? [];
        array_push($routesByType[$route->attributes->description], $route);
      }
      return $routesByType;
    }
  }
?>