<?php


namespace App\Serializer;

use CalendarBundle\Entity\Event;


class Serializer implements SerializerInterface
{

    /**
     * @param Event[] $events
     *
     * @return string json
     */
    public function serialize(array $events): string
    {
        $result = [];
        foreach ($events as $event) {
            $result[] = $event->toArray();
        }
        return json_encode($result);
    }
}