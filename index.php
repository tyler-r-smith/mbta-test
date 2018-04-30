<?php
 include './MBTA.php';
 $routesTypes = MBTA::routesByType();
 
 ?>  

 <table>
  <?php foreach ($routesTypes as $route_name => $routes): ?>
      <th><?php echo $route_name; ?></th>
      <?php foreach ($routes as $route): ?>
        <tr style="background-color: <?php echo $route->attributes->color; ?>">
          <td><?php echo $route->attributes->long_name !== '' ? $route->attributes->long_name  : $route->attributes->short_name; ?></td>
        </tr>
      <?php endforeach; ?>
  <?php endforeach; ?>
</table>