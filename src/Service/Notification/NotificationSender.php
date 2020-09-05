<?php

namespace App\Service\Notification;

use App\Entity\Customer;
use App\Entity\Weather;
use App\Service\Mail\MailFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Mailer\MailerInterface;

class NotificationSender
{
    private EntityManagerInterface $entityManager;
    private MailFactory $mailFactory;
    private MailerInterface $mailer;

    public function __construct(EntityManagerInterface $entityManager, MailFactory $mailFactory, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailFactory = $mailFactory;
        $this->mailer = $mailer;
    }

    public function sendNotification(Customer $customer)
    {
        $weather = $this->getWeatherForecastForCustomer($customer);

        $this->sendMailNotification($customer, $weather);
    }

    private function getWeatherForecastForCustomer(Customer $customer): Weather
    {
        $weather = $this->entityManager->getRepository(Weather::class)
            ->findOneBy([
                'cityCoordinates' => $customer->getCityCoordinates(),
                'language' => $customer->getLanguage(),
                'forecastForDate' => new \DateTime()
                ]);

        if($weather === null) {
            throw new HttpException('404', 'No weather find for this customer');
        }

        return $weather;
    }

    private function sendMailNotification(Customer $customer, Weather $weather)
    {
        $email = $this->mailFactory->createWeatherForecastMail($weather, $customer);

        $this->mailer->send($email);
    }
}