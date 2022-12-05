<?php

namespace Smartgroup\SmartSerializer;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Proxy\Proxy;
use JsonException;
use ReflectionClass;
use ReflectionException;
use Smartgroup\SmartSerializer\Annotations\Snapshot;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class Serializer
{
    /**
     * @throws JsonException
     * @throws ReflectionException
     */
    public static function getSnapshot(
        $object,
        bool $getAsJson = false,
        RouterInterface $router = null,
        TranslatorInterface $translator = null,
        int $nestingLevel = 1
    ): bool|array|string
    {
        $output = [];
        if ($object instanceof Proxy) {
            $reflectionClass = new ReflectionClass(ClassUtils::getRealClass(get_class($object)));
            if (!$object->__isInitialized()) {
                $object->__load();
            }
        } else {
            $reflectionClass = new ReflectionClass(get_class($object));
        }
        $reader = new AnnotationReader();
        foreach ($reflectionClass->getProperties() as $property) {
            $snapshot = null;
            $snapshotData = self::getClearSnapshotConfig();
            $property->setAccessible(true);


            $snapshotAttributes = $property->getAttributes(Snapshot::class);
            if (count($snapshotAttributes) === 1) {
                $snapshotArguments = $snapshotAttributes[0]->getArguments();
                $snapshotData['isSnapshot'] = true;
                $snapshotData['isObject'] = array_key_exists('isObject', $snapshotArguments) && $snapshotArguments['isObject'] === true;
                $snapshotData['isDate'] = array_key_exists('isDate', $snapshotArguments) && $snapshotArguments['isDate'] === true;
                $snapshotData['isCollection'] = array_key_exists('isCollection', $snapshotArguments) && $snapshotArguments['isCollection'] === true;
                $snapshotData['isTranslator'] = array_key_exists('translate', $snapshotArguments) && $snapshotArguments['translate'] === true;
            } else {
                $snapshot = $reader->getPropertyAnnotation($property, Snapshot::class);

                if ($snapshot !== null) {
                    $snapshotData['isSnapshot'] = true;
                    $snapshotData['isObject'] = $snapshot->isObject === true;
                    $snapshotData['isDate'] = $snapshot->isDate === true;
                    $snapshotData['isCollection'] = $snapshot->isCollection === true;
                    $snapshotData['isTranslator'] = $snapshot->translate === true;
                }
            }

            if ($snapshotData['isSnapshot']) {
                if ($snapshotData['isObject']) {
                    $value = $property->getValue($object) === null || $nestingLevel > 1 ?
                        null : self::getSnapshot($property->getValue($object), $getAsJson, $router, $translator, $nestingLevel + 1);
                } elseif ($snapshotData['isDate']) {
                    $value = $property->getValue($object)?->format('Y-m-d H:i:s');
                } elseif ($snapshotData['isCollection']) {
                    /** @var Collection $value */
                    $collection = $property->getValue($object);
                    $value = [];
                    if ($collection->count() > 0) {
                        foreach ($collection as $item) {
                            $value[] = self::getSnapshot($item);
                        }
                    }
                } else {
                    if ($snapshotData['isTranslator'] && $translator !== null) {
                        $value = $translator->trans($property->getValue($object));
                        $output[$property->getName() . '_raw'] = $property->getValue($object);
                    } else {
                        $value = $property->getValue($object);
                    }
                }
                $output[$property->getName()] = $value;
            }
        }

        foreach ($reflectionClass->getMethods() as $method) {
            $snapshot = null;
            $snapshotData = self::getClearSnapshotConfig();
            $method->setAccessible(true);

            $snapshotAttributes = $method->getAttributes(Snapshot::class);
            if (count($snapshotAttributes) === 1) {
                $snapshotArguments = $snapshotAttributes[0]->getArguments();
                $snapshotData['isSnapshot'] = true;
                $snapshotData['isRoute'] = array_key_exists('isRoute', $snapshotArguments) && $snapshotArguments['isRoute'] === true;
                $snapshotData['fieldName'] = array_key_exists('fieldName', $snapshotArguments) ? $snapshotArguments['fieldName'] : '';
            } else {
                $snapshot = $reader->getMethodAnnotation($method, Snapshot::class);

                if ($snapshot !== null) {
                    $snapshotData['isSnapshot'] = true;
                    $snapshotData['isRoute'] = $snapshot->isRoute === true;
                    $snapshotData['fieldName'] = $snapshot->fieldName;
                }
            }

            if ($snapshotData['isSnapshot']) {
                if ($snapshotData['isRoute'] && $router !== null) {
                    $value = $method->invoke($object);
                    if ($value['route'] !== null) {
                        $output[$snapshotData['fieldName']] = $router->generate($value['route'], ['user' => $value['routeParams']]);
                    } else {
                        $output[$snapshotData['fieldName']] = '';
                    }
                } else {
                    $output[$snapshotData['fieldName']] = $method->invoke($object);
                }
            }
        }

        return $getAsJson ? json_encode($output, JSON_THROW_ON_ERROR) : $output;
    }

    private static function getClearSnapshotConfig(): array
    {
        return [
            'isSnapshot' => false,
            'isObject' => false,
            'isDate' => false,
            'isCollection' => false,
            'isTranslator' => false,
            'isRoute' => false,
            'fieldName' => ''
        ];
    }

}