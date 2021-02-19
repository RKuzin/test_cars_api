<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DropMileageCommand extends Command
{

    /**
     * @var string
     */
    protected static $defaultName = 'app:drop-mileage';

    /**
     * Percent of mileage reduce
     * @var integer
     */
    const REDUCE_PROCENT = 30;

    /**
     * Minimum mileage value for reducing
     * @var integer
     */
    const MILEAGE_LIMIT = 150000;


    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "app/console")
            ->setName('app:drop-mileage')

            // the short description shown while running "php bin/console list"
            ->setDescription('Reducing mileage by ' . self::REDUCE_PROCENT . '% for all cars with mileage over '
                . (int)self::MILEAGE_LIMIT . ' miles')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to reduce mileage by ' . self::REDUCE_PROCENT . '% for all cars with 
            mileage over ' . (int)self::MILEAGE_LIMIT . ' miles...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $multiplier = (100 - self::REDUCE_PROCENT) / 100;
        if ((int)self::MILEAGE_LIMIT > 0 && $multiplier > 0 && $multiplier <= 1) {
            $this
                ->entityManager
                ->createQuery('
            UPDATE App\Entity\Vehicle v
            SET v.mileage = v.mileage * ' . $multiplier . '
            WHERE v.mileage > ' . (int)self::MILEAGE_LIMIT . '')
                ->execute();
            $output->writeln('Mileage reducing is done!');
            return Command::SUCCESS;
        }
        $output->writeln('Incorrect percent of reduce or mileage limit!');
        return Command::FAILURE;
    }
}