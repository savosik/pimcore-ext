<?php
namespace Savosik\OzonBundle\Command;

use phpseclib3\Math\BigInteger\Engines\PHP;
use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


use Pimcore\Model\DataObject;


class OzonCategoriesUpdateCommand extends AbstractCommand{


    protected function configure()
    {
        $this
            ->setName("ozon:categories:update")
            ->setDescription('For updating dictionary objects inside pimcore that collect OZON data');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entries = new DataObject\Settings\Listing();
        $entries->addConditionParam("setting = ?", "ozon_seller_id");

        var_dump($entries);

        return 0;
    }
}