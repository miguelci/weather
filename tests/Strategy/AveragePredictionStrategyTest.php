<?php

namespace Weather\Tests\Strategy;

use DateTime;
use PHPStan\Testing\TestCase;
use Weather\Parser;
use Weather\PredictionStrategy;
use Weather\Strategy\AveragePredictionStrategy;
use Weather\Tests\PredictionTest;

class AveragePredictionStrategyTest extends TestCase
{
    private PredictionStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new AveragePredictionStrategy();
    }

    public function testCanPredictAnAverageTemperatureForTheSameDate(): void
    {
        $date = '2018-01-01 00:00';
        $firstPrediction = PredictionTest::stub($date, 5);
        $secondPrediction = PredictionTest::stub($date, 10);

        $predictionsWithStrategy = $this->strategy->predict([
            (new DateTime($date))->format(Parser::DATE_FORMAT) => [$firstPrediction, $secondPrediction],
        ]);

        self::assertNotEmpty($predictionsWithStrategy);
        self::assertCount(1, $predictionsWithStrategy);

        self::assertSame(
            (5 + 10) / 2,
            $predictionsWithStrategy[0]
                ->getTemperature()->getValue()
        );
    }

    public function testCanReturnAnAverageTemperatureForMultipleDays(): void
    {
        $firstDate = '2018-01-01 00:00';
        $secondDate = '2018-01-02 00:00';
        $thirdDate = '2018-01-03 00:00';
        $firstKey = (new DateTime($firstDate))->format(Parser::DATE_FORMAT);
        $secondKey = (new DateTime($secondDate))->format(Parser::DATE_FORMAT);
        $thirdKey = (new DateTime($thirdDate))->format(Parser::DATE_FORMAT);

        $predictionsWithStrategy = $this->strategy->predict([
            $firstKey => [PredictionTest::stub($firstDate, 5)],
            $secondKey => [
                PredictionTest::stub($secondDate, 5),
                PredictionTest::stub($secondDate, 10),
            ],
            $thirdKey => [
                PredictionTest::stub($thirdDate, 1),
                PredictionTest::stub($thirdDate, 4),
                PredictionTest::stub($thirdDate, 7),
                PredictionTest::stub($thirdDate, 2),
            ],
        ]);

        self::assertCount(3, $predictionsWithStrategy);
        self::assertSame(5.0, $predictionsWithStrategy[0]->getTemperature()->getValue());
        self::assertSame(7.5, $predictionsWithStrategy[1]->getTemperature()->getValue());
        self::assertSame(3.5, $predictionsWithStrategy[2]->getTemperature()->getValue());
    }
}
