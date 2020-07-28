<?php

namespace App\DataProvider;

class CardDataProvider implements CardDataProviderInterface
{
    private string $url;

    public function __construct(string $sourceUrl)
    {
        $this->url = $sourceUrl;
    }

    /**
     * @param string $binCode
     * @return array
     * @throws \Exception
     */
    public function getInfoByBinCode(string $binCode): string
    {
        $creditCardInfo = file_get_contents($this->url . '/' . $binCode);

        if ($creditCardInfo === false) {
            throw new \Exception('Card info was not found in the provided resource: '
                . $this->url
                . "\n"
                . 'Please make sure the resource is available, bin code is correct and you have not overcome the limits.'
            );
        }

        return $creditCardInfo;
    }
}