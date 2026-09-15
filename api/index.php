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

// Delegate to Laravel public/index.php
require __DIR__ . "/../public/index.php";
