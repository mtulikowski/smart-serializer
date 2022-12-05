<?php

use Smartgroup\SmartSerializer\Tests\Examples\AllFieldTypesAnnotatedObject;
use Smartgroup\SmartSerializer\Tests\Examples\SimpleAnnotatedObject;
use Smartgroup\SmartSerializer\Tests\Examples\AllFieldTypesObject;
use Smartgroup\SmartSerializer\Tests\Examples\SimpleObject;

spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
                SimpleObject::class => '/../tests/Examples/SimpleObject.php',
                AllFieldTypesObject::class => '/../tests/Examples/AllFieldTypesObject.php',
                SimpleAnnotatedObject::class => '/../tests/Examples/SimpleAnnotatedObject.php',
                AllFieldTypesAnnotatedObject::class => '/../tests/Examples/AllFieldTypesAnnotatedObject.php',
            );
        }
        if (isset($classes[$class])) {
            require __DIR__ . $classes[$class];
        }
    },
    true,
    false
);