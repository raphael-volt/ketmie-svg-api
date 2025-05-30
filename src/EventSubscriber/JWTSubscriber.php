<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\com\JWTUser;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTEncodedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class JWTSubscriber implements EventSubscriberInterface
{
    private static $_responseToken = '';

    public function __construct(
        private JWTTokenManagerInterface $jwtManager)
    {
    }
    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $user = $event->getUser();
        $event->setData([
            'roles' => $user->getRoles(),
            'email' => $user->getUserIdentifier()
        ]);
    }
    /**
     * @param JWTEncodedEvent $event
     */
    public function onJwtEncoded(JWTEncodedEvent $event)
    {
        self::$_responseToken = $event->getJWTString();
    }
    public function onJWTAuthenticated(JWTAuthenticatedEvent $event){
        $payload = $event->getPayload();
        $expiration = new \DateTime('+2 month');
        $payload['exp'] = $expiration->getTimestamp();
        $user = JWTUser::createFromPayload($payload['username'], [
            'roles' => $payload['roles']
        ]);
        self::$_responseToken = $this->jwtManager->createFromPayload($user, $payload);
    }
    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => ['responseHandler', EventPriorities::PRE_RESPOND],
        ];
    }
    public function responseHandler(ResponseEvent $event): void {
        $token = self::$_responseToken;
        if($token)
            $event->getResponse()->headers->set('X-JWT', $token);
    }
}
