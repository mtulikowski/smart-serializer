<?php
namespace Smartgroup\SmartSerializer\Tests;

use PHPUnit\Framework\TestCase;
use Smartgroup\SmartSerializer\Serializer;
use Smartgroup\SmartSerializer\Tests\Examples\AllFieldTypesAnnotatedObject;
use Smartgroup\SmartSerializer\Tests\Examples\AllFieldTypesObject;
use Smartgroup\SmartSerializer\Tests\Examples\SimpleAnnotatedObject;
use Smartgroup\SmartSerializer\Tests\Examples\SimpleObject;

class SerializerTest extends TestCase
{
    /**
     * @throws \ReflectionException
     * @throws \JsonException
     */
    public function testSimpleObject(): void
    {
        $testObject = new SimpleObject(1, "test-object");

        $serialized = Serializer::getSnapshot($testObject, true);

        $this->assertEquals('{"id":"1","name":"test-object"}', $serialized);
    }

    /**
     * @throws \ReflectionException
     * @throws \JsonException
     */
    public function testAllFieldTypesObject(): void
    {
        $expected = '{"id":99,"name":"all-types-obj","creationDate":"2022-12-06 10:00:00","options":["option 1","option 2"],"simpleObject":"{\"id\":\"1\",\"name\":\"simple\"}","prefixedName":"prefix_all-types-obj"}';

        $simpleObject = new SimpleObject(1, "simple");
        $testObject = new AllFieldTypesObject(99, "all-types-obj");
        $testObject->setSimpleObject($simpleObject);
        $testObject->setCreationDate(new \DateTime("2022-12-06T10:00:00"));
        $testObject->addOption('option 1');
        $testObject->addOption('option 2');

        $serialized = Serializer::getSnapshot($testObject, true);

        $this->assertEquals($expected, $serialized);
    }

    public function testSimpleAnnotatedObject(): void
    {
        $testObject = new SimpleAnnotatedObject(1, "test-object");

        $serialized = Serializer::getSnapshot($testObject, true);

        $this->assertEquals('{"id":"1","name":"test-object"}', $serialized);
    }

    public function testAllFieldTypesAnnotatedObject(): void
    {
        $expected = '{"id":99,"name":"all-types-obj","creationDate":"2022-12-06 10:00:00","options":["option 1","option 2"],"simpleObject":"{\"id\":\"1\",\"name\":\"simple\"}","prefixedName":"prefix_all-types-obj"}';

        $simpleObject = new SimpleAnnotatedObject(1, "simple");
        $testObject = new AllFieldTypesAnnotatedObject(99, "all-types-obj");
        $testObject->setSimpleObject($simpleObject);
        $testObject->setCreationDate(new \DateTime("2022-12-06T10:00:00"));
        $testObject->addOption('option 1');
        $testObject->addOption('option 2');

        $serialized = Serializer::getSnapshot($testObject, true);

        $this->assertEquals($expected, $serialized);
    }
}