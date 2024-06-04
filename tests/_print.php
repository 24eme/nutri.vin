<?php

if (! $test) {
    echo 'FAIL: pas d\'objet test';
}

// Affichage des résultats
foreach ($test->results() as $result) {
    $status = ($result['status']) ? 'PASS' : 'FAIL';
    if (php_sapi_name() === 'cli') {
        switch ($status) {
            case 'PASS': $status = "\033[32m".$status."\033[0m"; break;
            case 'FAIL': $status = "\033[31m".$status."\033[0m"; break;
        }
    }
    echo sprintf("%s: %s (%s)".PHP_EOL, $status, $result['text'], $result['source']);
}
