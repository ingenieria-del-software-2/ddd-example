<?php

use LuisCusihuaman\Apps\Backoffice\Backend\BackofficeBackendKernel;
use Symfony\Component\ErrorHandler\DebugClassLoader;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__) . '/../../bootstrap.php';

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
    DebugClassLoader::enable();

}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? $_ENV['TRUSTED_HOSTS'] ?? false) {
    Request::setTrustedHosts([$trustedHosts]);
}

$kernel = new BackofficeBackendKernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
