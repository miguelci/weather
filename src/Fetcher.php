<?php
declare(strict_types=1);

namespace Weather;

interface Fetcher
{
    /**
     * @param array<int|string, array<Prediction>> $allPredictions
     * @return array<int|string, array<Prediction>>
     */
    public function fetch(array $allPredictions): array;
}
