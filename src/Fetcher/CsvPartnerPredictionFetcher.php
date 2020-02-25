<?php
declare(strict_types=1);

namespace Weather\Fetcher;

use Weather\Fetcher\Parser\CsvParser;

final class CsvPartnerPredictionFetcher extends PartnerPredictionFetcher
{
    protected const DATA_SAMPLE = __DIR__ . '/samples/temps.csv';

    public function __construct(CsvParser $parser)
    {
        $this->parser = $parser;
    }
}
