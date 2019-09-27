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

        $xFactor = pow($myStartNode->getX() - $myEndNode->getX(), 2);
        $yFactor = pow($myStartNode->getY() - $myEndNode->getY(), 2);

        $euclideanDistance = sqrt($xFactor + $yFactor);

        if ($euclideanDistance == 0 || $myStartNode->getF() != $myEndNode->getF())
            $euclideanDistance += 0.001;

        return $euclideanDistance;
    }
}
