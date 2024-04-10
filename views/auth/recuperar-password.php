<h1 class="nombre-pagina" >Recuperar contraseña</h1>
<p class="descripcion-pagina">Escribe tu nueva contraseña</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<?php if(!$error){?>
<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Contraseña</label>
        <input
            require
            type="password"
            id="password"
            name="password"
            placeholder="Nueva contraseña"
        >
    </div>

    <input 
        type="submit" 
        class="boton" 
        value="Guardar nueva contraseña"
    >

</form>

<?php };?>

<div class="acciones">
    <a href="/">¿Ya tienes cuenta? Iniciar session</a>
    <a href="/crear-cuenta">¿No tienes cuenta? Crear una cuenta</a>
</div>