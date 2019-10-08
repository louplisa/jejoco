<?php


namespace App\EventListener;

use CalendarBundle\Entity\Event;
use App\Repository\AssoEventRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use CalendarBundle\Event\CalendarEvent;

class CalendarListener
{
    private $assoEventRepository;
    private $router;

    public function __construct(AssoEventRepository $assoEventRepository, UrlGeneratorInterface $router)
    {
        $this->assoEventRepository = $assoEventRepository;
        $this->router = $router;
    }

    public function loadAction(CalendarEvent $calendar): void
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();


        $assoEvents = $this->assoEventRepository
            ->createQueryBuilder('assoevent')
            ->where('assoevent.beginAt BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult();

        foreach ($assoEvents as $assoEvent) {
            $eventNew = new Event(
                $assoEvent->getName(),
                $assoEvent->getBeginAt(),
                $assoEvent->getEndAt()
            );

            $eventNew->setOptions(['backgroundColor' => '#0C84E4',
                'borderColor' => '#0C84E4',
            ]);


                $eventNew->addOption(
                    'url',
                    $this->router->generate('event_show', [
                        'id' => $assoEvent->getId(),
                    ])
                );

            $calendar->addEvent($eventNew);
        }
    }
}