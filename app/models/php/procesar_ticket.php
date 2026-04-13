<?php
// 1. Incluir el archivo de conexión
require 'conexion.php';

// 2. Verificar si venimos del formulario (Método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Capturar los datos del formulario
    $titulo = $_POST['titulo'];
    $categoria_id = $_POST['categoria_id'];
    $prioridad_id = $_POST['prioridad_id'];
    $descripcion = $_POST['descripcion'];

    // Valores por defecto para la BD
    $usuario_id = 1;      
    $estado_id = 1;       
    $departamento_id = 1; 

    try {
        // Preparar la consulta SQL
        $sql = "INSERT INTO tickets (titulo, descripcion, usuario_id, estado_id, prioridad_id, categoria_id, departamento_id, fecha_creacion, fecha_actualizacion) 
                VALUES (:titulo, :descripcion, :usuario_id, :estado_id, :prioridad_id, :categoria_id, :departamento_id, NOW(), NOW())";
        
        $stmt = $pdo->prepare($sql);

        // Ejecutar la consulta inyectando los valores capturados
        $stmt->execute([
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':usuario_id' => $usuario_id,
            ':estado_id' => $estado_id,
            ':prioridad_id' => $prioridad_id,
            ':categoria_id' => $categoria_id,
            ':departamento_id' => $departamento_id
        ]);

        // ¡AQUÍ ESTÁ LA SOLUCIÓN!
        // Redirigimos a esta misma página, pero enviando un parámetro "?status=success" por la URL
        header("Location: procesar_ticket.php?status=success");
        exit(); // Siempre debes poner exit() después de una redirección header()

    } catch(PDOException $e) {
        // Si hay error, redirigimos también pero con estado de error
        header("Location: procesar_ticket.php?status=error&msg=" . urlencode($e->getMessage()));
        exit();
    }
} 
// 3. Verificar si estamos mostrando la pantalla después de la redirección (Método GET)
else if (isset($_GET['status'])) {
    
    if ($_GET['status'] == 'success') {
        // Mostrar mensaje de éxito con el fondo #152036 y caja blanca
        echo "<body style='background-color: #152036; margin: 0; padding: 20px; display: flex; justify-content: center; align-items: center; min-height: 100vh;'>";
        
        echo "<div style='font-family: Arial; width: 100%; max-width: 600px; padding: 40px; background: #ffffff; border-radius: 8px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.3);'>";
        echo "<h2 style='color: #28a745; margin-top: 0;'>¡Ticket creado exitosamente!</h2>";
        echo "<p style='color: #333; font-size: 16px;'>El problema ha sido registrado en el sistema. Nos pondremos en contacto pronto.</p>";
        
        // Recuerda cambiar 'crear_ticket.html' si tu archivo se llama distinto
        echo "<br><a href='/TICKETUCAD/formulario-usuario' style='display: inline-block; background-color: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px; margin-top: 10px;'>Regresar al formulario</a>";
        
        echo "</div>";
        echo "</body>";
        
    } else if ($_GET['status'] == 'error') {
        // Mostrar pantalla de error
        $mensajeError = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : "Ocurrió un error desconocido.";
        
        echo "<body style='background-color: #152036; margin: 0; padding: 20px; display: flex; justify-content: center; align-items: center; min-height: 100vh;'>";
        echo "<div style='font-family: Arial; width: 100%; max-width: 600px; padding: 40px; background: #ffffff; border-radius: 8px; text-align: center; border: 2px solid #dc3545;'>";
        echo "<h2 style='color: #dc3545; margin-top: 0;'>Error al crear el ticket</h2>";
        echo "<p style='color: #333; font-size: 16px;'>" . $mensajeError . "</p>";
        echo "<br><a href='crear_ticket.html' style='display: inline-block; background-color: #dc3545; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Volver a intentar</a>";
        echo "</div>";
        echo "</body>";
    }
} 
// 4. Si alguien entra directo a la página sin enviar formulario ni redirección
else {
    echo "Acceso no autorizado.";
}
?>