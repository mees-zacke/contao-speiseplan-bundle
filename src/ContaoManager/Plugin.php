<?php

namespace mees-zacke\SpeiseplanBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\CoreBundle\ContaoCoreBundle;
use mees-zacke\SpeiseplanBundle\SpeiseplanBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(SpeiseplanBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}