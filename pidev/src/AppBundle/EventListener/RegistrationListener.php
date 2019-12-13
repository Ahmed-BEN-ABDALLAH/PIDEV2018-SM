<?php
/**
 * Created by PhpStorm.
 * User: saiid
 * Date: 10/02/2018
 * Time: 20:05
 */

namespace AppBundle\EventListener;


use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class RegistrationListener implements EventSubscriberInterface
{
    private $router;
    private $container;

    public function __construct(UrlGeneratorInterface $router,ContainerInterface  $container)
    {
        $this->router = $router;
        $this->container = $container;
    }
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => [
                ['onRegistrationSuccess', -10],
            ],
        ];
    }
    public function onRegistrationSuccess(FormEvent $event)
    {
        $event->setResponse(new RedirectResponse($this->router->generate('homepage')));

    }

}