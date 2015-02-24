<?php

namespace Pim\Bundle\CatalogBundle\Model;

abstract class ReferenceData implements ReferenceDataInterface
{
    /** @var int */
    protected $id;

    /** @var ProductValueReferenceDataInterface */
    protected $productValueReferenceData;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ProductValueReferenceDataInterface
     */
    public function getProductValueReferenceData()
    {
        return $this->productValueReferenceData;
    }

    /**
     * @param ProductValueReferenceDataInterface $productValueReferenceData
     */
    public function setProductValueReferenceData(ProductValueReferenceDataInterface $productValueReferenceData)
    {
        $this->productValueReferenceData = $productValueReferenceData;
    }
}
