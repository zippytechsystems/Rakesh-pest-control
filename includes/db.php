<?php
/**
 * Harsha Pest Control - Database Connection Manager
 * 
 * Target: Hostinger MySQL / MariaDB via PDO
 * Local Dev: Graceful SQLite fallback supported for local Windows testing
 */

defined('HARSHA_APP') or define('HARSHA_APP', true);

// Check if config.php exists; if not, return null so installer/setup can guide the user
$configFile = __DIR__ . '/../config.php';
if (!file_exists($configFile)) {
    // Return null or guide to setup
    $config = null;
} else {
    $config = require $configFile;
}

// Function to obtain the single PDO database instance
function get_db_connection() {
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $configFile = __DIR__ . '/../config.php';
    if (!file_exists($configFile)) {
        throw new Exception("Configuration file 'config.php' is missing. Please copy 'config.example.php' to 'config.php' and enter your Hostinger database details.");
    }

    $config = require $configFile;
    $dbConfig = $config['db'] ?? [];

    $driver = $dbConfig['driver'] ?? 'mysql';

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        if ($driver === 'sqlite') {
            // Local dev testing database
            $sqlitePath = $dbConfig['sqlite_path'] ?? (__DIR__ . '/../data/harsha_local.sqlite');
            $dir = dirname($sqlitePath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $pdo = new PDO("sqlite:" . $sqlitePath, null, null, $options);
            $pdo->exec("PRAGMA foreign_keys = ON;");
        } else {
            // Standard Hostinger MySQL / MariaDB connection
            $host    = $dbConfig['host'] ?? 'localhost';
            $port    = $dbConfig['port'] ?? 3306;
            $dbname  = $dbConfig['dbname'] ?? '';
            $user    = $dbConfig['username'] ?? '';
            $pass    = $dbConfig['password'] ?? '';
            $charset = $dbConfig['charset'] ?? 'utf8mb4';

            if (empty($dbname) || $dbname === 'u123456789_harsha_db') {
                throw new Exception("Database credentials are not yet configured in config.php. Please enter your Hostinger database name, username, and password.");
            }

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
            $pdo = new PDO($dsn, $user, $pass, $options);
            $pdo->exec("SET NAMES '{$charset}'");
        }
    } catch (PDOException $e) {
        $env = $config['app']['env'] ?? 'production';
        if ($env === 'development') {
            throw new Exception("Database Connection Failed: " . $e->getMessage());
        } else {
            error_log("Database Connection Error: " . $e->getMessage());
            throw new Exception("Could not connect to the database. Please verify your Hostinger MySQL credentials in config.php.");
        }
    }

    return $pdo;
}
