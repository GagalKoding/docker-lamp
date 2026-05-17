<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

function envValue($key, $default)
{
    $value = getenv($key);

    return $value !== false ? $value : $default;
}

function getOSInformation()
{
    if (!function_exists('shell_exec') || !is_readable('/etc/os-release')) {
        return [
            'pretty_name' => php_uname(),
        ];
    }

    $os = shell_exec('cat /etc/os-release');

    if (!$os) {
        return [
            'pretty_name' => php_uname(),
        ];
    }

    $listIds = [];
    preg_match_all('/.*=/', $os, $listIds);
    $listIds = $listIds[0] ?? [];

    $listValues = [];
    preg_match_all('/=.*/', $os, $listValues);
    $listValues = $listValues[0] ?? [];

    array_walk($listIds, function (&$value) {
        $value = strtolower(str_replace('=', '', $value));
    });

    array_walk($listValues, function (&$value) {
        $value = preg_replace('/=|"/', '', $value);
    });

    return array_combine($listIds, $listValues);
}

function checkPDOConnection($dsn, $username, $password)
{
    try {
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 3,
        ]);

        return [
            'status' => 'CONNECTED',
            'version' => $pdo->getAttribute(PDO::ATTR_SERVER_VERSION),
        ];
    } catch (Exception $e) {
        return [
            'status' => 'FAILED',
            'version' => $e->getMessage(),
        ];
    }
}

function checkRedisConnection()
{
    if (!class_exists('Redis')) {
        return [
            'status' => 'NOT INSTALLED',
            'version' => '-',
        ];
    }

    try {
        $redis = new Redis();
        $redis->connect('redis', 6379, 2);

        return [
            'status' => 'CONNECTED',
            'version' => $redis->info()['redis_version'] ?? 'Unknown',
        ];
    } catch (Exception $e) {
        return [
            'status' => 'FAILED',
            'version' => $e->getMessage(),
        ];
    }
}

$osInfo = getOSInformation();

$mysql = checkPDOConnection(
    'mysql:host=db;dbname=app',
    envValue('MYSQL_USER', 'root'),
    envValue('MYSQL_PASSWORD', 'root')
);

$postgreSQL = checkPDOConnection(
    'pgsql:host=postgres;port=5432;dbname=app',
    envValue('POSTGRES_USER', 'root'),
    envValue('POSTGRES_PASSWORD', 'root')
);

$redis = checkRedisConnection();

