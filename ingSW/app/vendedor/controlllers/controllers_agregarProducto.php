
<?php
session_start();
include('../config/bd.php');
$conexion = BD::crearInstancia();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'abarrotero') {
    header("Location: ../index.php");
    exit;
}

$mensaje = '';

// Obtener categorías desde la base de datos
$categorias = $conexion->query("SELECT nombre FROM categorias")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $fecha = $_POST['fecha_caducidad'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    $precio = $_POST['precio'] ?? 0;

    if ($nombre && $fecha && $categoria && is_numeric($stock) && is_numeric($precio)) {
        if (strlen($nombre) > 100 || strlen($categoria) > 50 || strlen($descripcion) > 255) {
            $mensaje = "⚠ Uno de los campos excede el límite permitido.";
        } else {
            // Verificar duplicado por nombre + fecha
            $verifica = $conexion->prepare("SELECT COUNT(*) FROM inventario_solicitudes 
                                            WHERE nombre = ? AND fecha_caducidad = ?");
            $verifica->execute([$nombre, $fecha]);
            $yaExiste = $verifica->fetchColumn();

            if ($yaExiste > 0) {
                $mensaje = "⚠ Ya existe un producto con el mismo nombre y fecha de caducidad pendiente.";
            } else {
                $stmt = $conexion->prepare("INSERT INTO inventario_solicitudes 
                    (nombre, descripcion, fecha_caducidad, categoria, stock, precio, creado_por)
                    VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $nombre, $descripcion, $fecha, $categoria, $stock, $precio, $_SESSION['usuario']
                ]);
                $mensaje = "✅ Producto enviado para aprobación.";
            }
        }
    } else {
        $mensaje = "⚠ Todos los campos son obligatorios.";
    }
}

include('../templates/cabVendedor.php');
require_once 'views/views_agregarProducto.php';
?>
