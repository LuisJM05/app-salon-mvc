<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesion con tus datos</p>

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
    <div class="campo">
        <label for="password">Contraseña</label>
        <input
            require
            type="password"
            id="password"
            name="password"
            placeholder="Tu Contraseña"
        >
    </div>

    <input 
        type="submit" 
        class="boton" 
        value="Iniciar Sesion"
    >
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿No tienes cuenta? Crear una cuenta</a>
    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>