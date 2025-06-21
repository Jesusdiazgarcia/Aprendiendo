<?php
// Helper function to read environment variables from multiple sources
function get_env_var($key, $default = null) {
    if ($value = getenv($key)) {
        return $value;
    }
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    }
    if (isset($_SERVER[$key])) {
        return $_SERVER[$key];
    }
    return $default;
}

echo "<!DOCTYPE html><html><head><title>Debug</title><style>body { font-family: sans-serif; background: #222; color: #eee; padding: 2em; } strong { color: #0f0; } code { background: #444; padding: 2px 5px; border-radius: 3px; } .fail { color: #f55; }</style></head><body>";
echo "<h1>Diagnóstico de Variables de Entorno</h1>";
echo "<p>Si ves los valores correctos aquí, el problema NO es la inyección de variables.</p>";

echo "<hr><h2>Probando variables garantizadas por Railway:</h2>";
$service_name = get_env_var('RAILWAY_SERVICE_NAME', '--- NO ENCONTRADO ---');
echo "<p><code>RAILWAY_SERVICE_NAME</code>: <strong" . (strpos($service_name, '---') !== false ? ' class="fail"' : '') . ">" . htmlspecialchars($service_name) . "</strong></p>";
$public_domain = get_env_var('RAILWAY_PUBLIC_DOMAIN', '--- NO ENCONTRADO ---');
echo "<p><code>RAILWAY_PUBLIC_DOMAIN</code>: <strong" . (strpos($public_domain, '---') !== false ? ' class="fail"' : '') . ">" . htmlspecialchars($public_domain) . "</strong></p>";

echo "<hr><h2>Probando variables de la base de datos:</h2>";
$mysql_host = get_env_var('MYSQL_HOST', '--- NO ENCONTRADO ---');
echo "<p><code>MYSQL_HOST</code>: <strong" . (strpos($mysql_host, '---') !== false ? ' class="fail"' : '') . ">" . htmlspecialchars($mysql_host) . "</strong></p>";
$mysql_user = get_env_var('MYSQL_USER', '--- NO ENCONTRADO ---');
echo "<p><code>MYSQL_USER</code>: <strong" . (strpos($mysql_user, '---') !== false ? ' class="fail"' : '') . ">" . htmlspecialchars($mysql_user) . "</strong></p>";
$mysql_db = get_env_var('MYSQL_DATABASE', '--- NO ENCONTRADO ---');
echo "<p><code>MYSQL_DATABASE</code>: <strong" . (strpos($mysql_db, '---') !== false ? ' class="fail"' : '') . ">" . htmlspecialchars($mysql_db) . "</strong></p>";

echo "</body></html>";
?> 