<?php

namespace App\Controller;

use App\Entity\Event;
use App\Manager\EventManager;
use App\Manager\VenueManager;
use App\Validator\EventPayloadValidator;
use DateMalformedStringException;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/event', name: 'api_')]
final class EventController extends AbstractController
{
    /**
     * @var EventManager
     */
    private EventManager $manager;

    /**
     * @var VenueManager
     */
    private VenueManager $venueManager;

    /**
     * EventController constructor.
     *
     * @param EventManager $eventManager
     * @param VenueManager $venueManager
     */
    public function __construct(EventManager $eventManager, VenueManager $venueManager)
    {
        $this->manager = $eventManager;
        $this->venueManager = $venueManager;
    }

    #[Route(name: 'app_event_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List of events',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Event::class, groups: ['event']))
        )
    )]
    #[OA\Tag(name: 'Event')]
    #[Security(name: 'Bearer')]
    public function index(): JsonResponse
    {
        return $this->json($this->manager->getAll(), Response::HTTP_OK, [], [
            'groups' => ['event', 'user', 'venue'],
        ]);
    }

    /**
     * @throws DateMalformedStringException
     */
    #[Route(name: 'app_event_create', methods: ['POST'])]
    #[FOSRest\RequestParam(name: 'venue_id', description: 'Venue ID', nullable: false)]
    #[FOSRest\RequestParam(name: 'title', description: 'Title of event', nullable: false)]
    #[FOSRest\RequestParam(name: 'description', description: 'Description of event', nullable: false)]
    #[FOSRest\RequestParam(name: 'start_time', description: 'Start time of event', nullable: false)]
    #[FOSRest\RequestParam(name: 'end_time', description: 'End time of event', nullable: false)]
    #[FOSRest\RequestParam(name: 'total_seats', description: 'Total seats of event', nullable: false)]
    #[FOSRest\RequestParam(name: 'price', description: 'Price for the ticket', nullable: false)]
    #[OA\Response(
        response: 201,
        description: 'Create event',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Event::class, groups: ['event']))
        )
    )]
    #[OA\Tag(name: 'Event')]
    #[Security(name: 'Bearer')]
    public function create(ParamFetcherInterface $paramFetcher): JsonResponse
    {
        $params = $paramFetcher->all();
        $validator = new EventPayloadValidator(array_merge($params, ['action' => 'new']));
        if (!$validator->validate()) {
            return $this->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!$venue = $this->venueManager->getFind($params['venue_id'])) {
            return $this->json([
                'message' => 'Venue not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $event = $this->manager->create($venue, $params);

        return $this->json(
            [
                'message' => 'Event added successfully.',
                'event' => $event,
            ], Response::HTTP_CREATED, [], [
                'groups' => ['event', 'venue'],
        ]);
    }

    #[Route('/{id}', name: 'app_event_update', methods: ['PUT'])]
    #[FOSRest\RequestParam(name: 'title', description: 'Title of event', nullable: true)]
    #[FOSRest\RequestParam(name: 'description', description: 'Description of event', nullable: true)]
    #[FOSRest\RequestParam(name: 'start_time', description: 'Start time of event', nullable: true)]
    #[FOSRest\RequestParam(name: 'end_time', description: 'End time of event', nullable: true)]
    #[FOSRest\RequestParam(name: 'total_seats', description: 'Total seats of event', nullable: true)]
    #[FOSRest\RequestParam(name: 'price', description: 'Price for the ticket', nullable: true)]
    #[OA\Response(
        response: 200,
        description: 'Update event',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Event::class, groups: ['event']))
        )
    )]
    #[OA\Tag(name: 'Event')]
    #[Security(name: 'Bearer')]
    public function update(int $id, ParamFetcherInterface $paramFetcher): JsonResponse
    {
        $params = $paramFetcher->all();
        $validator = new EventPayloadValidator(array_merge($params, ['action' => 'update']));
        if (!$validator->validate()) {
            return $this->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!$event = $this->manager->getFind($id)) {
            return $this->json([
                'message' => 'Event not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $event = $this->manager->update($event, $params);

        return $this->json([
            'message' => 'Event updated successfully.',
            'event' => $event,
        ], Response::HTTP_OK, [], [
            'groups' => ['event', 'venue'],
        ]);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['DELETE'])]
    #[FOSRest\RequestParam(name: 'id', description: 'Event ID', nullable: false)]
    #[OA\Response(
        response: 200,
        description: 'Delete event',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Event::class, groups: ['event']))
        )
    )]
    #[OA\Tag(name: 'Event')]
    #[Security(name: 'Bearer')]
    public function remove(int $id): JsonResponse
    {
        if (!$event = $this->manager->getFind($id)) {
            return $this->json([
                'message' => 'Event not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $this->manager->remove($event);

        return $this->json([
            'message' => 'Event deleted successfully.',
        ]);
    }
}
