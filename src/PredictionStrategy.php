<?php
declare(strict_types=1);

namespace Weather;

interface PredictionStrategy
{
    /**
     * @param  array<int|string, array<Prediction>> $predictions
     * @return Prediction[]
     */
    public function predict(array $predictions): array;
}
