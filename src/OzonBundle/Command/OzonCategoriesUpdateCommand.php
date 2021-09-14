<?php
namespace Savosik\OzonBundle\Command;

use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Pimcore\Model\DataObject;


class OzonCategoriesUpdateCommand extends AbstractCommand{

    private $container;

    public function __construct(ContainerBuilder $container)
    {
        parent::__construct();
        $this->container = $container;
    }


    protected function configure()
    {
        $this
            ->setName("ozon:categories:update")
            ->setDescription('For updating dictionary objects inside pimcore that collect OZON data');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {

        echo ($this->container->get('secret'));

        return 0;
    }
}