<?php
// altas.php
// CONFIG - ajusta si cambias usuario/clave
$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';         // en XAMPP por defecto está vacío
$DB_NAME = 'tutorias_db';
$TABLE  = 'altas';

// Conectar a MySQL (primero sin DB para crearla si hace falta)
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS);
if($mysqli->connect_errno){
    die("Error de conexión MySQL: " . $mysqli->connect_error);
}

// Crear base de datos si no existe
if(!$mysqli->query("CREATE DATABASE IF NOT EXISTS `$DB_NAME` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")){
    die("No se pudo crear la base de datos: " . $mysqli->error);
}

// Seleccionar la base de datos
$mysqli->select_db($DB_NAME);

// Crear tabla si no existe
$createSQL = "CREATE TABLE IF NOT EXISTS `$TABLE` (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100),
    email VARCHAR(150),
    telefono VARCHAR(40),
    materia VARCHAR(150) NOT NULL,
    factor_riesgo VARCHAR(200) NOT NULL,
    estrategia TEXT,
    observacion TEXT,
    creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if(!$mysqli->query($createSQL)){
    die("Error creando tabla: " . $mysqli->error);
}

// Sólo procesa POST
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Recoger y sanitizar entrada básica
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $materia = trim($_POST['materia'] ?? '');
    $factor = trim($_POST['factor'] ?? '');
    $estrategia = trim($_POST['estrategia'] ?? '');
    $observacion = trim($_POST['observacion'] ?? '');

    // Validación mínima
    if($nombre === '' || $materia === '' || $factor === ''){
        echo "<p style='color:red;'>Faltan campos requeridos. <a href='javascript:history.back()'>Volver</a></p>";
        exit;
    }

    // Prepared statement para insertar
    $stmt = $mysqli->prepare("INSERT INTO `$TABLE` (nombre, apellido, email, telefono, materia, factor_riesgo, estrategia, observacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if(!$stmt){
        die("Error preparando statement: " . $mysqli->error);
    }

    $stmt->bind_param('ssssssss', $nombre, $apellido, $email, $telefono, $materia, $factor, $estrategia, $observacion);
    $ok = $stmt->execute();

    if($ok){
        $id = $stmt->insert_id;
        echo "<h2>Registro guardado correctamente</h2>";
        echo "<p>ID: <strong>$id</strong></p>";
        echo "<p><a href='altas.html'>Agregar otro</a> | <a href='listado.php'>Ver listado</a> (opcional)</p>";
    } else {
        echo "<p style='color:red;'>Error al guardar: " . htmlspecialchars($stmt->error) . "</p>";
        echo "<p><a href='altas.html'>Volver</a></p>";
    }

    $stmt->close();
    $mysqli->close();
    exit;
}
 

header('Location: altas.html');
exit;
?>
