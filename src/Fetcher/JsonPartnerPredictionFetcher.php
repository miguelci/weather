<?php
declare(strict_types=1);

namespace Weather\Fetcher;

use Weather\Fetcher\Parser\JsonParser;

final class JsonPartnerPredictionFetcher extends PartnerPredictionFetcher
{
    protected const DATA_SAMPLE = __DIR__ . '/samples/temps.json';

    public function __construct(JsonParser $parser)
    {
        $this->parser = $parser;
    }
}
