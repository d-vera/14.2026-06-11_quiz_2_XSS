<?php
$nombre = htmlspecialchars($_GET['nombre'], ENT_QUOTES, 'UTF-8');
echo "Hola, $nombre!";
?>