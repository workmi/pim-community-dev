<?php

namespace spec\Pim\Bundle\CatalogBundle\Updater;

use PhpSpec\ObjectBehavior;
use Pim\Bundle\CatalogBundle\Model\AttributeInterface;
use Pim\Bundle\CatalogBundle\Model\ProductInterface;
use Pim\Bundle\CatalogBundle\Repository\AttributeRepositoryInterface;
use Pim\Bundle\CatalogBundle\Updater\Adder\AdderInterface;
use Pim\Bundle\CatalogBundle\Updater\Adder\AdderRegistryInterface;
use Pim\Bundle\CatalogBundle\Updater\Adder\AttributeAdderInterface;
use Pim\Bundle\CatalogBundle\Updater\Adder\FieldAdderInterface;
use Pim\Bundle\CatalogBundle\Updater\Copier\AttributeCopierInterface;
use Pim\Bundle\CatalogBundle\Updater\Copier\CopierInterface;
use Pim\Bundle\CatalogBundle\Updater\Copier\CopierRegistryInterface;
use Pim\Bundle\CatalogBundle\Updater\Copier\FieldCopierInterface;
use Pim\Bundle\CatalogBundle\Updater\Setter\AttributeSetterInterface;
use Pim\Bundle\CatalogBundle\Updater\Setter\FieldSetterInterface;
use Pim\Bundle\CatalogBundle\Updater\Setter\SetterInterface;
use Pim\Bundle\CatalogBundle\Updater\Setter\SetterRegistryInterface;
use Prophecy\Argument;

class ProductUpdaterSpec extends ObjectBehavior
{
    function let(
        AttributeRepositoryInterface $attributeRepository,
        SetterRegistryInterface $setterRegistry,
        CopierRegistryInterface $copierRegistry,
        AdderRegistryInterface $adderRegistry
    ) {
        $this->beConstructedWith($attributeRepository, $setterRegistry, $copierRegistry, $adderRegistry);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Pim\Bundle\CatalogBundle\Updater\ProductUpdater');
    }

    function it_sets_a_value(
        $setterRegistry,
        $attributeRepository,
        ProductInterface $product1,
        ProductInterface $product2,
        AttributeInterface $attribute,
        AttributeSetterInterface $setter
    ) {
        $products = [$product1, $product2];

        $attributeRepository->findOneBy(['code' => 'field'])->willReturn($attribute);
        $setterRegistry->getAttributeSetter($attribute)->willReturn($setter);
        $setter->setAttributeData($product1, $attribute, 'data', ['locale' => 'fr_FR', 'scope' => 'ecommerce'])->shouldBeCalled();
        $setter->setAttributeData($product2, $attribute, 'data', ['locale' => 'fr_FR', 'scope' => 'ecommerce'])->shouldBeCalled();

        $this->setValue($products, 'field', 'data', 'fr_FR', 'ecommerce');
    }

    function it_throws_an_exception_when_it_sets_an_unknown_field($attributeRepository, ProductInterface $product)
    {
        $attributeRepository->findOneBy(Argument::any())->willReturn(null);
        $this->shouldThrow(new \LogicException('No setter found for field "unknown_field"'))->during(
            'setValue', [[$product], 'unknown_field', 'data', 'fr_FR', 'ecommerce']
        );
    }

    function it_copies_a_value(
        $copierRegistry,
        $attributeRepository,
        ProductInterface $product1,
        ProductInterface $product2,
        AttributeInterface $fromAttribute,
        AttributeInterface $toAttribute,
        AttributeCopierInterface $copier
    ) {
        $products = [$product1, $product2];

        $attributeRepository->findOneBy(['code' => 'from_field'])->willReturn($fromAttribute);
        $attributeRepository->findOneBy(['code' => 'to_field'])->willReturn($toAttribute);
        $copierRegistry->getAttributeCopier($fromAttribute, $toAttribute)->willReturn($copier);
        foreach ($products as $product) {
            $copier
                ->copyAttributeData(
                    $product,
                    $product,
                    $fromAttribute,
                    $toAttribute,
                    [
                        'from_locale' => 'from_locale',
                        'to_locale' => 'to_locale',
                        'from_scope' => 'from_scope',
                        'to_scope' => 'to_scope'
                    ]
                )
                ->shouldBeCalled();
        }

        $this->copyValue($products, 'from_field', 'to_field', 'from_locale', 'to_locale', 'from_scope', 'to_scope');
    }

