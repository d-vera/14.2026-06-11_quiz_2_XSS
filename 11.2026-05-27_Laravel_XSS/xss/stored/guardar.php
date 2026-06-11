<?php
$comentario = $_POST['comentario'];
file_put_contents("comentarios.txt", $comentario . "\n", FILE_APPEND);
echo "Comentario guardado. <a href='ver.php'>Ver comentarios</a>";
?>