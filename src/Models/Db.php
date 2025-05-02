<?php

namespace App\Models;

use SQLite3;
use App\Exceptions\DatabaseQueryException;

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
    }

    public function runQuery(string $query): array
    {
        $result = $this->connection->query($query);
        $array = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            if (!$row) {
                throw new DatabaseQueryException("Something went wrong when executing a query towards the sqlite3 database: " . $this->connection->lastErrorMsg());
            }
            $array[] = $row;
        }
        return $array;
    }

    public function runQueryWithParams(string $query, array $columns, array $params, bool $isFetch): mixed
    {
        $stmt = $this->connection->prepare($query);

        foreach ($columns as $index => $column) {
            $stmt->bindValue($column, $params[$index]);
        }

        $result = $stmt->execute();
        $errorMessage = $this->connection->lastErrorMsg();

        if ($errorMessage !== "not an error") {
            throw new DatabaseQueryException("Something went wrong when executing a query towards the sqlite3 database: " . $errorMessage);
        }

        if ($isFetch) {
            $array = $result->fetchArray(SQLITE3_ASSOC);
            $stmt->close();
            return $array;
        }

        return true;
    }
}
