<?php
/**
 * Router script for PHP built-in server on Railway
 *
 * This ensures proper routing of requests to PHP files and static assets
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$file = __DIR__ . $uri;

// Log all requests for debugging
error_log("Router: Request URI: " . $uri);
error_log("Router: File path: " . $file);

// If requesting a directory, try index.html
if (is_dir($file)) {
    if (file_exists($file . '/index.html')) {
        $file = $file . '/index.html';
        error_log("Router: Serving index.html from directory");
    }
}

// If file exists and is not a PHP file, serve it directly
if (file_exists($file) && !preg_match('/\.php$/', $file)) {
    error_log("Router: Serving static file: " . $file);
    return false; // Let PHP built-in server handle it
}

// If it's a PHP file and exists, execute it
if (file_exists($file) && preg_match('/\.php$/', $file)) {
    error_log("Router: Executing PHP file: " . $file);
    require $file;
    return true;
}

// If file doesn't exist, check if it's a PHP endpoint without .php extension
if (!file_exists($file) && !preg_match('/\.php$/', $uri)) {
    $phpFile = $file . '.php';
    if (file_exists($phpFile)) {
        error_log("Router: Executing PHP file (added .php): " . $phpFile);
        require $phpFile;
        return true;
    }
}

// File not found - serve index.html for SPA routing
if (!file_exists($file)) {
    error_log("Router: File not found, serving index.html");
    if (file_exists(__DIR__ . '/index.html')) {
        require __DIR__ . '/index.html';
        return true;
    }
}

// If we get here, return 404
error_log("Router: 404 - File not found: " . $uri);
http_response_code(404);
echo "404 - File Not Found: " . htmlspecialchars($uri);
return true;
?>
