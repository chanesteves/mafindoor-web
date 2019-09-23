<?php

namespace App\AStar\Graph;

class Graph
{
    private $links = array();

    /**
     * @param Link[] $links
     */
    public function __construct(array $links = array())
    {
        foreach ($links as $link) {
            $this->addLink($link);
        }
    }

    public function addLink(Link $link)
    {
        $linkID = $this->getLinkID($link->getSource(), $link->getDestination());

        $this->links[$linkID] = $link;
    }

    /**
     * @param MNode $source
     * @param MNode $destination
     * @return Link | null
     */
    public function getLink(MNode $source, MNode $destination)
    {
        if ($this->hasLink($source, $destination)) {
            $linkID = $this->getLinkID($source, $destination);

            return $this->links[$linkID];
        }

        return null;
    }

    /**
     * @param MNode $source
     * @param MNode $destination
     * @return bool
     */
    public function hasLink(MNode $source, MNode $destination)
    {
        $linkID = $this->getLinkID($source, $destination);

        return isset($this->links[$linkID]);
    }

    /**
     * @param MNode $node
     * @return MNode[]
     */
    public function getDirectSuccessors(MNode $node)
    {
        $successors = array();

        foreach ($this->links as $link) {
            if ($node->getID() === $link->getSource()->getID()) {
                $successors[] = $link->getDestination();
            }
        }

        return $successors;
    }

    private function getLinkID(MNode $source, MNode $destination)
    {
        return $source->getID() . '|' . $destination->getID();
    }
}
