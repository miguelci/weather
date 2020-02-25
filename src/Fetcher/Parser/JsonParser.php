<?php
declare(strict_types=1);

namespace Weather\Fetcher\Parser;

use DateTime;
use RuntimeException;
use Weather\Parser;
use Weather\Prediction;
use Weather\Temperature;
use function json_decode;

class JsonParser implements Parser
{
    /** @inheritDoc */
    public function parse(string $content, array $allPredictions): array
    {
        if ($content === '') {
            throw new RuntimeException('No json content to read from.');
        }

        $content = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        $location = $content['predictions']['city'];
        $date = $content['predictions']['date'];

        foreach ($content['predictions']['prediction'] as $prediction) {
            $dateTime = new DateTime($date . ' ' . $prediction['time']);
            $allPredictions[$dateTime->format(self::DATE_FORMAT)][] = new Prediction(
                $location,
                $dateTime,
                Temperature::createFromFahrenheit((float) $prediction['value'])
            );
        }

        return $allPredictions;
    }
}
