<?php

declare(strict_types=1);

namespace MeesZacke\ContaoSpeiseplanBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\SkeletonBundle\ContaoSkeletonBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoSpeiseplanBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
