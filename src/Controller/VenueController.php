<?php

namespace App\Controller;

use App\Entity\Venue;
use App\Manager\VenueManager;
use App\Validator\VenuePayloadValidator;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/venue', name: 'api_')]
final class VenueController extends AbstractController
{
    /**
     * @var VenueManager
     */
    private VenueManager $manager;

    /**
     * VenueController constructor.
     *
     * @param VenueManager $venueManager
     */
    public function __construct(VenueManager $venueManager)
    {
        $this->manager = $venueManager;
    }

    #[Route(name: 'app_venue_create', methods: ['POST'])]
    #[FOSRest\RequestParam(name: 'name', description: 'Name of the venue', nullable: false)]
    #[FOSRest\RequestParam(name: 'iso_code', description: 'Country code of the venue', nullable: false)]
    #[FOSRest\RequestParam(name: 'capacity', description: 'Capacity of the venue', nullable: false)]
    #[FOSRest\RequestParam(name: 'description', description: 'Description of the venue', nullable: true)]
    #[OA\Response(
        response: 200,
        description: 'Create venue',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Venue::class, groups: ['venue']))
        )
    )]
    #[OA\Tag(name: 'Venue')]
    #[Security(name: 'Bearer')]
    public function create(ParamFetcherInterface $paramFetcher): JsonResponse
    {
        $params = $paramFetcher->all();
        $validator = new VenuePayloadValidator($params);
        if (!$validator->validate()) {
            return $this->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        $venue = $this->manager->create($params);

        return $this->json([
            'message' => 'Venue added successfully.',
            'venue' => $venue,
        ], Response::HTTP_CREATED, [], [
            'groups' => ['venue'],
        ]);
    }

    #[Route(name: 'app_venue_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get all venues',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Venue::class, groups: ['venue']))
        )
    )]
    #[OA\Tag(name: 'Venue')]
    #[Security(name: 'Bearer')]
    public function get(): JsonResponse
    {
        return $this->json($this->manager->getAll(), Response::HTTP_OK, [], [
            'groups' => ['venue'],
        ]);
    }
}
