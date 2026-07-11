<?php

$source = $argv[1] ?? dirname(__DIR__, 2).'/.cursor/projects/Users-admin-Documents-Projects-Laravel-school/uploads/library-2.md';
$output = $argv[2] ?? __DIR__.'/library-blocks.json';

if (! is_file($source)) {
    fwrite(STDERR, "Source file not found: {$source}\n");
    exit(1);
}

$file = file_get_contents($source);
$lines = explode("\n", $file);
$blocks = [];
$currentSubject = null;
$rows = [];
$headers = ['Author(s)', 'Title', 'Publisher', 'Year', 'Accession No.', 'Class No.'];

$flush = function () use (&$blocks, &$currentSubject, &$rows, $headers): void {
    if ($currentSubject && $rows !== []) {
        $blocks[] = [
            'type' => 'table',
            'title' => $currentSubject,
            'headers' => $headers,
            'rows' => $rows,
        ];
    }

    $rows = [];
};

foreach ($lines as $line) {
    if (preg_match('/^##\s+\*\*(.+?)\*\*/', $line, $matches)) {
        $flush();
        $currentSubject = trim($matches[1]);
        $headers = match ($currentSubject) {
            'MA/M.PHIL THESIS', 'PH.D THESIS' => ['School', 'Name', 'Topic', 'Year'],
            default => ['Author(s)', 'Title', 'Publisher', 'Year', 'Accession No.', 'Class No.'],
        };

        continue;
    }

    if (preg_match('/\*\*SUBJECT\*\*\s*\*\*:\s*(.+?)\*\*/', $line, $matches)) {
        $flush();
        $currentSubject = trim($matches[1]);
        $headers = ['Author(s)', 'Title', 'Publisher', 'Year', 'Accession No.', 'Class No.'];

        continue;
    }

    if (preg_match('/^\*\*(PH\.?D THESIS|MA\/M\.PHIL THESIS)\*\*/', $line, $matches)) {
        $flush();
        $currentSubject = trim($matches[1]);
        $headers = ['School', 'Name', 'Topic', 'Year'];

        continue;
    }

    if (! $currentSubject || ! str_contains($line, '|')) {
        continue;
    }

    if (str_contains($line, 'AUTHOR') && str_contains($line, 'TITLE')) {
        continue;
    }

    if (str_contains($line, 'NAME OF JOURNAL') && str_contains($line, 'INSTITUTION')) {
        $headers = ['Name of Journal', 'Institution'];
    }

    if (str_contains($line, 'SCHOOL') && str_contains($line, 'TOPIC')) {
        continue;
    }

    if (preg_match('/^\|\s*---/', $line)) {
        continue;
    }

    if (preg_match('/^\|\s*\|\s*\|/', $line)) {
        continue;
    }

    $cells = array_map('trim', array_filter(explode('|', trim($line, '|'))));

    if (count($cells) < 2) {
        continue;
    }

    while (count($cells) < count($headers)) {
        $cells[] = '';
    }

    $cells = array_slice($cells, 0, count($headers));

    if (implode('', $cells) === '') {
        continue;
    }

    $rows[] = $cells;
}

$flush();

$periodicals = [];
$other = [];
$mode = null;

foreach ($lines as $line) {
    if (str_contains($line, '## **JOURNALS/PERIODICALS COLLECTION**')) {
        $mode = 'periodicals';

        continue;
    }

    if (str_contains($line, '## **OTHER COLLECTIONS**')) {
        $mode = 'other';

        continue;
    }

    if (str_contains($line, '### WHY OPT FOR IDTM')) {
        $mode = null;

        continue;
    }

    if (! $mode) {
        continue;
    }

    if (preg_match('/^\d+\\.\s+(.+)/', $line, $matches)) {
        if ($mode === 'periodicals') {
            $periodicals[] = trim($matches[1]);
        } else {
            $other[] = trim($matches[1]);
        }
    }
}

if ($periodicals !== []) {
    $blocks[] = [
        'type' => 'list',
        'title' => 'Journals/Periodicals Collection',
        'items' => $periodicals,
    ];
}

if ($other !== []) {
    $blocks[] = [
        'type' => 'list',
        'title' => 'Other Collections',
        'items' => $other,
    ];
}

file_put_contents($output, json_encode($blocks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo 'Wrote '.count($blocks).' blocks to '.$output.PHP_EOL;
