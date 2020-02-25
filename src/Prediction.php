<?php
declare(strict_types=1);

namespace Weather;

use DateTime;
use function json_encode;

final class Prediction
{
    private string $location;
    private DateTime $dateTime;
    private Temperature $temperature;

    public function __construct(string $location, DateTime $dateTime, Temperature $temperature)
    {
        $this->location = $location;
        $this->dateTime = $dateTime;
        $this->temperature = $temperature;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    public function getTemperature(): Temperature
    {
        return $this->temperature;
    }

    public function __toString(): string
    {
        $prediction = json_encode([
            'location' => $this->location,
            'dateTime' => $this->dateTime->format('Y-m-d H:i:s'),
            'temperature' => (string) $this->temperature,
        ]);
        if ($prediction === false) {
            return '';
        }

        return $prediction;
    }

    /** @param array<string> $prediction */
    public static function createFromDatabase(array $prediction, string $scale = Temperature::DEFAULT_SCALE): self
    {
        $temperature = Temperature::createFromCelsius((float) $prediction['value']);
        if ($scale !== Temperature::DEFAULT_SCALE) {
            $temperature = Temperature::createFromFahrenheit((float) $prediction['value']);
        }

        return new self(
            $prediction['location'],
            new DateTime($prediction['temperature_date']),
            $temperature
        );
    }
}
