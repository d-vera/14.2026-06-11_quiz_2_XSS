<?php
$comentario = filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
file_put_contents("comentarios.txt", $comentario . "\n", FILE_APPEND);
echo "Comentario guardado. <a href='ver.php'>Ver comentarios</a>";
?>