    function it_throws_an_exception_when_it_copies_an_unknown_field($attributeRepository, ProductInterface $product)
    {
        $attributeRepository->findOneBy(Argument::any())->willReturn(null);
        $this->shouldThrow(new \LogicException('No copier found for fields "unknown_field" and "to_field"'))->during(
            'copyValue', [[$product], 'unknown_field', 'to_field', 'from_locale', 'to_locale', 'from_scope', 'to_scope']
        );
    }

    function it_sets_a_data_to_a_product_attribute(
        $setterRegistry,
        $attributeRepository,
        ProductInterface $product,
        AttributeInterface $attribute,
        AttributeSetterInterface $setter
    ) {
        $attributeRepository->findOneBy(['code' => 'name'])->willReturn($attribute);
        $setterRegistry->getAttributeSetter($attribute)->willReturn($setter);
        $setter
            ->setAttributeData($product, $attribute, 'my name', [])
            ->shouldBeCalled();

        $this->setData($product, 'name', 'my name', []);
    }

    function it_sets_a_data_to_a_product_field(
        $setterRegistry,
        $attributeRepository,
        ProductInterface $product,
        FieldSetterInterface $setter
    ) {
        $attributeRepository->findOneBy(['code' => 'category'])->willReturn(null);
        $setterRegistry->getFieldSetter('category')->willReturn($setter);
        $setter
            ->setFieldData($product, 'category', ['tshirt'], [])
            ->shouldBeCalled();

        $this->setData($product, 'category', ['tshirt'], []);
    }

    function it_adds_a_data_to_a_product_attribute(
        $adderRegistry,
        $attributeRepository,
        ProductInterface $product,
        AttributeInterface $attribute,
        AttributeAdderInterface $adder
    ) {
        $attributeRepository->findOneBy(['code' => 'color'])->willReturn($attribute);
        $adderRegistry->getAttributeAdder($attribute)->willReturn($adder);
        $adder
            ->addAttributeData($product, $attribute, ['red', 'blue'], [])
            ->shouldBeCalled();

        $this->addData($product, 'color', ['red', 'blue'], []);
    }

    function it_adds_a_data_to_a_product_field(
        $adderRegistry,
        $attributeRepository,
        ProductInterface $product,
        FieldAdderInterface $adder
    ) {
        $attributeRepository->findOneBy(['code' => 'category'])->willReturn(null);
        $adderRegistry->getFieldAdder('category')->willReturn($adder);
        $adder
            ->addFieldData($product, 'category', 'tshirt', [])
            ->shouldBeCalled();

        $this->addData($product, 'category', 'tshirt', []);
    }

    function it_copies_a_data_to_a_product_attribute(
        $copierRegistry,
        $attributeRepository,
        ProductInterface $product,
        AttributeInterface $fromAttribute,
        AttributeInterface $toAttribute,
        AttributeCopierInterface $copier
    ) {
        $attributeRepository->findOneBy(['code' => 'color_one'])->willReturn($fromAttribute);
        $attributeRepository->findOneBy(['code' => 'color_two'])->willReturn($toAttribute);
        $copierRegistry->getAttributeCopier($fromAttribute, $toAttribute)->willReturn($copier);
        $copier
            ->copyAttributeData($product, $product, $fromAttribute, $toAttribute, [])
            ->shouldBeCalled();

        $this->copyData($product, $product, 'color_one', 'color_two');
    }

    function it_copies_a_data_to_a_product_field(
        $copierRegistry,
        $attributeRepository,
        ProductInterface $fromProduct,
        ProductInterface $toProduct,
        AttributeInterface $fromAttribute,
        AttributeInterface $toAttribute,
        FieldCopierInterface $copier
    ) {
        $attributeRepository->findOneBy(['code' => 'category'])->willReturn(null);
        $copierRegistry->getFieldCopier('category', 'category')->willReturn($copier);
        $copier
            ->copyFieldData($fromProduct, $toProduct, 'category', 'category', [])
            ->shouldBeCalled();

        $this->copyData($fromProduct, $toProduct, 'category', 'category');
    }
}
