<?php /** @noinspection PhpUnused */

namespace Pyradic\Platform\Ui\TreeNode;

/**
 * @mixin \Pyradic\Platform\Ui\TreeNode\NodeTrait
 */
interface NodeInterface
{
    /**
     * @param \Pyradic\Platform\Ui\TreeNode\NodeInterface $root
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
     * @return \Pyradic\Platform\Ui\TreeNode\NodeInterface[]|\Pyradic\Platform\Ui\TreeNode\NodeCollection
     */
    public function getChildren();

    /**
     * @return \Pyradic\Platform\Ui\TreeNode\NodeInterface[]|\Pyradic\Platform\Ui\TreeNode\NodeCollection
     */
    public function getAllDescendants();

    /**
     * @param array|NodeInterface[]|\Pyradic\Platform\Ui\TreeNode\NodeCollection $children
     *
     * @return \Pyradic\Platform\Ui\TreeNode\NodeInterface
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
     * @return \Pyradic\Platform\Ui\TreeNode\NodeInterface[]|\Pyradic\Platform\Ui\TreeNode\NodeCollection
     */
    public function getAncestors();

    /**
     * @return \Pyradic\Platform\Ui\TreeNode\NodeInterface[]|\Pyradic\Platform\Ui\TreeNode\NodeCollection
     */
    public function getAncestorsAndSelf();

    /**
     * @return \Pyradic\Platform\Ui\TreeNode\NodeInterface[]|\Pyradic\Platform\Ui\TreeNode\NodeCollection
     */
    public function getNeighbors();

    /**
     * @return \Pyradic\Platform\Ui\TreeNode\NodeInterface[]|\Pyradic\Platform\Ui\TreeNode\NodeCollection
     */
    public function getNeighborsAndSelf();

    public function isLeaf(): bool;

    public function isRoot(): bool;

    public function isChild(): bool;

    /**
     * @return \Pyradic\Platform\Ui\TreeNode\NodeInterface
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
