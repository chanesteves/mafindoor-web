<?php

namespace App\AStar\Graph;

class SequencePrinter
{
    private $graph;
    private $sequence;

    /**
     * @param Graph $graph
     * @param MNode[] $sequence
     */
    public function __construct(Graph $graph, array $sequence)
    {
        $this->graph = $graph;
        $this->sequence = $sequence;
    }

    public function printSequence()
    {
        $nodesAsString = array();

        foreach ($this->sequence as $node) {
            $nodesAsString[] = $this->getNodeAsString($node);
        }

        if (!empty($nodesAsString)) {
            echo implode(' => ', $nodesAsString);
            echo "\n";
        }

        echo 'Total cost: ' . $this->getTotalDistance();
    }

    public function getSequence () {
        return $this->sequence;
    }

    private function getNodeAsString(MNode $node)
    {
        return "({$node->getX()}, {$node->getY()})";
    }

    private function getTotalDistance()
    {
        if (count($this->sequence) < 2) {
            return 0;
        }

        $totalDistance = 0;

        $sequence = $this->sequence;

        $previousNode = array_shift($sequence);
        foreach ($sequence as $node) {
            $totalDistance += $this->graph->getLink($previousNode, $node)->getDistance();

            $previousNode = $node;
        }

        return $totalDistance;
    }
}
