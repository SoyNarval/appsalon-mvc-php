<h1 class="nombre-pagina">Recuperar Contraseña</h1>
<p class="descripcion-pagina">Relena el siguiente formulario con tu nueva contraseña</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>


<?php if($error){ return;} ?>
<form class="formulario"  method="POST">
    <div class="campo">
        <label for="contraseña">Nueva Contraseña</label>
            <input
                type="password"
                id="contraseña"
                name="contraseña"
                placeholder="Tu Nueva Contraseña"
            />
    </div>
    <input type="submit" class="boton" value="Enviar">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
    <a href="/crear">¿Aún no tienes cuenta? Crea una</a>
</div>