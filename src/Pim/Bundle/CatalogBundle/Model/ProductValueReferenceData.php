<?php

namespace Pim\Bundle\CatalogBundle\Model;

class ProductValueReferenceData implements ProductValueReferenceDataInterface
{
    /** @var ProductValueInterface */
    protected $productValue;

    /** @var ReferenceDataInterface */
    protected $referenceData;

    /** @var string */
    protected $referenceDataType;

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

    /**
     * {@inheritdoc}
     */
    public function getReferenceData()
    {
        return $this->referenceData;
    }

    /**
     * {@inheritdoc}
     */
    public function setReferenceData(ReferenceDataInterface $referenceData)
    {
        $this->referenceData = $referenceData;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceDataType()
    {
        return $this->referenceDataType;
    }

    /**
     * {@inheritdoc}
     */
    public function setReferenceDataType($referenceDataType)
    {
        $this->referenceDataType = $referenceDataType;

        return $this;
    }
}
