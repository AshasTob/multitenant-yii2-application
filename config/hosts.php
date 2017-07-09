<?php
$available_hosts = [
    //put hosts that your application should be using, in format as shown below:
    'tenant1' => [
        'domains' => ['tenant1.dev'],
        'id'      => 1
    ],
    'tenant2' => [
        'domains' => ['tenant2.dev', 'another-local-site.dev'],
        'id'      => 2
    ],
];
$recognized = false;
foreach ($available_hosts as $hostName => $host) {
    foreach ($host['domains'] as $domain) {
        if ($_SERVER['HTTP_HOST'] === $domain) {
            define('CURRENT_TENANT', $hostName);
            define('CURRENT_TENANT_ID', $host['id']);
            $recognized = true;
        }
    }
}
if (!isset($_SERVER['HTTP_HOST']) || !$recognized) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
    exit;
}
?>