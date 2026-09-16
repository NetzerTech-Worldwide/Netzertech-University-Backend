<?php

/**
 * Vercel Serverless Entrypoint for Netzertech University Laravel Application
 */

// Initialize ephemeral writable storage paths in /tmp
$tmpStorage = "/tmp/storage";
$directories = [
    $tmpStorage,
    $tmpStorage . "/app",
    $tmpStorage . "/app/public",
    $tmpStorage . "/framework",
    $tmpStorage . "/framework/cache",
    $tmpStorage . "/framework/cache/data",
    $tmpStorage . "/framework/sessions",
    $tmpStorage . "/framework/views",
    $tmpStorage . "/logs",
];

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        @mkdir($directory, 0755, true);
    }
}

// Set environment variables if not already defined
if (!getenv("VIEW_COMPILED_PATH")) {
    putenv("VIEW_COMPILED_PATH=" . $tmpStorage . "/framework/views");
}

// Prevent Symfony/Laravel from treating '/api' as a subfolder base URL on Vercel
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

// Normalize URI if /api was stripped by Vercel or if requested directly via /v1
if (isset($_SERVER['REQUEST_URI'])) {
    if (preg_match('#^/v1(/|$)#', $_SERVER['REQUEST_URI'])) {
        $_SERVER['REQUEST_URI'] = '/api' . $_SERVER['REQUEST_URI'];
    }
}
if (isset($_SERVER['PATH_INFO'])) {
    if (preg_match('#^/v1(/|$)#', $_SERVER['PATH_INFO'])) {
        $_SERVER['PATH_INFO'] = '/api' . $_SERVER['PATH_INFO'];
    }
}
if (isset($_SERVER['UNENCODED_URL'])) {
    if (preg_match('#^/v1(/|$)#', $_SERVER['UNENCODED_URL'])) {
        $_SERVER['UNENCODED_URL'] = '/api' . $_SERVER['UNENCODED_URL'];
    }
}

// Delegate to Laravel public/index.php
require __DIR__ . "/../public/index.php";
