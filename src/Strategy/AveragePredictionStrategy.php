<?php

declare(strict_types=1);

namespace Weather\Strategy;

use Weather\Prediction;
use Weather\PredictionStrategy;
use Weather\Temperature;
use function var_dump;

final class AveragePredictionStrategy implements PredictionStrategy
{
    /**
     * @param array<int, array<int, Prediction>> $predictions
     * @return Prediction[]
     */
    public function predict(array $predictions): array
    {
        $applyTemperaturePredictionStrategy = static function (array $temperatures) {
            return array_sum($temperatures) / count($temperatures);
        };

        $newPredictions = [];

        foreach ($predictions as $key => $predictionsForTheHour) {
            if (count($predictionsForTheHour) === 1) {
                $newPredictions[] = $predictionsForTheHour[0];
                continue;
            }

            $newTemperature = $applyTemperaturePredictionStrategy(
                array_map(
                    static function (Prediction $prediction) {
                        return $prediction->getTemperature()->getValue();
                    },
                    $predictionsForTheHour
                )
            );
            /** @var Prediction $clone */
            $clone = clone $predictionsForTheHour[0];
            $newPredictions[] = new Prediction(
                $clone->getLocation(),
                $clone->getDateTime(),
                Temperature::createFromCelsius($newTemperature)
            );
        }

        return $newPredictions;
    }
}
