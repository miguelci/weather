<?php
declare(strict_types=1);

namespace Weather\Fetcher;

use Weather\Prediction;

interface PredictionGenerator
{
    /** @return array<int, array<int, Prediction[]>> */
    public static function generatePredictionsFromPrediction(Prediction $prediction): array;
}
