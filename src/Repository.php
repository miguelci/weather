<?php
declare(strict_types=1);

namespace Weather;

use PDO;
use RuntimeException;
use function file_exists;
use function fopen;

final class Repository
{
    private const PATH_TO_SQLITE_FILE = 'var/weather.db';

    private PDO $pdo;

    public function __construct()
    {
        if (! file_exists(self::PATH_TO_SQLITE_FILE)) {
            fopen(self::PATH_TO_SQLITE_FILE, 'w');
        }
        $this->pdo = new PDO('sqlite:' . self::PATH_TO_SQLITE_FILE);
    }

    public function connect(): PDO
    {
        if (! $this->pdo instanceof PDO) {
            $this->pdo = new PDO("sqlite:" . self::PATH_TO_SQLITE_FILE);
        }
        $this->createDatabase();

        return $this->pdo;
    }

    public function dropPredictionsTable(): void
    {
        $this->pdo->exec('DELETE FROM predictions');
    }

    private function createDatabase(): void
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS predictions (
                id   INTEGER PRIMARY KEY,
                location VARCHAR(32) NOT NULL,
                temperature_date DATETIME NOT NULL,
                value NUMERIC(6,2) NOT NULL
          )'
        );
    }

    public function addPrediction(Prediction $prediction): void
    {
        $sql = 'INSERT INTO predictions(location, temperature_date, value) 
            VALUES(:location, :temperature_date, :value)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'location' => $prediction->getLocation(),
            'temperature_date' => $prediction->getDateTime()->format('Y-m-d H:i:s'),
            'value' => $prediction->getTemperature()->getValue(),
        ]);
    }

    /** @return string[] */
    public function getAllPredictions(string $date): array
    {
        $firstDate = $date . ' 00:00:00';
        $secondDate = $date . ' 12:00:00';

        return $this->query(
            'SELECT * FROM predictions WHERE temperature_date BETWEEN :first_date AND :second_date',
            [
                'first_date' => $firstDate, //(new DateTime($date))->format('Y-m-d H:i:s'),
                'second_date' => $secondDate,
            ]
        );
    }

    /** @return string[] */
    public function getAllPossibleDates(): array
    {
        return $this->query('SELECT DISTINCT(substr(temperature_date, 0, 11)) date FROM predictions');
    }

    /**
     * @param string[] $params
     * @return mixed[]
     */
    private function query(string $query, array $params = []): array
    {
        $statement = $this->pdo->query($query);

        if ($statement === false) {
            throw new RuntimeException('Impossible to retrieve query result');
        }

        if ($params !== []) {
            $statement->execute($params);
        }

        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($data === false) {
            throw new RuntimeException('Data could not be retrieved');
        }

        return $data;
    }
}
