<?php

namespace App\Controller;

use App\Entity\Attendee;
use App\Manager\AttendeeManager;
use App\Validator\AttendeePayloadValidator;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/attendee', name: 'api_')]
final class AttendeeController extends AbstractController
{
    /**
     * @var AttendeeManager
     */
    private AttendeeManager $manager;

    /**
     * AttendeeController constructor.
     *
     * @param AttendeeManager $attendeeManager
     */
    public function __construct(AttendeeManager $attendeeManager)
    {
        $this->manager = $attendeeManager;
    }

    #[Route(name: 'app_attendee_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List of attendees',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Attendee::class, groups: ['attendee']))
        )
    )]
    #[OA\Tag(name: 'Attendee')]
    #[Security(name: 'Bearer')]
    public function index(): Response
    {
        $attendees = $this->manager->getFindAll();

        return $this->json($attendees, Response::HTTP_OK, [], [
            'groups' => ['attendee'],
        ]);
    }

    #[Route(name: 'app_attendee_create', methods: ['POST'])]
    #[FOSRest\RequestParam(name: 'name', description: 'Name of attendee', nullable: false)]
    #[FOSRest\RequestParam(name: 'email', description: 'Email of attendee', nullable: false)]
    #[FOSRest\RequestParam(name: 'phone', description: 'Phone of attendee', nullable: false)]
    #[OA\Response(
        response: 201,
        description: 'Create attendee',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Attendee::class, groups: ['attendee']))
        )
    )]
    #[OA\Tag(name: 'Attendee')]
    #[Security(name: 'Bearer')]
    public function create(ParamFetcherInterface $paramFetcher): JsonResponse
    {
        $params = $paramFetcher->all();
        $validator = new AttendeePayloadValidator($params);
        if (!$validator->validate()) {
            return $this->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        // check if attendee already exists
        if ($attendee = $this->manager->getFindOneBy(['email' => $params['email']])) {
            return $this->json($attendee, Response::HTTP_OK, [], [
                'groups' => ['attendee'],
            ]);
        } else {
            $attendee = $this->manager->create($params);
            return $this->json($attendee, Response::HTTP_CREATED, [], [
                'groups' => ['attendee'],
            ]);
        }
    }

    #[Route('/{id}', name: 'app_attendee_update', methods: ['PUT'])]
    #[FOSRest\RequestParam(name: 'name', description: 'Name of attendee', nullable: true)]
    #[FOSRest\RequestParam(name: 'email', description: 'Email of attendee', nullable: true)]
    #[FOSRest\RequestParam(name: 'phone', description: 'Phone of attendee', nullable: true)]
    #[OA\Response(
        response: 200,
        description: 'Update attendee',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Attendee::class, groups: ['attendee']))
        )
    )]
    #[OA\Tag(name: 'Attendee')]
    #[Security(name: 'Bearer')]
    public function update(int $id, ParamFetcherInterface $paramFetcher)
    {
        $params = $paramFetcher->all();
        $validator = new AttendeePayloadValidator($params);
        if (!$validator->validate()) {
            return $this->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!$attendee = $this->manager->getFind($id)) {
            return $this->json([
                'message' => 'Attendee not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $attendee = $this->manager->update($attendee, $params);

        return $this->json($attendee, Response::HTTP_OK, [], [
            'groups' => ['attendee'],
        ]);
    }


}