$runtime = [
    'PHP Version' => phpversion(),
    'PHP SAPI' => php_sapi_name(),
    'Timezone' => date_default_timezone_get(),
    'Memory Limit' => ini_get('memory_limit'),
    'Upload Max Filesize' => ini_get('upload_max_filesize'),
    'Post Max Size' => ini_get('post_max_size'),
    'Max Execution Time' => ini_get('max_execution_time'),
    'Loaded php.ini' => php_ini_loaded_file(),
    'OPcache Enabled' => extension_loaded('Zend OPcache') ? 'YES' : 'NO',
    'Redis Extension' => extension_loaded('redis') ? 'YES' : 'NO',
    'PostgreSQL Extension' => extension_loaded('pdo_pgsql') ? 'YES' : 'NO',
    'ionCube Loader' => function_exists('ioncube_loader_version')
        ? ioncube_loader_version()
        : 'NOT INSTALLED',
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Docker-LAMP v2</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 40px 20px;
            background: #0f172a;
            color: #e2e8f0;
            font-family: 'Inter', sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .hero {
            background: linear-gradient(135deg, #111827 0%, #1e293b 100%);
            border: 1px solid #334155;
            border-radius: 24px;
            padding: 40px;
            margin-bottom: 32px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
        }

        .hero img {
            width: 320px;
            max-width: 100%;
            margin-bottom: 24px;
        }

        .hero h1 {
            margin: 0 0 12px;
            font-size: 42px;
            font-weight: 700;
            color: #f8fafc;
        }

        .hero p {
            margin: 0;
            font-size: 18px;
            line-height: 1.7;
            color: #cbd5e1;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
        }

        .card {
            background: #111827;
            border: 1px solid #334155;
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
        }

        .card h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 22px;
            color: #f8fafc;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table tr:not(:last-child) {
            border-bottom: 1px solid #1e293b;
        }

        .table td {
            padding: 12px 0;
            vertical-align: top;
            font-size: 14px;
        }

        .table td:first-child {
            color: #94a3b8;
            width: 45%;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.4px;
        }

        .success {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border: 1px solid rgba(74, 222, 128, 0.3);
        }

        .failed {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(248, 113, 113, 0.3);
        }

        .footer {
            margin-top: 32px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }

        .footer a {
            color: #38bdf8;
            text-decoration: none;
        }

        code {
            background: #0f172a;
            border: 1px solid #334155;
            padding: 2px 6px;
            border-radius: 6px;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="container">

    <section class="hero">
        <img
            src="https://raw.githubusercontent.com/GagalKoding/docker-lamp/master/docs/logo.svg"
            alt="Docker-LAMP Logo"
        >

        <h1>Docker-LAMP v2</h1>

        <p>
            Enterprise-grade multi-runtime PHP development platform powered by
            Apache, PHP-FPM, MariaDB, PostgreSQL, Redis-ready architecture,
            phpMyAdmin, pgAdmin, and dynamic runtime switching.
        </p>
    </section>

    <section class="grid">

        <div class="card">
            <h2>System Information</h2>

            <table class="table">
                <tr>
                    <td>Operating System</td>
                    <td><?= htmlspecialchars($osInfo['pretty_name'] ?? 'Unknown') ?></td>
                </tr>

                <tr>
                    <td>Apache</td>
                    <td><?= htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') ?></td>
                </tr>

                <tr>
                    <td>Container Hostname</td>
                    <td><?= htmlspecialchars(gethostname()) ?></td>
                </tr>
            </table>
        </div>

        <div class="card">
            <h2>PHP Runtime</h2>

            <table class="table">
                <?php foreach ($runtime as $key => $value): ?>
                    <tr>
                        <td><?= htmlspecialchars($key) ?></td>
                        <td><?= htmlspecialchars(strval($value)) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="card">
            <h2>Service Health</h2>

            <table class="table">
                <tr>
                    <td>MariaDB</td>
                    <td>
                        <span class="badge <?= $mysql['status'] === 'CONNECTED' ? 'success' : 'failed' ?>">
                            <?= htmlspecialchars($mysql['status']) ?>
                        </span>
                        <br><br>
                        <?= htmlspecialchars($mysql['version']) ?>
                    </td>
                </tr>

                <tr>
                    <td>PostgreSQL</td>
                    <td>
                        <span class="badge <?= $postgreSQL['status'] === 'CONNECTED' ? 'success' : 'failed' ?>">
                            <?= htmlspecialchars($postgreSQL['status']) ?>
                        </span>
                        <br><br>
                        <?= htmlspecialchars($postgreSQL['version']) ?>
                    </td>
                </tr>

                <tr>
                    <td>Redis</td>
                    <td>
                        <span class="badge <?= $redis['status'] === 'CONNECTED' ? 'success' : 'failed' ?>">
                            <?= htmlspecialchars($redis['status']) ?>
                        </span>
                        <br><br>
                        <?= htmlspecialchars($redis['version']) ?>
                    </td>
                </tr>
            </table>
        </div>

        <div class="card">
            <h2>Quick Access</h2>

            <table class="table">
                <tr>
                    <td>phpMyAdmin</td>
                    <td>
                        <a href="http://localhost:8080" target="_blank">
                            http://localhost:8080
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>pgAdmin</td>
                    <td>
                        <a href="http://localhost:8081" target="_blank">
                            http://localhost:8081
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>MariaDB Host</td>
                    <td><code>db:3306</code></td>
                </tr>

                <tr>
                    <td>PostgreSQL Host</td>
                    <td><code>postgres:5432</code></td>
                </tr>

                <tr>
                    <td>PHP Runtime Switching</td>
                    <td><code>.htaccess</code></td>
                </tr>
            </table>
        </div>

    </section>

    <div class="footer">
        Docker-LAMP v2 • Multi-Runtime PHP Development Platform •
        <a href="https://github.com/GagalKoding/docker-lamp" target="_blank">
            GitHub Repository
        </a>
    </div>

</div>

</body>
</html>