<?php

declare(strict_types=1);

namespace MeesZacke\ContaoSpeiseplanBundle\Tests;

use MeesZacke\ContaoSpeiseplanBundle\ContaoSpeiseplanBundle;
use PHPUnit\Framework\TestCase;

class ContaoSpeiseplanBundleTest extends TestCase
{
    public function testCanBeInstantiated(): void
    {
        $bundle = new ContaoSpeiseplanBundle();

        $this->assertInstanceOf('MeesZacke\ContaoSpeiseplanBundle\ContaoSpeiseplanBundle', $bundle);
    }
}
