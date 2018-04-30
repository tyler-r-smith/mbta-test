<?php
 include './MBTA.php';
 $routesTypes = MBTA::routesByType();
 $routeId = !empty($_GET['route']) ? $_GET['route'] : false;

 if (!empty($routeId)):
  $schedule = MBTA::getScheduleByIdGroupedByTrip($routeId); 
    foreach($schedule as $tripId => $stops):
    ?>
    <h3><?php echo $tripId; ?></h3>
      <?php foreach($stops as $stop): ?>
      <?php echo $stop->relationships->stop->data->id; ?>
      <?php $date = new DateTime($stop->attributes->arrival_time); echo $date->format('H:i:s'); ?>
      <br/>
      <?php endforeach; ?>
    <?php endforeach;?>
  <?php else: ?>
    <table>
      <?php foreach ($routesTypes as $route_name => $routes): ?>
          <th><?php echo $route_name; ?></th>
          <?php foreach ($routes as $route): ?>
            <tr style="background-color: <?php echo $route->attributes->color; ?>">
              <td><a style="color: black;" href="/?route=<?php echo $route->id; ?>"><?php echo $route->attributes->long_name !== '' ? $route->attributes->long_name  : $route->attributes->short_name; ?></a></td>
            </tr>
          <?php endforeach; ?>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
