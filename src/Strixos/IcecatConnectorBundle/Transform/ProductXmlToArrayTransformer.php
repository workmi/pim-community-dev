<?php
namespace Strixos\IcecatConnectorBundle\Transform;

/**
 * Aims to transform xml product data to array
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright Copyright (c) 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ProductXmlToArrayTransformer
{

    const PREFIX = 'icecat';

    /**
     * Get product base data
     * @var array
     */
    protected $productBaseData;

    /**
     * Get product features
     * @var array
     */
    protected $productFeatures;

    /**
    * Transform xml file to csv
    *
    * @param string $simpleDoc
    * @param string $csvFile
    */
    public function process($simpleDoc)
    {
        $this->parseBaseData($simpleDoc);
        $this->parseFeatures($simpleDoc);
    }

    /**
     * Parse base product data
     * @param unknown_type $simpleDoc
     */
    protected function parseBaseData($simpleDoc)
    {
        // get product data
        $productTag = $simpleDoc->Product;
        $this->productBaseData['id']       = (string)$productTag['Prod_id'];
        $this->productBaseData['name']     = (string)$productTag['Name'];
        $this->productBaseData['HighPic']  = (string)$productTag['HighPic'];
        $this->productBaseData['LowPic']   = (string)$productTag['HighPic'];
        $this->productBaseData['ThumbPic'] = (string)$productTag['ThumbPic'];
            /* TODO : deal with image id and size ?
        $this->productBaseData['HighPicHeight'] = (string)$productTag['HighPicHeight'];
        $this->productBaseData['HighPicSize'] = (string)$productTag['HighPicSize'];
        $this->productBaseData['HighPicWidth'] = (string)$productTag['HighPicWidth'];
        */
        // TODO deal with other provided product data

        // get vendor data
        $supplierTag = $productTag->Supplier;
        $this->productBaseData['vendorId']   = (string) $supplierTag['ID'];
        $this->productBaseData['vendorName'] = (string) $supplierTag['Name'];
        // get summary description data
        $summaryTag = $productTag->SummaryDescription;
        $this->productBaseData['ShortDescription'] = (string) $productTag->SummaryDescription->ShortSummaryDescription;
        $this->productBaseData['LongDescription']  = (string) $productTag->SummaryDescription->LongSummaryDescription;

        // get category data
        $categoryTag = $productTag->Category;
        $this->productBaseData['CategoryId']   = (string) $categoryTag['ID'];
        $this->productBaseData['CategoryName'] = (string) $categoryTag->Name['Value'];

        // get category feature group id
        $this->productBaseData['CategoryFeaturesGroupId']   = (string) $productTag->CategoryFeatureGroup['ID'];
    }

    /**
     * Parse base product data
     * @param unknown_type $simpleDoc
     */
    protected function parseFeatures($simpleDoc)
    {
        $descriptionArray = array();
        $specGroups = $simpleDoc->Product->CategoryFeatureGroup;
        $specFeatures = $simpleDoc->Product->ProductFeature;
        foreach ($specFeatures as $feature) {
            $id = (int)$feature['CategoryFeatureGroup_ID'];
            $featureText = (string) $feature["Presentation_Value"];
            $featureName = (string) $feature->Feature->Name["Value"];
            $featureId = (string) $feature["ID"];
            foreach ($specGroups as $group) {
                $groupId = (int)$group["ID"];
                if ($groupId == $id) {
                    $groupName = (string) $group->FeatureGroup->Name["Value"];
                    $rating = (int)$group['No'];
                    $descriptionArray[$rating][$groupName][$featureId] = array('name' => $featureName, 'value' => $featureText);
                    break;
                }
            }
        }
        krsort($descriptionArray);
        $this->productFeatures = $descriptionArray;
    }

   /**
    * Get product data
    * @return Array:
    */
    public function getProductBaseData()
    {
        return $this->productBaseData;
    }

    /**
     * Get product features
     * @return Array
     */
    public function getProductFeatures()
    {
        return $this->productFeatures;
    }
}
