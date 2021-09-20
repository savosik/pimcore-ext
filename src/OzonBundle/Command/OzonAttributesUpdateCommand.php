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
        //load settings
        $settings_helper = new SettingsHelper();
        $settings['ozon_client_id'] = $settings_helper->getByKey('ozon_client_id');
        $settings['ozon_api_key'] = $settings_helper->getByKey('ozon_api_key');
        $settings['ozon_categories_pimcore_start_path'] = $settings_helper->getByKey('ozon_categories_pimcore_start_path');
        $settings['ozon_parent_categories'] = explode(PHP_EOL, $settings_helper->getByKey('ozon_parent_categories'));
        $settings['ozon_classification_store'] = $settings_helper->getByKey('ozon_classification_store');


        //load Ozon data proxy
        $ozon_data_provider = new OzonDataProvider();
        $ozon_data_provider->setClientId($settings['ozon_client_id']);
        $ozon_data_provider->setApiKey($settings['ozon_api_key']);


        // create Store for Ozon attributes
        $attributes_processor = new AttributesProcessor();
        $store_id = $attributes_processor->createClassificationStore($settings['ozon_classification_store']);


        //fetch ozon categories from specific three
        $categories_processor = new CategoriesProcessor();
        $pimcore_categories = $categories_processor->getCategories($settings['ozon_categories_pimcore_start_path']);


        //iterate with created (in pimcore) categories
        foreach ($pimcore_categories as $pimcore_category){

            //get ozon attributes by category id
            $ozon_attributes  = $ozon_data_provider->getAttributesByCategoriesIds(array($pimcore_category['ozon_category_id']));
            $ozon_attributes  = $ozon_attributes[0]['attributes'];

            //create_collection with same name like category and full path in description
            $full_path = $pimcore_category['full_path'];
            $full_path_arr = explode("/", $full_path);
            $collection_name = end($full_path_arr);
            $attributes_processor->createCollection($store_id, $collection_name, $full_path);

        }

        return 0;
    }
}