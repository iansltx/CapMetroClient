<?php

require __DIR__ . '/autoload.php';

$results = (new iansltx\CapMetroClient\RealTimeLocationQuery())->fetchAll();

echo "Count: " . count($results) . "\n";

foreach ($results as $result) {
    foreach (get_class_methods($result) as $method) {
        if ($method === '__construct')
            continue;
        $output = $result->$method();
        if ($output instanceof \DateTime)
            $output = $output->format('F j, Y g:i A');
        if ($output instanceof \stdClass)
            $output = print_r($output, true);
        echo $method . ': ' . $output . "\n";
    }
}
