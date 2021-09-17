<?php

namespace Savosik\OzonBundle\Command;

use Pimcore\Console\AbstractCommand;
use Savosik\OzonBundle\Processors\AttributesProcessor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Savosik\OzonBundle\Helpers\SettingsHelper;
use Savosik\OzonBundle\Ozon\OzonDataProvider;

use Savosik\OzonBundle\Processors\CategoriesProcessor;


class OzonAttributesUpdateCommand extends AbstractCommand
{

    protected function configure()
    {
        $this
            ->setName("ozon:attributes:update")
            ->setDescription('For updating attribute objects inside pimcore that collect ozon data');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $settings_helper = new SettingsHelper();
        $settings['ozon_api_key'] = $settings_helper->getByKey('ozon_api_key');
        $settings['ozon_token'] = $settings_helper->getByKey('ozon_token');
        $settings['ozon_categories_pimcore_start_path'] = $settings_helper->getByKey('ozon_categories_pimcore_start_path');
        $settings['ozon_parent_categories'] = explode(PHP_EOL, $settings_helper->getByKey('ozon_parent_categories'));


        $ozon_data_provider = new OzonDataProvider();
        $ozon_data_provider->setApiKey($settings['ozon_api_key']);
        $ozon_data_provider->setToken($settings['ozon_token']);


        $attributes_processor = new AttributesProcessor();


        $categories_processor = new CategoriesProcessor();
        $pimcore_categories = $categories_processor->getCategories($settings['ozon_categories_pimcore_start_path']);

        foreach ($pimcore_categories as $pimcore_category){

            //get ozon attributes by category id
            $ozon_attributes  = $ozon_data_provider->getAttributesByCategory($pimcore_category['ozon_category_id']);

            $attributes_processor->unPublishInPath($pimcore_category['full_path']);
            $attributes_processor->insertUpdate($ozon_attributes, $pimcore_category['full_path']);

        }

        return 0;
    }
}