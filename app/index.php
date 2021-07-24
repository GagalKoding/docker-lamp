<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
$db = new PDO('mysql:host=localhost', 'root', null);

function getOSInformation()
 {
     if (false == function_exists("shell_exec") || false == is_readable("/etc/os-release")) {
         return null;
     }

      $os         = shell_exec('cat /etc/os-release');
     $listIds    = preg_match_all('/.*=/', $os, $matchListIds);
     $listIds    = $matchListIds[0];

      $listVal    = preg_match_all('/=.*/', $os, $matchListVal);
     $listVal    = $matchListVal[0];

      array_walk($listIds, function(&$v, $k){
         $v = strtolower(str_replace('=', '', $v));
     });

      array_walk($listVal, function(&$v, $k){
         $v = preg_replace('/=|"/', '', $v);
     });

      return array_combine($listIds, $listVal);
 }
$osInfo = getOSInformation();
?>
<!doctype html>
<html lang=en>
<head>
    <meta charset=utf-8>
    <title>Hello World from Docker-LAMP</title>
    <link rel="icon" href="https://raw.githubusercontent.com/docker/docker.github.io/master/favicon.ico" type="image/x-icon" />
    <style>
        @import 'https://fonts.googleapis.com/css?family=Montserrat|Raleway|Source+Code+Pro';

        body { font-family: 'Raleway', sans-serif; }
        h2 { font-family: 'Montserrat', sans-serif; }
        pre {
            font-family: 'Source Code Pro', monospace;

            padding: 16px;
            overflow: auto;
            font-size: 85%;
            line-height: 1.45;
            background-color: #f7f7f7;
            border-radius: 3px;

            word-wrap: normal;
        }

        .container {
            max-width: 1024px;
            width: 100%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <img src="https://raw.githubusercontent.com/GagalKoding/docker-lamp/master/docs/logo.svg" width="406" alt="Docker LAMP logo" />
            <h2>Welcome to <a href="https://github.com/gagalkoding/docker-lamp" target="_blank">Docker-Lamp</a> a.k.a gagalkoding/lamp</h2>
        </header>
        <article>
            <p>
                For documentation, <a href="https://github.com/gagalkoding/docker-lamp" target="_blank">click here</a>.
            </p>
        </article>
        <section>
            <pre>
OS: <?php echo $osInfo['pretty_name']; ?><br/>
Apache: <?php echo $_SERVER['SERVER_SOFTWARE']; ?><br/>
MySQL Version: <?php echo $db->getAttribute( PDO::ATTR_SERVER_VERSION ); ?><br/>
PHP Version: <?php echo phpversion(); ?><br/>
ionCube Version: <?php echo ioncube_loader_version(); ?>
            </pre>
        </section>
    </div>
</body>
</html>