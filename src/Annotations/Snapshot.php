<?php

namespace Smartgroup\SmartSerializer\Annotations;

use Attribute;

/**
 * @Annotation
 */
#[Attribute(Attribute::TARGET_ALL)]
final class Snapshot
{
    public bool $isObject = false;
    public bool $isDate = false;
    public string $dateFormat = 'Y-m-d H:i:s';
    public string $fieldName = '';
    public bool $isRoute = false;
    public bool $isCollection = false;
    public bool $translate = false;

}