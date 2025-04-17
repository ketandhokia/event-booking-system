<?php

namespace App\Controller;

use App\Entity\EventBooking;
use App\Manager\AttendeeManager;
use App\Manager\EventBookingManager;
use App\Manager\EventManager;
use App\Validator\EventBookingPayloadValidator;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/event-booking', name: 'api_')]
final class EventBookingController extends AbstractController
{
    /**
     * @var EventBookingManager
     */
    private EventBookingManager $manager;

    /**
     * @var EventManager
     */
    private EventManager $eventManager;

    /**
     * @var AttendeeManager
     */
    private AttendeeManager $attendeeManager;

    /**
     * EventController constructor.
     *
     * @param EventBookingManager $eventBookingManager
     * @param EventManager $eventManager
     * @param AttendeeManager $attendeeManager
     */
    public function __construct(
        EventBookingManager $eventBookingManager,
        EventManager $eventManager,
        AttendeeManager $attendeeManager)
    {
        $this->manager = $eventBookingManager;
        $this->eventManager = $eventManager;
        $this->attendeeManager = $attendeeManager;
    }

    #[Route(name: 'app_event_booking', methods: ['POST'])]
    #[FOSRest\RequestParam(name: 'event_id', description: 'Event ID', nullable: false)]
    #[FOSRest\RequestParam(name: 'attendee_ids', requirements: "\d+", description: 'Attendees details', map: true, nullable: false)]
    #[OA\Response(
        response: 200,
        description: 'Book an event',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: EventBooking::class, groups: ['booking', 'user', 'event', 'venue', 'attendee']))
        )
    )]
    #[OA\Tag(name: 'Booking')]
    #[Security(name: 'Bearer')]
    public function index(ParamFetcher $paramFetcher): Response
    {
        $params = $paramFetcher->all();
        $validator = new EventBookingPayloadValidator($params);
        if (!$validator->validate()) {
            return $this->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!$event = $this->eventManager->getFind($params['event_id'])) {
            return $this->json([
                'message' => 'Event not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        // check if attendees exists
        $attendees = [];
        foreach ($params['attendee_ids'] as $attendeeId) {
            if (!$attendee = $this->attendeeManager->getFind($attendeeId)) {
                return $this->json([
                    'message' => 'Attendee not found.',
                ], Response::HTTP_NOT_FOUND);
            }

            // check if attendee is already booked
            if ($this->manager->checkIfBookingExists($event, $attendee)) {
                return $this->json([
                    'message' => sprintf('Booking already exists for attendee ID %d.', $attendee->getId()),
                ], Response::HTTP_CONFLICT);
            }
            $attendees[] = $attendee;
        }

        $numOfTickets = count($attendees);
        // check if enough seats are available
        if ($event->getAvailableSeats() < $numOfTickets) {
            return $this->json([
                'message' => 'Not enough seats available.',
            ], Response::HTTP_CONFLICT);
        }

        $booking = $this->manager->createBookingForAttendees($this->getUser(), $event, $attendees);

        // update available seats
        $this->eventManager->updateAvailableSeats($event, $numOfTickets);

        return $this->json(
            [
                'message' => 'Booking created successfully.',
                'booking' => $booking,
            ],
            Response::HTTP_CREATED,
            [],
            ['groups' => ['booking', 'user', 'event', 'venue', 'attendee']]
        );
    }
}
