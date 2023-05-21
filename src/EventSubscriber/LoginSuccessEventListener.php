<?php
declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSuccessEventListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents():array
    {
        return [LoginSuccessEvent::class => '__invoke'];
    }

    public function __invoke(LoginSuccessEvent $event): void
    {
        $request = $event->getRequest();
        $user = $event->getUser();
        $roles = $event->getAuthenticatedToken()->getRoleNames();

        dump($roles);

//        dd(  $session = $request->getSession()->getId());
    }
}