<?php


namespace App\Controller;

use App\Serializer\SerializerInterface;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use DateTime;
use Exception;
use function is_array;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController implements SerializerInterface
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        SerializerInterface $serializer
    )
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->serializer = $serializer;
    }

    /*
     * @Route("/show")
     */
    public function showCalendar()
    {
        return $this->render('calendar/calendar.html.twig');
    }

    /*
     * @Route("/")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function loadAction(Request $request): Response
    {
        $start = new DateTime($request->get('start'));
        $end = new DateTime($request->get('end'));
        $filters = $request->get('filters', '{}');
        $filters = is_array($filters) ? $filters : json_decode($filters, true);
        $event = $this->eventDispatcher->dispatch(
            CalendarEvents::SET_DATA,
            new CalendarEvent($start, $end, $filters)
        );
        $content = $this->serializer->serialize($event->getEvents());
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($content);
        $response->setStatusCode(empty($content) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK);
        return $response;
    }

    /**
     * @param Event[] $events
     *
     * @return string json
     */
    public function serialize(array $events): string
    {
        return null;
    }
}