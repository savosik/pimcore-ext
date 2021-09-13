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


        $items = new DataObject\AppSettings\Listing();
        $items->setOrderKey("RAND()", false);

        foreach ($items as $item) {
            echo $item . "<br />"; // output the path of the object
        }

        return 0;
    }
}