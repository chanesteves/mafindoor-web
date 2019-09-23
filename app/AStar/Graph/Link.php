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

        $filteredDistance = filter_var($distance, FILTER_VALIDATE_FLOAT);

        if ($filteredDistance === false || $filteredDistance < 0) {
            throw new \InvalidArgumentException('Invalid distance: ' . print_r($distance, true));
        }

        $this->distance = $filteredDistance;
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
