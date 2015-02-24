<?php

namespace Pim\Bundle\CatalogBundle\Model;

class ProductValueReferenceData implements ProductValueReferenceDataInterface
{
    protected $id;

    // TODO: should not be hardcoded
    protected $car;

    /** @var ProductValueInterface */
    protected $productValue;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductValue()
    {
        return $this->productValue;
    }

    /**
     * {@inheritdoc}
     */
    public function setProductValue(ProductValueInterface $productValue)
    {
        $this->productValue = $productValue;

        return $this;
    }
}
