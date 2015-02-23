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

    /**
     * @return ReferenceDataInterface
     */
    public function getReferenceData();

    /**
     * @param ReferenceDataInterface $referenceData
     *
     * @return ProductValueReferenceDataInterface
     */
    public function setReferenceData(ReferenceDataInterface $referenceData);

    /**
     * @return string
     */
    public function getReferenceDataType();

    /**
     * @param $referenceDataType
     *
     * @return ProductValueReferenceDataInterface
     */
    public function setReferenceDataType($referenceDataType);
}
