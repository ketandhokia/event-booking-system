<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\UserManager;
use App\Validator\RegisterPayloadValidator;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
final class RegistrationController extends AbstractController
{
    /**
     * @var UserManager
     */
    private UserManager $manager;

    /**
     * RegistrationController constructor.
     *
     * @param UserManager $manager
     */
    public function __construct(UserManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    #[Route('/register', name: 'app_registration', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Create venue',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class, groups: ['user']))
        )
    )]
    #[OA\Tag(name: 'Register')]
    #[Security(name: 'Bearer')]
    public function index(Request $request): JsonResponse
    {
        $params = json_decode($request->getContent(), true);
        $validator = new RegisterPayloadValidator($params);
        if (!$validator->validate()) {
            return $this->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        // Check if the email already exists
        if ($this->manager->getFindBy(['email' => $params['email']])) {
            return $this->json([
                'message' => 'Email already exists',
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->manager->create($params);

        return $this->json([
            'message' => 'User successfully registered',
            'user' => $user,
        ], Response::HTTP_CREATED, [], [
            'groups' => ['user'],
        ]);
    }
}
