<?php

namespace Pim\Bundle\CatalogBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class AddReferenceDataSubscriber implements EventSubscriber
{
    /**
     * @staticvar array
     */
    protected static $referenceData = [
        'car' => 'Acme\Bundle\AppBundle\Entity\Car',
    ];

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            'loadClassMetadata'
        ];
    }

    /**
     * Adds repository class for a class name
     *
     * @param string $type
     * @param string $class
     */
    public function addReferenceData($type, $class)
    {
        static::$referenceData[$type] = $class;
    }

    /**
     * Processes event and resolves new object repository class
     *
     * @param LoadClassMetadataEventArgs $args
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        $classMetadata = $args->getClassMetadata();
        $className = $classMetadata->getName();

        if ('Pim\Bundle\CatalogBundle\Model\ProductValueReferenceData' === $className) {

            $builder = new ClassMetadataBuilder($classMetadata);

            foreach (self::$referenceData as $type => $model) {
                $relBuilder = $builder->createManyToOne($type, $model);
                $relBuilder->build();
            }
        }

    }
}
