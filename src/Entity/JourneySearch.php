<?php
declare(strict_types=1);

namespace App\Entity;

class JourneySearch
{
    private string $origin;
    private string $destination;
    private string $departureTime;

    public function getOrigin(): string
    {
        return $this->origin;
    }

    //needs to be turned to geo data or station id in landing page form
    public function setOrigin(string $origin): JourneySearch
    {
        $this->origin = $origin;
        return $this;
    }

    public function getDestination(): string
    {
        return $this->destination;
    }

    //needs to be turned to geo data or station id in landing page form
    public function setDestination(string $destination): JourneySearch
    {
        $this->destination = $destination;
        return $this;
    }

    public function getDepartureTime(): string
    {
        return $this->departureTime;
    }

    public function setDepartureTime(string $departureTime): JourneySearch
    {
        $this->departureTime = $departureTime;
        return $this;
    }
}
