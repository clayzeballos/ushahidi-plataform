<?php

require_once __DIR__.'/vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

if (getenv("CLEARDB_DATABASE_URL")) {
    $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
    // Push url parts into env
    putenv("DB_HOST=" . $url["host"]);
    putenv("DB_USERNAME=" . $url["user"]);
    putenv("DB_PASSWORD=" . $url["pass"]);
    putenv("DB_DATABASE=" . substr($url["path"], 1));
}

return [
    'paths' => [
        'migrations' => __DIR__ . '/migrations',
        'seeds' => __DIR__ . '/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'ushahidi',
        'ushahidi' => [
            'adapter' => 'mysql', // todo: how to make this dynamic?
            'host' => getenv('DB_HOST'),
            'name' => getenv('DB_DATABASE'),
            'user' => getenv('DB_USERNAME'),
            'pass' => getenv('DB_PASSWORD'),
            'charset' => 'utf8',
        ],
    ]
];
