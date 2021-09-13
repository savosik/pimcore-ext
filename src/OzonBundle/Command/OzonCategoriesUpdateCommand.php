<?php
namespace Savosik\OzonBundle\Command;

use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


use \Pimcore\Model\DataObject;


class OzonCategoriesUpdateCommand extends AbstractCommand{

    protected function configure()
    {
        $this
            ->setName("ozon:categories:update")
            ->setDescription('For updating dictionary objects inside pimcore that collect OZON data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {


        $myObject = DataObject\AppSettings::getById(1979);

        $key = $myObject->getSettings_key();

        var_dump($key);

        return 0;
    }
}