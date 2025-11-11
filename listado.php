<?php

$DB_HOST='127.0.0.1'; $DB_USER='root'; $DB_PASS=''; $DB_NAME='tutorias_db';
$mysqli = new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME);
if($mysqli->connect_errno) die("Error: ".$mysqli->connect_error);

$res = $mysqli->query("SELECT id,nombre,apellido,materia,factor_riesgo,creado FROM altas ORDER BY creado DESC LIMIT 200");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Listado de Altas</title></head>
<body>
  <h1>Registros</h1>
  <p><a href="altas.html">Nuevo registro</a></p>
  <table border="1" cellpadding="8" cellspacing="0">
    <tr><th>ID</th><th>Nombre</th><th>Materia</th><th>Factor</th><th>Fecha</th></tr>
    <?php while($r = $res->fetch_assoc()): ?>
      <tr>
        <td><?=htmlspecialchars($r['id'])?></td>
        <td><?=htmlspecialchars($r['nombre'].' '.$r['apellido'])?></td>
        <td><?=htmlspecialchars($r['materia'])?></td>
        <td><?=htmlspecialchars($r['factor_riesgo'])?></td>
        <td><?=htmlspecialchars($r['creado'])?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
<?php $mysqli->close(); ?>
