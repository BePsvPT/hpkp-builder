<?php

namespace Bepsvpt\HPKPBuilder;

class HPKPBuilder
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var bool
     */
    protected $needsCompile = true;

    /**
     * @var string
     */
    protected $compiled = '';

    /**
     * @var bool
     */
    protected $reportOnly = false;

    /**
     * Constructor.
     *
     * @param array $preloaded
     */
    public function __construct(array $preloaded = [])
    {
        $this->config = $preloaded;
    }

    /**
     * @return array
     */
    public function getHeaderArray(): array
    {
        if ($this->needsCompile) {
            $this->compile();
        }

        $key = $this->reportOnly
            ? 'Public-Key-Pins-Report-Only'
            : 'Public-Key-Pins';

        return [$key => $this->compiled];
    }

    /**
     * Compile the HPKP header, store it in the protected $compiled property.
     *
     * @return string
     */
    protected function compile(): string
    {
        $this->reportOnly = $this->config['report-only'];

        $headers = [];

        foreach ($this->config['hashes'] as $hash) {
            $headers[] = sprintf(
                'pin-%s="%s"; ',
                $hash['algo'],
                $hash['hash']
            );
        }

        $headers[] = sprintf('max-age=%d', $this->config['max-age']);

        if ($this->config['include-sub-domains']) {
            $headers[] = '; includeSubDomains';
        }

        if (! empty($this->config['report-uri'])) {
            $headers[] = sprintf(
                '; report-uri="%s"',
                $this->config['report-uri']
            );
        }

        $this->needsCompile = false;

        return $this->compiled = implode('', $headers);
    }
}
