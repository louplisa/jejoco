<?php
declare(strict_types=1);

namespace App\Serializer;

use CalendarBundle\Entity\Event;


interface SerializerInterface
{
    /**
     * @param Event[] $events
     *
     * @return string json
     */
    public function serialize(array $events): string;
}