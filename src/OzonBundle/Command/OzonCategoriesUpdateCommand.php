<?php
namespace Savosik\OzonBundle\Command;

use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Savosik\OzonBundle\Helpers\SettingsHelper;


class OzonCategoriesUpdateCommand extends AbstractCommand{

    protected function configure()
    {
        $this
            ->setName("ozon:categories:update")
            ->setDescription('For updating dictionary objects inside pimcore that collect ozon data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $test = new SettingsHelper();
        echo $test->getByKey('ozon_seller_id');

        // dump
        $this->dump("Isn't that awesome?");

        // add newlines through flags
        $this->dump("Dump #2");

        // only dump in verbose mode
        $this->dumpVerbose("Dump verbose");

        // Output as white text on red background.
        $this->writeError('oh noes!');

        // Output as green text.
        $this->writeInfo('info');

        // Output as blue text.
        $this->writeComment('comment');

        // Output as yellow text.
        $this->writeQuestion('question');

        return 0;
    }
}