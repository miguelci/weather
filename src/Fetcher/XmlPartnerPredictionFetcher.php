<?php
declare(strict_types=1);

namespace Weather\Fetcher;

use Weather\Fetcher\Parser\XmlParser;

final class XmlPartnerPredictionFetcher extends PartnerPredictionFetcher
{
    protected const DATA_SAMPLE = __DIR__ . '/samples/temps.xml';

    public function __construct(XmlParser $parser)
    {
        $this->parser = $parser;
    }
}
