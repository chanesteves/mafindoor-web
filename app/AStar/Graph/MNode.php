<?php

namespace App\AStar\Graph;

use JMGQ\AStar\AbstractNode;
use JMGQ\AStar\Node;

class MNode extends AbstractNode
{
    private $x;
    private $y;

    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @param Node $node
     * @return MNode
     */
    public static function fromNode(Node $node)
    {
        $coordinates = explode('x', $node->getID());

        if (count($coordinates) !== 2) {
            throw new \InvalidArgumentException('Invalid node: ' . print_r($node, true));
        }

        $x = $coordinates[0];
        $y = $coordinates[1];

        return new MNode($x, $y);
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }

    /**
     * {@inheritdoc}
     */
    public function getID()
    {
        return $this->x . 'x' . $this->y;
    }
}
