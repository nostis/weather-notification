<?php

namespace App\Service\Mail;

use App\Entity\Customer;
use App\Entity\Weather;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;

class MailFactory
{
    private MailerInterface $mailer;
    private ParameterBagInterface $params;
    private TranslatorInterface $translator;

    public function __construct(MailerInterface $mailer, ParameterBagInterface $parameterBag, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->params = $parameterBag;
        $this->translator = $translator;
    }

    public function createWeatherForecastMail(Weather $weather, Customer $customer): Email
    {
        return (new TemplatedEmail())
            ->from($this->params->get('mailer_from'))
            ->to($customer->getEmail())
            ->subject($this->translator->trans('mail.subject', [], 'messages', $customer->getLanguage()->getCode()))
            ->htmlTemplate('emails/weather_forecast.html.twig')
            ->context(['customer' => $customer, 'weather' => $weather])
            ;
    }
}