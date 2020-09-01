<?php

namespace App\Command;

use App\Entity\CityCoordinates;
use App\Service\CityCoordinatesCsvConverter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

class LoadCityCoordinatesFromCsvCommand extends Command
{
    protected static $defaultName = 'app:load:city-coordinates-csv';

    private CityCoordinatesCsvConverter $cityCoordinatesCsvConverter;
    private KernelInterface $kernel;
    private EntityManagerInterface $entityManager;

    public function __construct(CityCoordinatesCsvConverter $cityCoordinatesCsvConverter, KernelInterface $kernel, EntityManagerInterface  $entityManager, string $name = null)
    {
        $this->cityCoordinatesCsvConverter = $cityCoordinatesCsvConverter;
        $this->kernel = $kernel;
        $this->entityManager = $entityManager;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Loads city-coordinates to db from csv file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $csvData = $this->getCsvData($this->getCsvFileLocation());

        if($this->isCityCoordinatesEmpty()) {
            $collectionToSave = $this->cityCoordinatesCsvConverter->getDataConvertedToCityCoordinates($csvData);

            /**
             * @var CityCoordinates $cityCoordinates
             */
            foreach($collectionToSave as $cityCoordinates) {
                $this->entityManager->persist($cityCoordinates);
            }

            $this->entityManager->flush();

            $io->success('Successfully loaded and saved city-coordinates to db');

            return Command::SUCCESS;
        }

        $io->success('There are already city-coordinates records in db');

        return Command::FAILURE;
    }

    private function getCsvData(string $location): string
    {
        return file_get_contents($location);
    }

    private function getCsvFileLocation(): string
    {
        return $this->kernel->getProjectDir() . '/src/Resource/Csv/worldcities.csv';
    }

    private function isCityCoordinatesEmpty(): bool
    {
        $result = $this->entityManager->getRepository(CityCoordinates::class)
            ->createQueryBuilder('c')
            ->select('count(c.id)')
            ->getQuery()
            ->getSingleScalarResult()
            ;

        return (int) $result == 0;
    }
}
