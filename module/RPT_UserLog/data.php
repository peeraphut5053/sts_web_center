<?php
header('Content-Type: application/json; charset=utf-8');

$load = isset($_REQUEST['load']) ? trim($_REQUEST['load']) : '';

if ($load !== 'report') {
    echo json_encode(array(
        'success' => false,
        'message' => 'Invalid load action'
    ));
    exit;
}

$logFile = 'C:/Apache/Apache24/logs/access.log';

if (!is_readable($logFile)) {
    echo json_encode(array(
        'success' => false,
        'message' => 'Cannot read log file: ' . $logFile
    ));
    exit;
}

$fromDate = isset($_REQUEST['from_date']) ? trim($_REQUEST['from_date']) : '';
$toDate = isset($_REQUEST['to_date']) ? trim($_REQUEST['to_date']) : '';
$methodFilter = isset($_REQUEST['method']) ? strtoupper(trim($_REQUEST['method'])) : '';
$statusFilter = isset($_REQUEST['status']) ? trim($_REQUEST['status']) : '';
$keyword = isset($_REQUEST['keyword']) ? trim($_REQUEST['keyword']) : '';
$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 500;
$limit = $limit > 0 ? min($limit, 3000) : 500;

$summary = array(
    'total' => 0,
    'success' => 0,
    'redirect' => 0,
    'client_error' => 0,
    'server_error' => 0,
    'not_found' => 0,
    'bytes' => 0,
    'unique_ip' => 0,
    'latest_time' => ''
);

$ipMap = array();
$statusMap = array();
$methodMap = array();
$pathMap = array();
$hourMap = array();
$rows = array();
$scanned = 0;
$matched = 0;

$handle = fopen($logFile, 'r');
if ($handle === false) {
    echo json_encode(array(
        'success' => false,
        'message' => 'Cannot open log file'
    ));
    exit;
}

while (($line = fgets($handle)) !== false) {
    $scanned++;
    $entry = parseAccessLogLine(trim($line));

    if ($entry === null) {
        continue;
    }

    if ($fromDate !== '' && $entry['date'] < $fromDate) {
        continue;
    }

    if ($toDate !== '' && $entry['date'] > $toDate) {
        continue;
    }

    if ($methodFilter !== '' && $entry['method'] !== $methodFilter) {
        continue;
    }

    if ($statusFilter !== '' && strval($entry['status']) !== $statusFilter) {
        continue;
    }

    if ($keyword !== '' && stripos($entry['path'], $keyword) === false && stripos($entry['ip'], $keyword) === false) {
        continue;
    }

    $matched++;
    $summary['total']++;
    $summary['bytes'] += $entry['bytes'];
    $summary['latest_time'] = $entry['datetime'];

    $ipMap[$entry['ip']] = true;
    incrementCounter($statusMap, strval($entry['status']));
    incrementCounter($methodMap, $entry['method']);
    incrementCounter($pathMap, $entry['path']);
    incrementCounter($hourMap, $entry['hour']);

    if ($entry['status'] >= 200 && $entry['status'] < 300) {
        $summary['success']++;
    } else if ($entry['status'] >= 300 && $entry['status'] < 400) {
        $summary['redirect']++;
    } else if ($entry['status'] >= 400 && $entry['status'] < 500) {
        $summary['client_error']++;
    } else if ($entry['status'] >= 500) {
        $summary['server_error']++;
    }

    if ($entry['status'] == 404) {
        $summary['not_found']++;
    }

    $rows[] = array(
        'datetime' => $entry['datetime'],
        'ip' => $entry['ip'],
        'method' => $entry['method'],
        'path' => $entry['path'],
        'protocol' => $entry['protocol'],
        'status' => $entry['status'],
        'bytes' => $entry['bytes'],
        'raw' => $entry['raw']
    );

    if (count($rows) > $limit) {
        array_shift($rows);
    }
}

fclose($handle);

$summary['unique_ip'] = count($ipMap);

echo json_encode(array(
    'success' => true,
    'source' => $logFile,
    'scanned' => $scanned,
    'matched' => $matched,
    'limit' => $limit,
    'summary' => $summary,
    'status' => counterToRows($statusMap, 'status', 20),
    'methods' => counterToRows($methodMap, 'method', 20),
    'top_paths' => counterToRows($pathMap, 'path', 15),
    'hourly' => counterToRows($hourMap, 'hour', 24, true),
    'rows' => array_reverse($rows)
));

function parseAccessLogLine($line) {
    if ($line === '') {
        return null;
    }

    $pattern = '/^(\S+) \S+ \S+ \[([^\]]+)\] "([A-Z]+)\s+([^"]*?)(?:\s+(HTTP\/[0-9.]+))?"\s+(\d{3})\s+(\S+)/';

    if (!preg_match($pattern, $line, $matches)) {
        return null;
    }

    $dateTime = DateTime::createFromFormat('d/M/Y:H:i:s O', $matches[2]);
    if ($dateTime === false) {
        return null;
    }

    $bytes = $matches[7] === '-' ? 0 : intval($matches[7]);

    return array(
        'ip' => $matches[1],
        'timestamp' => $dateTime->getTimestamp(),
        'date' => $dateTime->format('Y-m-d'),
        'datetime' => $dateTime->format('Y-m-d H:i:s'),
        'hour' => $dateTime->format('Y-m-d H:00'),
        'method' => $matches[3],
        'path' => $matches[4],
        'protocol' => isset($matches[5]) ? $matches[5] : '',
        'status' => intval($matches[6]),
        'bytes' => $bytes,
        'raw' => $line
    );
}

function incrementCounter(&$counter, $key) {
    if (!isset($counter[$key])) {
        $counter[$key] = 0;
    }

    $counter[$key]++;
}

function counterToRows($counter, $keyName, $limit, $sortByKey = false) {
    if ($sortByKey) {
        ksort($counter);
    } else {
        arsort($counter);
    }

    $rows = array();
    foreach ($counter as $key => $count) {
        $rows[] = array(
            $keyName => $key,
            'count' => $count
        );

        if (count($rows) >= $limit) {
            break;
        }
    }

    return $rows;
}
?>
