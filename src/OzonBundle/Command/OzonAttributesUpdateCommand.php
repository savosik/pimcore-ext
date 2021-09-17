<?php

namespace Savosik\OzonBundle\Command;

use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Savosik\OzonBundle\Pimcore\OzonAttributesProcessor;



class OzonAttributesUpdateCommand extends AbstractCommand
{

    protected function configure()
    {
        $this
            ->setName("ozon:attributes:update")
            ->setDescription('For updating attributes objects inside pimcore that collect ozon attributes');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $attibute_processor = new OzonAttributesProcessor();
        $attibute_processor->addOzonAttributes();

        return 0;
    }
}