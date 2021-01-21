<?php

namespace Demo\App;

use Demo\App\Catalog\Domain\SizeScale;
use Doctrine\ORM\EntityManagerInterface;
use Krak\Admin\Templates\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class PlaygroundCommand extends Command
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
        parent::__construct();
    }

    protected function configure() {
        $this->setName('playground');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
//        Table::Td('');
//        $sizeScale = $this->em->find(SizeScale::class, 1);
//        dump($sizeScale);
        $sizeScale = new SizeScale('Test 2', [1, 2, 3]);
        $this->em->persist($sizeScale);
        $this->em->flush();
        return 0;
    }
}
