<?php
declare(strict_types=1);

namespace Weather\Fetcher;

use RuntimeException;
use Weather\Fetcher;
use Weather\Parser;

abstract class PartnerPredictionFetcher implements Fetcher
{
    private const DATA_SAMPLE = 'to_be_defined';

    protected Parser $parser;

    /** @inheritDoc */
    public function fetch(array $allPredictions): array
    {
        if (static::DATA_SAMPLE === 'to_be_defined') {
            throw new RuntimeException('A data sample needs to be defined');
        }

        /** @var string $content */
        $content = file_get_contents(static::DATA_SAMPLE);

        return $this->parser->parse($content, $allPredictions);
    }
}
