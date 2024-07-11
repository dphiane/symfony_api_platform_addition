<?php

namespace App\EventSubscriber;

use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\User\UserInterface;
use CoopTilleuls\ForgotPasswordBundle\Event\CreateTokenEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use CoopTilleuls\ForgotPasswordBundle\Event\UpdatePasswordEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class ForgotPasswordEventSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private MailerInterface $mailer,
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            CreateTokenEvent::class => 'onCreateToken',
            UpdatePasswordEvent::class => 'onUpdatePassword',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest() || !str_starts_with($event->getRequest()->get('_route'), 'coop_tilleuls_forgot_password')) {
            return;
        }

        // User should not be authenticated on forgot password
        $token = $this->tokenStorage->getToken();
        if (null !== $token && $token->getUser() instanceof UserInterface) {
            throw new AccessDeniedHttpException('Vous ne pouvez pas être authentifié lors d\'une demande d\'oubli de mot de passe.');
        }
    }

    public function onCreateToken(CreateTokenEvent $event): void
    {
        $passwordToken = $event->getPasswordToken();
        $user = $passwordToken->getUser();

        $email = (new TemplatedEmail())
            ->from('contact@addition-dphiane.fr')
            ->to($user->getEmail())
            ->subject('Votre demande de réinitialisation de mot de passe')
            ->htmlTemplate('./emails\templates\resetPasswordRequest.html.twig')
            ->context([
                'resetToken' => $passwordToken,
            ]);

        $this->mailer->send($email);
    }

    public function onUpdatePassword(UpdatePasswordEvent $event): JsonResponse
    {
        $passwordToken = $event->getPasswordToken();
        $user = $this->userRepository->findOneBy(['email' => $passwordToken->getUser()->getEmail()]);

        if (!$user) {
            throw new \Exception('User not found.');
        }

        // Hash the new password
        $hashedPassword = password_hash($event->getPassword(), PASSWORD_DEFAULT);

        // Update user's password with hashed password
        $user->setPassword($hashedPassword);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Votre mot de passe à bien été réinitialisé'], Response::HTTP_OK);
    }
}
