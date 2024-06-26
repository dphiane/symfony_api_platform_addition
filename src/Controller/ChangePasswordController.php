<?php

namespace App\Controller;

use App\Dto\ChangePasswordRequest;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ChangePasswordController extends AbstractController{

    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private  EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private  UserRepository $userRepository
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->getContent();
        $changePasswordRequest = $this->serializer->deserialize($data, ChangePasswordRequest::class, 'json');

        $errors = $this->validator->validate($changePasswordRequest);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = $this->userRepository->findOneBy(['email' => $changePasswordRequest->email]);

        if (!$user) {
            return $this->json(['error' => 'Utilisateur non trouvé'], JsonResponse::HTTP_NOT_FOUND);
        }

        if (!$this->passwordHasher->isPasswordValid($user, $changePasswordRequest->currentPassword)) {
            return $this->json(['error' => 'L\'ancien mot de passe est incorrect'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $hashedPassword = $this->passwordHasher->hashPassword($user, $changePasswordRequest->newPassword);
        $user->setPassword($hashedPassword);
        $this->entityManager->flush();

        return $this->json(['success' => 'Mot de passe changé avec succès']);
    }
}
