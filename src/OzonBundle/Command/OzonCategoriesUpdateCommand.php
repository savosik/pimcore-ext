<?php
use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Pimcore\Model\DataObject;

use Savosik\OzonBundle\Service\Settings;

class OzonCategoriesUpdateCommand extends AbstractCommand{


    protected function configure()
    {
        $this
            ->setName("ozon:categories:update")
            ->setDescription('For updating dictionary objects inside pimcore that collect OZON data');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $settings = new Settings();
        echo $settings->getValue('ozon_seller_id');

        return 0;
    }
}