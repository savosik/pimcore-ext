<?php

namespace Savosik\OzonBundle\Command;

use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Savosik\OzonBundle\Helpers\SettingsHelper;
use Savosik\OzonBundle\Ozon\OzonDataProvider;

use Savosik\OzonBundle\Processors\CategoriesProcessor;


class OzonCategoriesUpdateCommand extends AbstractCommand
{

    protected function configure()
    {
        $this
            ->setName("ozon:categories:update")
            ->setDescription('For updating categories tree inside pimcore that collect ozon data');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $settings_helper = new SettingsHelper();
        $settings['ozon_client_id'] = $settings_helper->getByKey('ozon_client_id');
        $settings['ozon_api_key'] = $settings_helper->getByKey('ozon_api_key');
        $settings['ozon_categories_pimcore_start_path'] = $settings_helper->getByKey('ozon_categories_pimcore_start_path');
        $settings['ozon_parent_categories'] = explode(PHP_EOL, $settings_helper->getByKey('ozon_parent_categories'));


        $ozon_data_provider = new OzonDataProvider();
        $ozon_data_provider->setClientId($settings['ozon_client_id']);
        $ozon_data_provider->setApiKey($settings['ozon_api_key']);


        $categories_processor = new CategoriesProcessor();
        $categories_processor->lockAllCategories($settings['ozon_categories_pimcore_start_path']);


        foreach ($settings['ozon_parent_categories'] as $ozon_parent_category_id){

            //get ozon categories by parent category id it makes request
            $ozon_categories_tree = $ozon_data_provider->getCategories($ozon_parent_category_id);

            //create_update and set Unlocked
            $categories_processor->insertUpdate($ozon_categories_tree, $settings['ozon_categories_pimcore_start_path']);
        }

        return 0;
    }
}