<?php

namespace Pim\Bundle\CatalogBundle\Model;

interface ReferenceDataInterface
{
    public function getType();

    public function getIdentifier();

    public function getIdentifierProperties();
}
