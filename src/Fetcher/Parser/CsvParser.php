<?php
declare(strict_types=1);

namespace Weather\Fetcher\Parser;

use DateTime;
use RuntimeException;
use Weather\Parser;
use Weather\Prediction;
use Weather\Temperature;
use function array_map;
use function array_shift;
use function explode;

class CsvParser implements Parser
{
    /** @inheritDoc */
    public function parse(string $content, array $allPredictions): array
    {
        if ($content === '') {
            throw new RuntimeException('No json content to read from.');
        }

        $csv = $this->getCsvContent($content);

        $location = $date = '';

        foreach ($csv as $prediction) {
            if ($prediction[0] !== '') {
                $location = $prediction[1];
                $date = $prediction[2];
            }

            $dateTime = new DateTime($date . ' ' . $prediction[3]);
            $allPredictions[$dateTime->format(self::DATE_FORMAT)][] = new Prediction(
                $location,
                $dateTime,
                Temperature::createFromCelsius((float) ltrim((string) $prediction[4], '0'))
            );
        }

        return $allPredictions;
    }

    /** @return array<int, array<string>> */
    private function getCsvContent(string $content): array
    {
        $lines = explode(PHP_EOL, $content);
        //remove first and last lines
        array_shift($lines);
        array_pop($lines);

        return array_map('str_getcsv', $lines);
    }
}
