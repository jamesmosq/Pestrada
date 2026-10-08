<?php
// Las credenciales se toman de config.php (raiz del repo)
require_once __DIR__ . '/../../../config.php';

// getDBConnection() esta definida en config.php y devuelve un objeto PDO
$conn = getDBConnection(DB_NAME_TODO);
