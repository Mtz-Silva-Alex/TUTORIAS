<?php

$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = ''; 
$DB_NAME = 'tutorias_db';
$TABLE   = 'altas';


$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS);
if ($mysqli->connect_errno) {
    die("Error de conexión MySQL: " . $mysqli->connect_error);
}

if (!$mysqli->query("CREATE DATABASE IF NOT EXISTS `$DB_NAME` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
    die("No se pudo crear la base de datos: " . $mysqli->error);
}

$mysqli->select_db($DB_NAME);

$createSQL = "CREATE TABLE IF NOT EXISTS `$TABLE` (
    matricula VARCHAR(20) PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    fecha DATE NOT NULL,
    semestre INT NOT NULL,
    parcial VARCHAR(20) NOT NULL,
    maestro VARCHAR(100) NOT NULL,
    grupo VARCHAR(20) NOT NULL,
    motivo VARCHAR(200) NOT NULL,
    materia VARCHAR(150) NOT NULL,
    estrategias TEXT NOT NULL,
    creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if (!$mysqli->query($createSQL)) {
    die("Error creando tabla: " . $mysqli->error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricula   = trim($_POST['matricula'] ?? '');
    $nombres     = trim($_POST['nombres'] ?? '');
    $apellidos   = trim($_POST['apellidos'] ?? '');
    $fecha       = trim($_POST['fecha'] ?? '');
    $semestre    = trim($_POST['semestre'] ?? '');
    $parcial     = trim($_POST['parcial'] ?? '');
    $maestro     = trim($_POST['maestro'] ?? '');
    $grupo       = trim($_POST['grupo'] ?? '');
    $motivo      = trim($_POST['motivo'] ?? '');
    $materia     = trim($_POST['materia'] ?? '');
    $estrategias = trim($_POST['estrategias'] ?? '');

    if ($matricula === '' || $nombres === '' || $apellidos === '' || $fecha === '' ||
        $semestre === '' || $parcial === '' || $maestro === '' || $grupo === '' ||
        $motivo === '' || $materia === '' || $estrategias === '') {
        echo "<p style='color:red;'>Faltan campos requeridos. <a href='javascript:history.back()'>Volver</a></p>";
        exit;
    }


    $stmt = $mysqli->prepare("INSERT INTO `$TABLE` 
        (matricula, nombres, apellidos, fecha, semestre, parcial, maestro, grupo, motivo, materia, estrategias)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Error preparando statement: " . $mysqli->error);
    }

    $stmt->bind_param(
        'ssssissssss',
        $matricula,
        $nombres,
        $apellidos,
        $fecha,
        $semestre,
        $parcial,
        $maestro,
        $grupo,
        $motivo,
        $materia,
        $estrategias
    );

    $ok = $stmt->execute();

    if ($ok) {
        echo "<h2>Registro guardado correctamente</h2>";
        echo "<p>Matrícula: <strong>" . htmlspecialchars($matricula) . "</strong></p>";
        echo "<p><a href='index.html'>Agregar otro</a></p>";
    } else {
        echo "<p style='color:red;'>Error al guardar: " . htmlspecialchars($stmt->error) . "</p>";
        echo "<p><a href='index.html'>Volver</a></p>";
    }

    $stmt->close();
    $mysqli->close();
    exit;
}

// Si no llega POST, redirige al formulario
header('Location: index.html');
exit;
?>
