<h1 class="nombre-pagina" >Olvide mi contraseña</h1>
<p class="descripcion-pagina">Escribe tu E-mail para restablecer tu contraseña</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<form class="formulario" method="POST">
    <div class="campo">
        <label for="email">E-mail</label>
        <input
            require
            type="email"
            id="email"
            name="email"
            placeholder="Tu Email"
        >
    </div>

    <input 
        type="submit" 
        class="boton" 
        value="Enviar codigo"
    >

</form>

<div class="acciones">
    <a href="/">¿Ya tienes cuenta? Iniciar session</a>
    <a href="/crear-cuenta">¿No tienes cuenta? Crear una cuenta</a>
</div>