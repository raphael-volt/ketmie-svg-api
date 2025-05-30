<?php

namespace App\Controller;

use App\DTO\SetupDTO;
use App\Entity\ApiUser;
use App\Repository\ApiUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
final class SetupController extends AbstractController
{
    public function __construct(
        private readonly ApiUserRepository           $apiUserRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly SerializerInterface         $serializer)
    {

    }

    /**
     * Enable user creation without jwt authentication using config/secrets/INSTALL_PASSWORD
     * @param SetupDTO $setupDTO
     * @return Response
     */
    #[Route('/setup', name: 'app_setup', methods: ['POST'])]
    public function index(#[MapRequestPayload] SetupDTO $setupDTO): Response
    {
        $secret = $this->getParameter('install_password');
        if ($secret !== $setupDTO->secret) {
            return new Response("INVALID SECRET", 401);
        }
        $repository = $this->apiUserRepository;
        $apiUser = $repository->findOneBy(['email' => $setupDTO->email]);
        if ($apiUser instanceof ApiUser) {
            return new Response("USER EXISTS", 401);
        }
        $apiUser = new ApiUser();
        $apiUser->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $apiUser->setEmail($setupDTO->email);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $apiUser,
            $setupDTO->password
        );
        $apiUser->setPassword($hashedPassword);
        $apiUser->eraseCredentials();
        $repository->save($apiUser, true);
        $jsonContent = $this->serializer->serialize(
            $apiUser, 'json',
            [AbstractNormalizer::ATTRIBUTES => ['email', 'id', 'roles']]);
        return JsonResponse::fromJsonString($jsonContent);
    }
}
