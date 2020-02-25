<?php
declare(strict_types=1);

namespace Weather\Fetcher\Parser;

use DateTime;
use RuntimeException;
use Weather\Parser;
use Weather\Prediction;
use Weather\Temperature;
use function ltrim;
use function simplexml_load_string;

class XmlParser implements Parser
{
    /** @inheritDoc */
    public function parse(string $content, array $allPredictions): array
    {
        if ($content === '') {
            throw new RuntimeException('No json content to read from.');
        }

        $content = simplexml_load_string($content);
        $location = (string) $content->city;
        $date = (string) $content->date;

        foreach ($content->prediction as $prediction) {
            $dateTime = new DateTime($date . ' ' . (string) $prediction->time);
            $allPredictions[$dateTime->format(self::DATE_FORMAT)][] = new Prediction(
                $location,
                $dateTime,
                Temperature::createFromCelsius((float) ltrim((string) $prediction->value, '0'))
            );
        }

        return $allPredictions;
    }
}
