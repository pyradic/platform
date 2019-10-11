<?php /** @noinspection PhpUnused */

namespace Pyro\Platform\Ui\TreeNode;

/**
 * @mixin \Pyro\Platform\Ui\TreeNode\NodeTrait
 */
interface NodeInterface
{
    /**
     * @param \Pyro\Platform\Ui\TreeNode\NodeInterface $root
     *
     * @return $this
     */
    public function setRoot(NodeInterface $root);

    /**
     * @return $this
     */
    public function setCollectionClass($collectionClass);

    /**
     * @return $this
     */
    public function setIndex(int $index);

    public function hasChild(NodeInterface $child): bool;

    /**
     * @return $this
     */
    public function addChild(NodeInterface $child);

    /**
     * @return $this
     */
    public function removeChild(NodeInterface $child);

    /**
     * @return $this
     */
    public function removeAllChildren();

    /**
     * @return \Pyro\Platform\Ui\TreeNode\NodeInterface[]|\Pyro\Platform\Ui\TreeNode\NodeCollection
     */
    public function getChildren();

    /**
     * @return \Pyro\Platform\Ui\TreeNode\NodeInterface[]|\Pyro\Platform\Ui\TreeNode\NodeCollection
     */
    public function getAllDescendants();

    /**
     * @param array|NodeInterface[]|\Pyro\Platform\Ui\TreeNode\NodeCollection $children
     *
     * @return \Pyro\Platform\Ui\TreeNode\NodeInterface
     */
    public function setChildren($children);

    /**
     * @return $this
     */
    public function removeParentFromChildren();

    public function setParent(?NodeInterface $parent = null);

    /**
     * @return $this
     */
    public function getParent();

    /**
     * @return \Pyro\Platform\Ui\TreeNode\NodeInterface[]|\Pyro\Platform\Ui\TreeNode\NodeCollection
     */
    public function getAncestors();

    /**
     * @return \Pyro\Platform\Ui\TreeNode\NodeInterface[]|\Pyro\Platform\Ui\TreeNode\NodeCollection
     */
    public function getAncestorsAndSelf();

    /**
     * @return \Pyro\Platform\Ui\TreeNode\NodeInterface[]|\Pyro\Platform\Ui\TreeNode\NodeCollection
     */
    public function getNeighbors();

    /**
     * @return \Pyro\Platform\Ui\TreeNode\NodeInterface[]|\Pyro\Platform\Ui\TreeNode\NodeCollection
     */
    public function getNeighborsAndSelf();

    public function isLeaf(): bool;

    public function isRoot(): bool;

    public function isChild(): bool;

    /**
     * @return \Pyro\Platform\Ui\TreeNode\NodeInterface
     */
    public function root();

    public function getDepth(): int;

    public function getHeight(): int;

    public function getSize(): int;

    public function isFirst(): bool;

    public function isLast(): bool;

    public function hasAncestor(): bool;

    public function hasChildren(): bool;

    public function hasMatchingParentType(): bool;

    public function hasParent(): bool;

    public function hasSiblings(): bool;

    public function hasPrevious(): bool;

    public function hasNext(): bool;

    /**
     * @return $this
     */
    public function getNext();

    /**
     * @return $this
     */
    public function getPrevious();

    public function getIndex(): int;
}
