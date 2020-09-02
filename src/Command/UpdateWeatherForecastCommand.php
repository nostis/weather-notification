<?php

namespace App\Command;

use App\Service\Weather\WeatherUpdater;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateWeatherForecastCommand extends Command
{
    protected static $defaultName = 'app:update:weather-forecast-all';

    private WeatherUpdater $weatherUpdater;
    private const UPDATE_HOUR = '10:05';

    public function __construct(WeatherUpdater $weatherUpdater, string $name = null)
    {
        $this->weatherUpdater = $weatherUpdater;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Update all weather forecasts')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $dateTimeNow = new \DateTime();

        if($dateTimeNow->format('H:i') == self::UPDATE_HOUR) {
            $this->weatherUpdater->updateAllWeather();

            $io->success('Successfully updated forecasts');

            return Command::SUCCESS;
        }

        $io->warning('Wrong time for update!');

        return Command::FAILURE;
    }
}
