<?php

namespace App\Models;

use \SQLite3;

class Db
{
    private SQLite3 $connection;

    public function __construct()
    {
    }

    public function connect(): void
    {
        $this->connection = new SQLite3(SQLITE_DB_PATH);
    }

    public function runQuery(string $query): array
    {
        $result = $this->connection->query($query);
        $array = [];
        while($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $array[] = $row;
        }
        return $array;
    }

    public function runQueryWithParams(string $query, array $columns, array $params): array
    {
        $stmt = $this->connection->prepare($query);
        foreach ($columns as $index => $column) {
            $stmt->bindValue($column, $params[$index]);
        }
        $result = $stmt->execute();
        $array = $result->fetchArray(SQLITE3_ASSOC);
        $stmt->close();
        return $array ? $array : [];
    }
}
