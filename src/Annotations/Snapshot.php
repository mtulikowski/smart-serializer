<?php

namespace Smartgroup\SmartSerializer\Annotations;

use Attribute;

/**
 * @Annotation
 */
#[Attribute(Attribute::TARGET_ALL)]
class Snapshot
{
    public bool $isObject = false;
    public bool $isDate = false;
    public string $dateFormat = 'Y-m-d H:i:s';
    public string $fieldName = '';
    public bool $isRoute = false;
    public bool $isCollection = false;
    public bool $translate = false;

    /**
     * @param bool $isObject
     * @param bool $isDate
     * @param string $dateFormat
     * @param string $fieldName
     * @param bool $isRoute
     * @param bool $isCollection
     * @param bool $translate
     */
    public function __construct(
        bool $isObject = false,
        bool $isDate = false,
        string $dateFormat = 'Y-m-d H:i:s',
        string $fieldName = '',
        bool $isRoute = false,
        bool $isCollection = false,
        bool $translate = false
    ) {
        $this->isObject = $isObject;
        $this->isDate = $isDate;
        $this->dateFormat = $dateFormat;
        $this->fieldName = $fieldName;
        $this->isRoute = $isRoute;
        $this->isCollection = $isCollection;
        $this->translate = $translate;
    }

}