<?php

namespace Pim\Bundle\CatalogBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

interface ReferenceDataInterface
{
    /**
     * @return ArrayCollection of ProductValueReferenceDataInterface
     */
    public function getProductValueReferenceData();

    /**
     * @param ArrayCollection $data
     *
     * @return ReferenceDataInterface
     */
    public function setProductValueReferenceData(ArrayCollection $data);
}
