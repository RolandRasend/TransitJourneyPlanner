<?php
declare(strict_types=1);

namespace App\Entity;

use DateTime;

class Leg
{
    private string $arrivalPlaceName;
    private string $departurePlaceName;
    private DateTime $departure;
    private DateTime $arrival;
    private string $track;
    private string $mode;
    private int $duration;

    public function getArrivalPlaceName(): string
    {
        return $this->arrivalPlaceName;
    }

    public function setArrivalPlaceName(string $arrivalPlaceName): Leg
    {
        $this->arrivalPlaceName = $arrivalPlaceName;
        return $this;
    }

    public function getDeparturePlaceName(): string
    {
        return $this->departurePlaceName;
    }

    public function setDeparturePlaceName(string $departurePlaceName): Leg
    {
        $this->departurePlaceName = $departurePlaceName;
        return $this;
    }

    public function getDeparture(): DateTime
    {
        return $this->departure;
    }

    public function setDeparture(DateTime $departure): Leg
    {
        $this->departure = $departure;
        return $this;
    }

    public function getArrival(): DateTime
    {
        return $this->arrival;
    }

    public function setArrival(DateTime $arrival): Leg
    {
        $this->arrival = $arrival;
        return $this;
    }

    public function getTrack(): string
    {
        return $this->track;
    }

    public function setTrack(string $track): Leg
    {
        $this->track = $track;
        return $this;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function setMode(string $mode): Leg
    {
        $this->mode = $mode;
        return $this;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): Leg
    {
        $this->duration = $duration;
        return $this;
    }

}
