<?php
declare(strict_types=1);

namespace Weather;

interface Parser
{
    public const DATE_FORMAT = 'YmdHi';

    /**
     * @param string $content
     * @param array<int|string, array<Prediction>> $allPredictions
     * @return array<int|string, array<Prediction>>
     */
    public function parse(string $content, array $allPredictions): array;
}
