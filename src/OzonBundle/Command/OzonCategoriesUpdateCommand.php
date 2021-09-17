<?php

namespace Savosik\OzonBundle\Command;

use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Savosik\OzonBundle\Helpers\SettingsHelper;
use Savosik\OzonBundle\Ozon\OzonDataProvider;

use Savosik\OzonBundle\Pimcore\PimcoreDataProvider;
use Savosik\OzonBundle\Processors\CategoriesProcessor;


class OzonCategoriesUpdateCommand extends AbstractCommand
{

    protected function configure()
    {
        $this
            ->setName("ozon:categories:update")
            ->setDescription('For updating dictionary objects inside pimcore that collect ozon data');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $settings = new SettingsHelper();

        $ozon_client_id = $settings->getByKey('ozon_client_id');
        $ozon_token = $settings->getByKey('ozon_api_key');
        $ozon_parent_categories = $settings->getByKey('ozon_parent_categories');
        $ozon_parent_categories = explode(PHP_EOL, $ozon_parent_categories);
        $ozon_categories_start_path = $settings->getByKey('ozon_categories_pimcore_start_path');

        $ozon_data_provider = new OzonDataProvider();
        $categories_processor = new CategoriesProcessor();



        return 0;
    }
}