<h1 class="nombre-pagina">Nuevo servicio</h1>
<p class="descripcion-pagina">Llene los campos para añadir nuevo servicio</p>

<?php
    include_once __DIR__ . '/../templates/barra.php';
    include_once __DIR__ . '/../templates/alertas.php';
?>

<form method='POST' class="formulario">

<?php
    include_once __DIR__ . '/formulario.php'
?>

    <input 
        type="submit"
        class="boton"
        value="Guardar Servicio"
    >
</form>