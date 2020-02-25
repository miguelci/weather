<?php

namespace Weather\Tests;

use PHPUnit\Framework\TestCase;
use Weather\Temperature;

class TemperatureTest extends TestCase
{
    public function testCelsiusScale(): void
    {
        self::assertSame(10.0, Temperature::createFromCelsius(10)->getValue());
        self::assertSame(10.5, Temperature::createFromCelsius(10.5)->getValue());
        self::assertSame(-10.5, Temperature::createFromCelsius(-10.5)->getValue());
    }

    public function testFahrenheitScaleWillReturnCelsius(): void
    {
        self::assertSame(-6.67, Temperature::createFromFahrenheit(20)->getValue());
        self::assertSame(37.78, Temperature::createFromFahrenheit(100)->getValue());
    }

    public function testDefaultCelsiusScaleCanBeConvertedToFahrenheit(): void
    {
        $temperature = Temperature::createFromCelsius(20);
        $fahrenheit = $temperature->fromCelsiusToFahrenheit();
        self::assertSame(68.0, $fahrenheit->getValue());
    }
}
