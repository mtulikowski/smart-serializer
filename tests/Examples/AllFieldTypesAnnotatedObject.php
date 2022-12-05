<?php

namespace Smartgroup\SmartSerializer\Tests\Examples;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Smartgroup\SmartSerializer\Annotations\Snapshot;

class AllFieldTypesAnnotatedObject
{
    /**
     * @var int
     * @Snapshot()
     */
    private int $id;
    /**
     * @var string
     * @Snapshot()
     */
    private string $name;
    /**
     * @var \DateTime
     * @Snapshot(isDate=true)
     */
    private \DateTime $creationDate;
    /**
     * @var Collection|ArrayCollection
     * @Snapshot(isCollection=true)
     */
    private Collection $options;
    /**
     * @var SimpleAnnotatedObject
     * @Snapshot(isObject=true)
     */
    private SimpleAnnotatedObject $simpleObject;

    /**
     * @param int $id
     * @param string $name
     */
    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->options = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate(): \DateTime
    {
        return $this->creationDate;
    }

    /**
     * @param \DateTime $creationDate
     */
    public function setCreationDate(\DateTime $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return Collection
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    /**
     * @param Collection $options
     */
    public function setOptions(Collection $options): void
    {
        $this->options = $options;
    }

    public function addOption(string $option): void
    {
        $this->options->add($option);
    }

    /**
     * @return SimpleObject
     */
    public function getSimpleObject(): SimpleAnnotatedObject
    {
        return $this->simpleObject;
    }

    /**
     * @param SimpleObject $simpleObject
     */
    public function setSimpleObject(SimpleAnnotatedObject $simpleObject): void
    {
        $this->simpleObject = $simpleObject;
    }

    /**
     * @return string
     * @Snapshot(fieldName="prefixedName")
     */
    public function getPrefixedName(): string
    {
        return 'prefix_' . $this->name;
    }
}