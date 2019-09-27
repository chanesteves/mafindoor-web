<?php

namespace App\AStar\Graph;

class Link
{
    private $source;
    private $destination;
    private $distance;

    public function __construct(MNode $source, MNode $destination, $distance)
    {
        $this->source = $source;
        $this->destination = $destination;
        $this->distance = $distance;
    }

    /**
     * @return MNode
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return MNode
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @return float
     */
    public function getDistance()
    {
        return $this->distance;
    }
}
