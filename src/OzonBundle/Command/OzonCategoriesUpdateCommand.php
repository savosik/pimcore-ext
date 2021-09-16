<?php
namespace Savosik\OzonBundle\Command;

use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Savosik\OzonBundle\Helpers\SettingsHelper;
use Savosik\OzonBundle\Ozon\OzonDataProvider;


class OzonCategoriesUpdateCommand extends AbstractCommand{

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

        var_dump($ozon_parent_categories);

        $ozon_parent_categories  = explode($ozon_parent_categories,"\n");

        $ozon_parent_category = $ozon_parent_categories[0];

        var_dump($ozon_parent_category);

        $ozon = new OzonDataProvider();
        $ozon->setClientId($ozon_client_id);
        $ozon->setToken($ozon_token);

        $categories = $ozon->get_categories($ozon_parent_category);

        var_dump($categories);


        return 0;
    }
}