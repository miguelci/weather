<?php
declare(strict_types=1);

namespace Weather\Fetcher;

use Closure;
use DateTime;
use Weather\Prediction;
use Weather\Temperature;

final class RandomPredictionGenerator implements PredictionGenerator
{
    private const DAYS_TO_GENERATE = 9;
    private const HOURS_TO_GENERATE = 10;

    /** @inheritDoc */
    public static function generatePredictionsFromPrediction(Prediction $prediction): array
    {
        $location = $prediction->getLocation();
        $date = $prediction->getDateTime()
            ->format('Y-m-d H:i');

        $generate10Hours = static function (string $date, string $location) {
            return array_map(function (int $hour) use ($date, $location) {
                return new Prediction(
                    $location,
                    (new DateTime($date))->modify('+' . $hour . ' hour'),
                    Temperature::createFromCelsius(rand(0, 15))
                );
            }, range(0, self::HOURS_TO_GENERATE));
        };

        $generate9Days = static function (string $date, string $location, Closure $generate10Hours) {
            return array_map(function (int $day) use ($date, $location, $generate10Hours) {
                return $generate10Hours(
                    (new DateTime($date))
                        ->modify('+' . $day . ' day')
                        ->format('Y-m-d H:i'),
                    $location
                );
            }, range(1, self::DAYS_TO_GENERATE));
        };

        return $generate9Days($date, $location, $generate10Hours);
    }
}
