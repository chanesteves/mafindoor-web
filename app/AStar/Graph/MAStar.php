<?php

namespace App\AStar\Graph;

use JMGQ\AStar\AStar;
use JMGQ\AStar\Node;

class MAStar extends AStar
{
    private $graph;

    public function __construct(Graph $graph)
    {
        $this->graph = $graph;
    }

    /**
     * {@inheritdoc}
     */
    public function generateAdjacentNodes(Node $node)
    {
        $myNode = MNode::fromNode($node);

        return $this->graph->getDirectSuccessors($myNode);
    }

    /**
     * {@inheritdoc}
     */
    public function calculateRealCost(Node $node, Node $adjacent)
    {
        $myStartNode = MNode::fromNode($node);
        $myEndNode = MNode::fromNode($adjacent);

        if (!$this->graph->hasLink($myStartNode, $myEndNode)) {
            throw new \DomainException('The provided nodes are not linked');
        }

        return $this->graph->getLink($myStartNode, $myEndNode)->getDistance();
    }

    /**
     * {@inheritdoc}
     */
    public function calculateEstimatedCost(Node $start, Node $end)
    {
        $myStartNode = MNode::fromNode($start);
        $myEndNode = MNode::fromNode($end);

        $lat_from = deg2rad($myStartNode->getY());
        $lng_from = deg2rad($myStartNode->getX());
        $lat_to = deg2rad($myEndNode->getY());
        $lng_to = deg2rad($myEndNode->getX());

        $lat_delta = $lat_to - $lat_from;
        $lng_delta = $lng_to - $lng_from;

        $angle = 2 * asin(sqrt(pow(sin($lat_delta / 2), 2) +
            cos($lat_from) * cos($lat_to) * pow(sin($lng_delta / 2), 2)));
        
        return $angle * 6378137;
    }
}
