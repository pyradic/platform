<?php /** @noinspection PhpUnused */

namespace Pyro\Platform\Ui\TreeNode;

use Illuminate\Support\Traits\Macroable;

/**
 * @mixin \Pyro\Platform\Ui\TreeNode\NodeInterface
 */
trait NodeTrait
{
    use Macroable;

    /** @var mixed */
    private $value;

    /** @var \Pyro\Platform\Ui\TreeNode\NodeInterface */
    private $parent;

    /** @var NodeInterface[] */
    private $children = [];

    /** @var int */
    private $index;

    /** @var \Pyro\Platform\Ui\TreeNode\NodeInterface */
    private $root;

    /** @var string */
    protected $collectionClass = NodeCollection::class;

    public function setRoot(NodeInterface $root)
    {
        $this->root = $root;
        return $this;
    }

    public function getAllDescendants()
    {
        $descendants = $this->newCollection();
        foreach ($this->getChildren() as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->getAllDescendants());
        }
        return $descendants;
    }

    /**
     * @return \Pyro\Platform\Ui\TreeNode\NodeCollection|\Pyro\Platform\Ui\TreeNode\NodeInterface[]
     */
    public function newCollection(array $items = [])
    {
        return new $this->collectionClass($items);
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    public function hasValue()
    {
        return $this->value !== null;
    }

    public function hasChild(NodeInterface $child): bool
    {
        foreach ($this->children as $item) {
            if ($item === $child) {
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function addChild(NodeInterface $child)
    {
        if ($this->hasChild($child)) {
            return $this;
        }
        $child->setIndex(array_push($this->children, $child));
        $child->setParent($this);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setParent(?NodeInterface $parent = null)
    {
        if ($this->parent === $parent) {
            return $this;
        }
        if ($this->hasParent() && $this->getParent()->hasChild($this)) {
            $this->getParent()->removeChild($this);
        }
        $this->parent = $parent;
        if ($parent !== null) {
            $parent->addChild($this);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeChild(NodeInterface $child)
    {
        foreach ($this->children as $key => $myChild) {
            if ($child == $myChild) {
                unset($this->children[ $key ]);
            }
        }

        $this->children = array_values($this->children);

        $child->setParent(null);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAllChildren()
    {
        $this->setChildren([]);

        return $this;
    }

    /**
     * @return \Pyro\Platform\Ui\TreeNode\NodeCollection|\Pyro\Platform\Ui\TreeNode\NodeInterface[]
     */
    public function getChildren()
    {
        return $this->newCollection($this->children);
    }

    /**
     * @param array|\Pyro\Platform\Ui\TreeNode\NodeInterface[]|\Pyro\Platform\Ui\TreeNode\NodeCollection $children
     *
     * @return $this
     */
    public function setChildren($children)
    {
        if ($children instanceof NodeCollection) {
            $children = $children->all();
        }
        $this->removeParentFromChildren();
        $this->children = [];

        foreach ($children as $child) {
            $this->addChild($child);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function getAncestors()
    {
        $parents = [];
        $node    = $this;
        while ($parent = $node->getParent()) {
            array_unshift($parents, $parent);
            $node = $parent;
        }

        return $this->newCollection($parents);
    }

    /**
     * {@inheritDoc}
     */
    public function getAncestorsAndSelf()
    {
        return $this->newCollection(array_merge($this->getAncestors()->all(), [ $this ]));
    }

    /**
     * {@inheritdoc}
     */
    public function getNeighbors()
    {
        $neighbors = $this->getParent()->getChildren()->all();
        $current   = $this;

        // Uses array_values to reset indexes after filter.
        $items = array_values(
            array_filter(
                $neighbors,
                function ($item) use ($current) {
                    return $item != $current;
                }
            )
        );

        return $this->newCollection($items);
    }

    /**
     * {@inheritDoc}
     */
    public function getNeighborsAndSelf()
    {
        return $this->getParent()->getChildren();
    }

    /**
     * {@inheritDoc}
     */
    public function isLeaf(): bool
    {
        return count($this->children) === 0;
    }

    /**
     * @return bool
     */
    public function isRoot(): bool
    {
        return $this->getParent() === null;
    }

    /**
     * {@inheritDoc}
     */
    public function isChild(): bool
    {
        return $this->getParent() !== null;
    }

    /**
     * Find the root of the node
     *
     * @return NodeInterface
     */
    public function root()
    {
        $node = $this;

        while ($parent = $node->getParent()) {
            $node = $parent;
        }

        return $node;
    }

    /**
     * Return the distance from the current node to the root.
     *
     * Warning, can be expensive, since each descendant is visited
     *
     * @return int
     */
    public function getDepth(): int
    {
        if ($this->isRoot()) {
            return 0;
        }

        return $this->getParent()->getDepth() + 1;
    }

    /**
     * Return the height of the tree whose root is this node
     *
     * @return int
     */
    public function getHeight(): int
    {
        if ($this->isLeaf()) {
            return 0;
        }

        $heights = [];

        foreach ($this->getChildren() as $child) {
            $heights[] = $child->getHeight();
        }

        return max($heights) + 1;
    }

    /**
     * Return the number of nodes in a tree
     *
     * @return int
     */
    public function getSize(): int
    {
        $size = 1;
        foreach ($this->getChildren() as $child) {
            $size += $child->getSize();
        }

        return $size;
    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function accept(Visitor $visitor)
//    {
//        return $visitor->visit($this);
//    }

    public function removeParentFromChildren()
    {
        foreach ($this->getChildren() as $child) {
            $child->setParent(null);
        }
    }

    public function setCollectionClass($collectionClass)
    {
        $this->collectionClass = $collectionClass;
        return $this;
    }

    public function setIndex(int $index)
    {
        $this->index = $index;
        return $this;
    }

    public function isFirst(): bool
    {
        return $this->hasPrevious() === false;
    }

    public function isLast(): bool
    {
        return $this->hasNext() === false;
    }

    public function hasAncestor(): bool
    {
        return $this->hasParent() && $this->getParent()->hasParent();
    }

    public function hasChildren(): bool
    {
        return count($this->children) > 0;
    }

    public function hasMatchingParentType(): bool
    {
        return $this->hasParent() && get_class($this->getParent()) === get_class($this);
    }

    public function hasParent(): bool
    {
        return $this->parent !== null;
    }

    public function hasSiblings(): bool
    {
        return $this->hasParent() && $this->getParent()->getChildren()->count() > 0;
    }

    public function hasPrevious(): bool
    {
        return $this->hasParent() && $this->getNeighborsAndSelf()->has($this->getIndex() - 1);
    }

    public function hasNext(): bool
    {
        return $this->hasParent() && $this->getNeighborsAndSelf()->has($this->getIndex() + 1);
    }

    public function getNext()
    {
        return $this->getNeighborsAndSelf()->get($this->getIndex() + 1);
    }

    public function getPrevious()
    {
        return $this->getNeighborsAndSelf()->get($this->getIndex() - 1);
    }

    public function getIndex(): int
    {
        if ($this->index !== null) {
            return $this->index;
        }
        if ($this->hasParent()) {
            $this->getNeighborsAndSelf()->search(function ($item) {
                return $item === $this;
            });
        }
        return 0;
    }

    public function up($times = 1)
    {
        $node = $this;
        for ($time = 0; $time < $times; $time++) {
            if ($node->isRoot()) {
                return $node;
            }
            $node = $node->getParent();
        }
        return $node;
    }
}

