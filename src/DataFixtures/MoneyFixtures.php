<?php

namespace App\DataFixtures;

use App\Entity\Money\Currency;
use Doctrine\Persistence\ObjectManager;
use JsonException;


class MoneyFixtures extends AbstractFixtures
{
    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $this->exec($manager, $this);
    }

    /**
     * @param ObjectManager $manager
     * @throws JsonException
     */
    protected function loadDataCurrency(ObjectManager $manager): void
    {
        $fixturesFileDir = __DIR__ . '/JsonData/Money';

        foreach ($this->getFixturesData($fixturesFileDir, 'currency.json') as $currency) {
            $currencyEntity = new Currency();
            $currencyEntity->setName($currency['name']);
            $currencyEntity->setCode($currency['code']);
            $currencyEntity->setSymbol($currency['symbol']);

            $manager->persist($currencyEntity);
            $manager->flush();
        }
    }
}
