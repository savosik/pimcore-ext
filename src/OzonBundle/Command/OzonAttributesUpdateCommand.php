<?php

namespace Savosik\OzonBundle\Command;

use Pimcore\Console\AbstractCommand;
use Savosik\OzonBundle\Processors\AttributesProcessor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Savosik\OzonBundle\Helpers\SettingsHelper;
use Savosik\OzonBundle\Ozon\OzonDataProvider;

use Savosik\OzonBundle\Processors\CategoriesProcessor;
use Savosik\OzonBundle\Processors\DictionariesProcessor;


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


        //load Ozon data provider
        $ozon_data_provider = new OzonDataProvider();
        $ozon_data_provider->setClientId($settings['ozon_client_id']);
        $ozon_data_provider->setApiKey($settings['ozon_api_key']);


        // create Store for Ozon attributes
        $attributes_processor = new AttributesProcessor();
        $store_id = $attributes_processor->createClassificationStore($settings['ozon_classification_store']);


        //fetch created in pimcore categories from specific three
        $categories_processor = new CategoriesProcessor();
        $pimcore_categories = $categories_processor->getCategories($settings['ozon_categories_pimcore_start_path']);


        //iterate with created (in pimcore) categories
        foreach ($pimcore_categories as $pimcore_category) {

            $ozon_category_id = $pimcore_category['ozon_category_id']; //from folder user properties

            //get ozon attributes by category id
            $ozon_attributes = $ozon_data_provider->getAttributesByCategoriesIds(array($ozon_category_id));
            $ozon_attributes = $ozon_attributes[0]['attributes'];

            //create_collection with same name like category and full path in description
            $full_path = $pimcore_category['full_path'];
            $full_path_arr = explode("/", $full_path);
            $collection_name = end($full_path_arr);
            $collection_id = $attributes_processor->createCollection($store_id, $collection_name, $full_path);

            // fetch loaded from request ozon attributes
            foreach ($ozon_attributes as $ozon_attribute) {
                $ozon_attribute_id = $ozon_attribute['id'];
                $ozon_group_id = $ozon_attribute['group_id'];
                $ozon_group_name = $ozon_attribute['group_name'];
                $ozon_group_description = $ozon_group_id;

                if ($ozon_group_id == 0) {
                    $ozon_group_name = $collection_name;
                }

                // create classification store group
                $created_group_id = $attributes_processor->createGroup($store_id, $ozon_group_name, $ozon_group_description);

                // add group to collection
                $attributes_processor->addGroupToCollection($collection_id, $created_group_id);


                $dictionary_elements = [];
                $dictionary_id = $ozon_attribute['dictionary_id'];

                if ($dictionary_id !== 0) {

                    $dictionary = $ozon_data_provider->getDictionaryElements($ozon_category_id, $ozon_attribute_id);
                    if ($dictionary['has_next'] == true) {
                        // todo: create folders for dictionaries
                    } else {
                        $dictionary_elements = $dictionary['elements'];
                    }
                }

                $prop_id = $attributes_processor->createPropertyByOzonAttribute($store_id, $ozon_attribute, $ozon_category_id, $dictionary_elements);
                $attributes_processor->addPropertyToGroup($created_group_id, $prop_id);
            }

        }

        return 0;
    }
}