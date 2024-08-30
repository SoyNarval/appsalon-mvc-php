<h1 class="nombre-pagina">Recuperar Contraseña</h1>
<p class="descripcion-pagina">Crea una nueva contraseña rellenando el formulario</p>

<?php
    include_once __DIR__ . "/../templates/alertas.php"
?>

<form class="formulario" action="/olvide" method="POST">
    <div class="campo">
        <label for="email">E-mail</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="Tu E-mail"
            />
    </div>
    <input type="submit" class="boton" value="Enviar">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes cuenta? Crea una</a>
</div>
