<?php
declare(strict_types=1);

namespace Weather;

use function round;

final class Temperature
{
    public const DEFAULT_SCALE = 'celsius';
    private float $value;
    private string $scale;

    private function __construct(float $value, string $scale = self::DEFAULT_SCALE)
    {
        $this->value = $value;
        $this->scale = $scale;
    }

    public function getValue(): float
    {
        return round($this->value, 2);
    }

    public function getScale(): string
    {
        if ($this->scale !== self::DEFAULT_SCALE) {
            return ' ºF';
        }

        return ' ºC';
    }

    public static function createFromCelsius(float $value): self
    {
        return new self($value);
    }

    public static function createFromFahrenheit(float $value): self
    {
        return new self(($value - 32) / 1.8, 'fahrenheit');
    }

    public function fromCelsiusToFahrenheit(): self
    {
        $clone = clone $this;

        return new self($clone->value * 1.8 + 32);
    }

    public function __toString(): string
    {
        return (string) $this->getValue() . $this->getScale();
    }
}
