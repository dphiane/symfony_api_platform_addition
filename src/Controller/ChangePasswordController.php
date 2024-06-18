<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ChangePasswordController extends AbstractController
{
    #[Route('/api/change_password', name: 'change_password', methods: ['POST'])]
    public function changePassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher,UserRepository $userRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['currentPassword']) && isset($data['newPassword']) && isset($data['email'])) {
            $user = $userRepository->findOneBy(['email' => $data['email']]);

            if (!$user) {
                return $this->json(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
            }

            if (!$passwordHasher->isPasswordValid($user, $data['currentPassword'])) {
                return $this->json(['error' => 'L\'ancien mot de passe est incorrect'], Response::HTTP_UNAUTHORIZED);
            }

            $hashedPassword = $passwordHasher->hashPassword($user, $data['newPassword']);
            $user->setPassword($hashedPassword);
            $entityManager->flush();

            return $this->json(['success' => 'Mot de passe changé avec succès']);
        }

        return $this->json(['error' =>$data], Response::HTTP_BAD_REQUEST);
    }
}
