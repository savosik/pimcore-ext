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
            ->setDescription('For updating dictionary objects inside pimcore that collect ozon data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $entries = new DataObject\Settings\Listing();

        foreach ($entries as $entry) {
            echo  $entry->getName();
        }


        return 0;
    }
}