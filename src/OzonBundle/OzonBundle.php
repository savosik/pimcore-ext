<?php
namespace Savosik\OzonBundle;
use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\Traits\PackageVersionTrait;


class OzonBundle extends AbstractPimcoreBundle {
    use PackageVersionTrait;

    const PACKAGE_NAME = 'savosik/OzonBundle';

    protected function getComposerPackageName(): string
    {
        return self::PACKAGE_NAME;
    }

}