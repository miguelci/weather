<?php

namespace Weather\Tests;

use DateTime;
use PHPStan\Testing\TestCase;
use Weather\Prediction;
use Weather\Temperature;

class PredictionTest extends TestCase
{
    private const DEFAULT_LOCATION = 'Amsterdam';
    private const DEFAULT_DATE_TIME = '2008-01-01 00:00';
    private const DEFAULT_TEMPERATURE = 5.0;

    public function testCanBeCorrectlyInitialized(): void
    {
        $prediction = self::stub();
        self::assertInstanceOf(Prediction::class, $prediction);
        self::assertSame(self::DEFAULT_LOCATION, $prediction->getLocation());
        self::assertSame(self::DEFAULT_DATE_TIME, $prediction->getDateTime()->format('Y-m-d H:i'));
        self::assertSame(self::DEFAULT_TEMPERATURE, $prediction->getTemperature()->getValue());
    }

    public static function stub(
        string $dateTime = self::DEFAULT_DATE_TIME,
        float $temperatureValue = self::DEFAULT_TEMPERATURE
    ): Prediction {
        return new Prediction(
            self::DEFAULT_LOCATION,
            new DateTime($dateTime),
            Temperature::createFromCelsius($temperatureValue)
        );
    }
}
