<?php
namespace Pim\Bundle\CatalogBundle\Model;

interface ProductValueReferenceDataInterface
{
    /**
     * @return ProductValueInterface
     */
    public function getProductValue();

    /**
     * @param ProductValueInterface $productValue
     *
     * @return ProductValueReferenceDataInterface
     */
    public function setProductValue(ProductValueInterface $productValue);
}
