<?php

namespace Weather\Tests\Fetcher;

use DateTime;
use PHPStan\Testing\TestCase;
use Weather\Fetcher\RandomPredictionGenerator;
use Weather\Prediction;
use Weather\Temperature;

class RandomPredictionGeneratorTest extends TestCase
{
    public function testGeneratePredictionsFromFirstPredictionDayReturnsCorrectly(): void
    {
        $prediction = new Prediction(
            'Amsterdam',
            new DateTime('2018-01-01 00:00'),
            Temperature::createFromCelsius(5)
        );

        $predictions = RandomPredictionGenerator::generatePredictionsFromPrediction($prediction);
        self::assertCount(9, $predictions);

        $day = (int) $prediction->getDateTime()->format('j');
        foreach ($predictions as $predictionsForTheDay) {
            /** @var Prediction[] $predictionsForTheDay */
            self::assertCount(11, $predictionsForTheDay);
            self::assertSame(++$day, (int) $predictionsForTheDay[0]->getDateTime()->format('j'));

            $hour = 0;
            foreach ($predictionsForTheDay as $predictionForTheDay) {
                self::assertSame($hour++, (int) $predictionForTheDay->getDateTime()->format('G'));
            }
        }
    }
}
