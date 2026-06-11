<?php
$comentarios = file("comentarios.txt");
foreach ($comentarios as $comentario) {
    echo "<p>$comentario</p>";
}
?>