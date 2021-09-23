<?php

namespace Savosik\OzonBundle\Command;

use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Savosik\OzonBundle\Helpers\SettingsHelper;
use Savosik\OzonBundle\Ozon\OzonDataProvider;

use Savosik\OzonBundle\Processors\DictionariesProcessor;


class OzonDictionariesUpdateCommand extends AbstractCommand
{

    protected function configure()
    {
        $this
            ->setName("ozon:dictionaries:update")
            ->setDescription('Add elements to ONE dictionary, or one page of dictionary by one step.');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        for ($i=0; $i<100; $i++){
            $this->make_update_step();
            sleep(1);
        }
        return 0;
    }


    private function make_update_step(){
        //load settings
        $settings_helper = new SettingsHelper();
        $settings['ozon_client_id'] = $settings_helper->getByKey('ozon_client_id');
        $settings['ozon_api_key'] = $settings_helper->getByKey('ozon_api_key');
        $settings['ozon_categories_pimcore_start_path'] = $settings_helper->getByKey('ozon_categories_pimcore_start_path');
        $settings['ozon_parent_categories'] = explode(PHP_EOL, $settings_helper->getByKey('ozon_parent_categories'));
        $settings['ozon_classification_store'] = $settings_helper->getByKey('ozon_classification_store');
        $settings['ozon_dictionaries_pimcore_start_path'] = $settings_helper->getByKey('ozon_dictionaries_pimcore_start_path');

        //load ozon data provider
        $ozon_data_provider = new OzonDataProvider();
        $ozon_data_provider->setClientId($settings['ozon_client_id']);
        $ozon_data_provider->setApiKey($settings['ozon_api_key']);

        //load dictionaries processor
        $dictionaries_processor = new DictionariesProcessor();

        //get first not completely processed dictionary
        $pimcore_dictionary = $dictionaries_processor->getDictionaryToProcess($settings['ozon_dictionaries_pimcore_start_path']);


        //get elements for dictionary
        if(!empty($pimcore_dictionary['category_id']) && !empty($pimcore_dictionary['attribute_id'])){
            $ozon_elements_set = $ozon_data_provider->getDictionaryElements($pimcore_dictionary['category_id'], $pimcore_dictionary['attribute_id'], $pimcore_dictionary['last_value_id']);
        }

        //add elements to dictionary
        $dictionaries_processor->addElementsToDictionary($pimcore_dictionary['dictionary_path'], $ozon_elements_set);
    }
}