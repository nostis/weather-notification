<?php

namespace App\Command;

use App\Entity\CityCoordinates;
use App\Entity\Language;
use App\Entity\Model\CityCoordinatesLanguageModel;
use App\Service\Weather\WeatherUpdater;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateWeatherForecastInSpecificLocationLanguageCommand extends Command
{
    protected static $defaultName = 'app:update:weather-forecast-specific';

    private WeatherUpdater $weatherUpdater;
    private EntityManagerInterface $entityManager;

    public function __construct(WeatherUpdater $weatherUpdater, EntityManagerInterface $entityManager, string $name = null)
    {
        $this->weatherUpdater = $weatherUpdater;
        $this->entityManager = $entityManager;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Update now forecast for specific location and language')
            ->addArgument('city_coordinates_id', InputArgument::OPTIONAL, 'Select city')
            ->addArgument('language_id', InputArgument::OPTIONAL, 'Select language')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $languageId = $input->getArgument('language_id');
        $cityCoordinateId = $input->getArgument('city_coordinates_id');

        $cityCoordinatesLanguages = $this->weatherUpdater->getCitiesLanguageAssignedToCustomers();

        $languages = new ArrayCollection();
        $cityCoordinates = new ArrayCollection();

        /**
         * @var CityCoordinatesLanguageModel $cityCoordinatesLanguage
         */
        foreach($cityCoordinatesLanguages as $cityCoordinatesLanguage) {
            $languages[] = $cityCoordinatesLanguage->getLanguage();
            $cityCoordinates[] = $cityCoordinatesLanguage->getCityCoordinates();
        }

        if (empty($languageId)) {
            $helper = $this->getHelper('question');
            $question = new ChoiceQuestion(
                'Please select language',
                array_map(function($e) { return $e->getId() . ': ' . ' [language: ' . $e->getLanguage() . ', iso_code: '. $e->getCode() .']'; }, $languages->toArray()),
                null
            );
            $question->setErrorMessage('Language %s is invalid.');

            $language = $helper->ask($input, $output, $question);
            $output->writeln('You have selected: '.$language);

            $languageId = explode(':', $language)[0];
        }

        if (empty($cityCoordinateId)) {
            $helper = $this->getHelper('question');
            $question = new ChoiceQuestion(
                'Please select city',
                array_map(function($e) { return $e->getId() . ': ' . ' [city: ' . $e->getCityAscii() . ']'; }, $cityCoordinates->toArray()),
                null
            );
            $question->setErrorMessage('City %s is invalid.');

            $cityCoordinate = $helper->ask($input, $output, $question);
            $output->writeln('You have selected: ' . $cityCoordinate);

            $cityCoordinateId = explode(':', $cityCoordinate)[0];
        }

        $cityCoordinates = $this->entityManager->getRepository(CityCoordinates::class)->find($cityCoordinateId);
        $language = $this->entityManager->getRepository(Language::class)->find($languageId);

        if($cityCoordinates === null) {
            $io->error('Can\'t find this city');

            return Command::FAILURE;
        }

        if($language === null) {
            $io->error('Can\'t find this language');

            return Command::FAILURE;
        }

        $this->weatherUpdater->updateWeatherForSpecificLocation($cityCoordinates, $language);

        $io->success('Successfully updated weather for selected options');

        return Command::SUCCESS;
    }
}
