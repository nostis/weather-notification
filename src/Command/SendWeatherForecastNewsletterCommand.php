<?php

namespace App\Command;

use App\Entity\Customer;
use App\Service\Notification\NotificationSender;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SendWeatherForecastNewsletterCommand extends Command //@todo
{
    protected static $defaultName = 'app:send:forecast:newsletter';

    private EntityManagerInterface $entityManager;
    private NotificationSender $notificationSender;

    public function __construct(EntityManagerInterface $entityManager, NotificationSender $notificationSender, string $name = null)
    {
        parent::__construct($name);

        $this->entityManager = $entityManager;
        $this->notificationSender = $notificationSender;
    }

    protected function configure()
    {
        $this
            ->setDescription('Send weather forecast newsletter to customers')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /**
         * @var Collection $customerToSendNotification
         */
        $customerToSendNotification = $this->entityManager->getRepository(Customer::class)
            ->findBy(['notificationHour' => new \DateTime()]);

        /**
         * @var Customer $customer
         */
        foreach($customerToSendNotification as $customer) {
            $this->notificationSender->sendNotification($customer);
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
