<?php

namespace App\Models;

use SQLite3;
use App\Exceptions\DatabaseQueryException;
use \SQLite3Exception;

class Db
{
    private SQLite3 $connection;

    public function __construct()
    {
    }

    public function connect(): void
    {
        $dbPath = constant("SQLITE_DB_PATH");
        $this->connection = new SQLite3($dbPath);
        $this->connection->enableExceptions(true);
    }

    public function runQuery(string $query): array
    {
        try {
            $result = $this->connection->query($query);
            $array = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $array[] = $row;
            }
        } catch (SQLite3Exception $e) {
            throw new DatabaseQueryException("Something went wrong when executing a query towards the sqlite3 database: " . $e);
        }
        return $array;
    }

    public function runQueryWithParams(string $query, array $columns, array $params, bool $isFetch): mixed
    {
        $stmt = $this->connection->prepare($query);

        foreach ($columns as $index => $column) {
            $stmt->bindValue($column, $params[$index]);
        }

        try {
            $result = $stmt->execute();
        } catch (SQLite3Exception $e) {
            throw new DatabaseQueryException("Something went wrong when executing a query towards the sqlite3 database: " . $e);
        }

        if ($isFetch) {
            $array = $result->fetchArray(SQLITE3_ASSOC);
            $stmt->close();
            return $array;
        }

        return true;
    }
}
