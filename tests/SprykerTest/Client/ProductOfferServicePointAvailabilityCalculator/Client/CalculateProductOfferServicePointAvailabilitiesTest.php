<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductOfferServicePointAvailabilityCalculator\Client;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorToProductOfferServicePointAvailabilityClientInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorToStoreClientInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculator\ProductOfferServicePointAvailabilityCalculatorDependencyProvider;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculatorExtension\Dependency\Plugin\ProductOfferServicePointAvailabilityCalculatorStrategyPluginInterface;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerTest\Client\ProductOfferServicePointAvailabilityCalculator\ProductOfferServicePointAvailabilityCalculatorClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductOfferServicePointAvailabilityCalculator
 * @group Client
 * @group CalculateProductOfferServicePointAvailabilitiesTest
 * Add your own group annotations below this line
 */
class CalculateProductOfferServicePointAvailabilitiesTest extends Unit
{
    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_1 = 'offer-1';

    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_2 = 'offer-2';

    /**
     * @var string
     */
    protected const PRODUCT_SKU_1 = 'sku-1';

    /**
     * @var string
     */
    protected const PRODUCT_SKU_2 = 'sku-2';

    /**
     * @var \SprykerTest\Client\ProductOfferServicePointAvailabilityCalculator\ProductOfferServicePointAvailabilityCalculatorClientTester
     */
    protected ProductOfferServicePointAvailabilityCalculatorClientTester $tester;

    /**
     * @return void
     */
    public function testExecutesProductOfferServicePointAvailabilityCalculatorStrategyPlugins(): void
    {
        // Arrange
        $this->tester->setDependency(
            ProductOfferServicePointAvailabilityCalculatorDependencyProvider::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY,
            $this->geProductOfferServicePointAvailabilityClientMock((new ProductOfferServicePointAvailabilityCollectionTransfer())),
        );

        $productOfferServicePointAvailabilityCriteriaTransfer = $this->tester->createProductOfferServicePointAvailabilityCriteriaTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 1,
            ],
        ]);

        // Assert
        $this->tester->setDependency(
            ProductOfferServicePointAvailabilityCalculatorDependencyProvider::PLUGINS_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CALCULATOR_STRATEGY,
            [$this->getProductOfferServicePointAvailabilityCalculatorStrategyPluginMock()],
        );

        // Act
        $this->tester->getClient()->calculateProductOfferServicePointAvailabilities($productOfferServicePointAvailabilityCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testReturnsNotAvailableResponseWhenProductOfferReferenceWasNotProvided(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityCollectionTransfer = new ProductOfferServicePointAvailabilityCollectionTransfer();

        $this->tester->setDependency(
            ProductOfferServicePointAvailabilityCalculatorDependencyProvider::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY,
            $this->geProductOfferServicePointAvailabilityClientMock($productOfferServicePointAvailabilityCollectionTransfer),
        );

        $productOfferServicePointAvailabilityCriteriaTransfer = $this->tester->createProductOfferServicePointAvailabilityCriteriaTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
            ],
        ]);

        // Act
        $productOfferServicePointAvailabilities = $this->tester->getClient()->calculateProductOfferServicePointAvailabilities($productOfferServicePointAvailabilityCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productOfferServicePointAvailabilities);
        $this->assertArrayHasKey(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilities);
        $this->assertArrayHasKey(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_2, $productOfferServicePointAvailabilities);

        $productOfferServicePointAvailabilitiesPerServicePoint = $productOfferServicePointAvailabilities[ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1];
        $this->assertCount(1, $productOfferServicePointAvailabilitiesPerServicePoint);

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[0];
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(0, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertNull($productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertSame('0', $productOfferServicePointAvailabilityResponseItemTransfer->getIdentifier());
        $this->assertSame(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());

        $productOfferServicePointAvailabilitiesPerServicePoint = $productOfferServicePointAvailabilities[ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_2];
        $this->assertCount(1, $productOfferServicePointAvailabilitiesPerServicePoint);

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[0];
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(0, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertNull($productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertSame('0', $productOfferServicePointAvailabilityResponseItemTransfer->getIdentifier());
        $this->assertSame(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_2, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
    }

    /**
     * @return void
     */
    public function testReturnsAvailabilitiesWhenNoOfferAvailabilitiesFound(): void
    {
        // Arrange
        $this->tester->setDependency(
            ProductOfferServicePointAvailabilityCalculatorDependencyProvider::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY,
            $this->geProductOfferServicePointAvailabilityClientMock(new ProductOfferServicePointAvailabilityCollectionTransfer()),
        );

        $productOfferServicePointAvailabilityCriteriaTransfer = $this->tester->createProductOfferServicePointAvailabilityCriteriaTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 1,
            ],
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_2,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_2,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 1,
            ],
        ]);

        // Act
        $productOfferServicePointAvailabilities = $this->tester->getClient()->calculateProductOfferServicePointAvailabilities($productOfferServicePointAvailabilityCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productOfferServicePointAvailabilities);

        foreach ($productOfferServicePointAvailabilities as $servicePointUuid => $productOfferServicePointAvailabilityResponseItemTransfers) {
            $firstProductOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilityResponseItemTransfers[0];
            $secondProductOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilityResponseItemTransfers[1];

            $this->assertFalse($firstProductOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
            $this->assertFalse($firstProductOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
            $this->assertSame(0, $firstProductOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
            $this->assertSame($servicePointUuid, $firstProductOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
            $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $firstProductOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
            $this->assertSame('0', $firstProductOfferServicePointAvailabilityResponseItemTransfer->getIdentifier());

            $this->assertFalse($secondProductOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
            $this->assertFalse($secondProductOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
            $this->assertSame(0, $secondProductOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
            $this->assertSame($servicePointUuid, $secondProductOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
            $this->assertSame(static::PRODUCT_OFFER_REFERENCE_2, $secondProductOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
            $this->assertSame('1', $secondProductOfferServicePointAvailabilityResponseItemTransfer->getIdentifier());
        }
    }

    /**
     * @return void
     */
    public function testReturnsAvailabilitiesWhenAvailabilitiesAreFound(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(10)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1),
            )
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(1)
                    ->setIsNeverOutOfStock(true)
                    ->setServicePointUuid(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_2),
            );

        $this->tester->setDependency(
            ProductOfferServicePointAvailabilityCalculatorDependencyProvider::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY,
            $this->geProductOfferServicePointAvailabilityClientMock($productOfferServicePointAvailabilityCollectionTransfer),
        );

        $productOfferServicePointAvailabilityCriteriaTransfer = $this->tester->createProductOfferServicePointAvailabilityCriteriaTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
            ],
        ]);

        // Act
        $productOfferServicePointAvailabilities = $this->tester->getClient()->calculateProductOfferServicePointAvailabilities($productOfferServicePointAvailabilityCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productOfferServicePointAvailabilities);
        $this->assertArrayHasKey(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilities);
        $this->assertArrayHasKey(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_2, $productOfferServicePointAvailabilities);

        $productOfferServicePointAvailabilitiesPerServicePoint = $productOfferServicePointAvailabilities[ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1];
        $this->assertCount(1, $productOfferServicePointAvailabilitiesPerServicePoint);

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[0];
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(10, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertSame('0', $productOfferServicePointAvailabilityResponseItemTransfer->getIdentifier());
        $this->assertSame(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());

        $productOfferServicePointAvailabilitiesPerServicePoint = $productOfferServicePointAvailabilities[ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_2];
        $this->assertCount(1, $productOfferServicePointAvailabilitiesPerServicePoint);

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[0];
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(1, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertSame('0', $productOfferServicePointAvailabilityResponseItemTransfer->getIdentifier());
        $this->assertSame(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_2, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
    }

    /**
     * @return void
     */
    public function testSharesTheSameOfferForSplitItemsWithFullAvailability(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(15)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1),
            );

        $this->tester->setDependency(
            ProductOfferServicePointAvailabilityCalculatorDependencyProvider::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY,
            $this->geProductOfferServicePointAvailabilityClientMock($productOfferServicePointAvailabilityCollectionTransfer),
        );

        $productOfferServicePointAvailabilityCriteriaTransfer = $this->tester->createProductOfferServicePointAvailabilityCriteriaTransfer(
            [
                [
                    ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                    ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                    ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
                ],
                [
                    ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                    ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                    ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
                ],
            ],
            [
                ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1,
            ],
        );

        // Act
        $productOfferServicePointAvailabilities = $this->tester->getClient()->calculateProductOfferServicePointAvailabilities($productOfferServicePointAvailabilityCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilities);
        $this->assertArrayHasKey(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilities);

        $productOfferServicePointAvailabilitiesPerServicePoint = $productOfferServicePointAvailabilities[ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1];
        $this->assertCount(2, $productOfferServicePointAvailabilitiesPerServicePoint);

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[0];
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(15, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertSame('0', $productOfferServicePointAvailabilityResponseItemTransfer->getIdentifier());
        $this->assertSame(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[1];
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(10, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertSame('1', $productOfferServicePointAvailabilityResponseItemTransfer->getIdentifier());
        $this->assertSame(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
    }

    /**
     * @return void
     */
    public function testSharesTheSameOfferForSplitItemsWithPartialAvailability(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(7)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1),
            );

        $this->tester->setDependency(
            ProductOfferServicePointAvailabilityCalculatorDependencyProvider::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY,
            $this->geProductOfferServicePointAvailabilityClientMock($productOfferServicePointAvailabilityCollectionTransfer),
        );

        $productOfferServicePointAvailabilityCriteriaTransfer = $this->tester->createProductOfferServicePointAvailabilityCriteriaTransfer(
            [
                [
                    ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                    ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                    ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
                ],
                [
                    ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                    ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                    ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
                ],
            ],
            [
                ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1,
            ],
        );

        // Act
        $productOfferServicePointAvailabilities = $this->tester->getClient()->calculateProductOfferServicePointAvailabilities($productOfferServicePointAvailabilityCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilities);
        $this->assertArrayHasKey(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilities);

        $productOfferServicePointAvailabilitiesPerServicePoint = $productOfferServicePointAvailabilities[ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1];
        $this->assertCount(2, $productOfferServicePointAvailabilitiesPerServicePoint);

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[0];
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(7, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertSame('0', $productOfferServicePointAvailabilityResponseItemTransfer->getIdentifier());
        $this->assertSame(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[1];
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertFalse($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(2, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertSame('1', $productOfferServicePointAvailabilityResponseItemTransfer->getIdentifier());
        $this->assertSame(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
    }

    /**
     * @return void
     */
    public function testSharesTheSameOfferForSplitItemsWithNeverOutOfStock(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(1)
                    ->setIsNeverOutOfStock(true)
                    ->setServicePointUuid(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1),
            );

        $this->tester->setDependency(
            ProductOfferServicePointAvailabilityCalculatorDependencyProvider::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY,
            $this->geProductOfferServicePointAvailabilityClientMock($productOfferServicePointAvailabilityCollectionTransfer),
        );

        $productOfferServicePointAvailabilityCriteriaTransfer = $this->tester->createProductOfferServicePointAvailabilityCriteriaTransfer(
            [
                [
                    ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                    ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                    ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
                ],
                [
                    ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                    ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                    ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 5,
                ],
            ],
            [
                ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1,
            ],
        );

        // Act
        $productOfferServicePointAvailabilities = $this->tester->getClient()->calculateProductOfferServicePointAvailabilities($productOfferServicePointAvailabilityCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilities);
        $this->assertArrayHasKey(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilities);

        $productOfferServicePointAvailabilitiesPerServicePoint = $productOfferServicePointAvailabilities[ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1];
        $this->assertCount(2, $productOfferServicePointAvailabilitiesPerServicePoint);

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[0];
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(1, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertSame('0', $productOfferServicePointAvailabilityResponseItemTransfer->getIdentifier());
        $this->assertSame(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());

        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilitiesPerServicePoint[1];
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsAvailable());
        $this->assertTrue($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStock());
        $this->assertSame(1, $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity());
        $this->assertSame(static::PRODUCT_OFFER_REFERENCE_1, $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference());
        $this->assertSame('1', $productOfferServicePointAvailabilityResponseItemTransfer->getIdentifier());
        $this->assertSame(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1, $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuid());
    }

    /**
     * @return void
     */
    public function testSetsCurrentStoreWhenStoreIsMissingInRequest(): void
    {
        // Arrange
        $this->tester->setDependency(
            ProductOfferServicePointAvailabilityCalculatorDependencyProvider::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY,
            $this->geProductOfferServicePointAvailabilityClientMock((new ProductOfferServicePointAvailabilityCollectionTransfer())),
        );

        $productOfferServicePointAvailabilityCriteriaTransfer = $this->tester->createProductOfferServicePointAvailabilityCriteriaTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 1,
            ],
        ]);

        // Assert
        $this->tester->setDependency(
            ProductOfferServicePointAvailabilityCalculatorDependencyProvider::CLIENT_STORE,
            $this->geStoreClientMock(true),
        );

        // Act
        $this->tester->getClient()->calculateProductOfferServicePointAvailabilities($productOfferServicePointAvailabilityCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testUsesStoreFromRequestWhenProvided(): void
    {
        // Arrange
        $this->tester->setDependency(
            ProductOfferServicePointAvailabilityCalculatorDependencyProvider::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY,
            $this->geProductOfferServicePointAvailabilityClientMock((new ProductOfferServicePointAvailabilityCollectionTransfer())),
        );

        $productOfferServicePointAvailabilityCriteriaTransfer = $this->tester->createProductOfferServicePointAvailabilityCriteriaTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 1,
            ],
        ]);

        $productOfferServicePointAvailabilityCriteriaTransfer
            ->getProductOfferServicePointAvailabilityConditionsOrFail()
            ->setStoreName('DE');

        // Assert
        $this->tester->setDependency(
            ProductOfferServicePointAvailabilityCalculatorDependencyProvider::CLIENT_STORE,
            $this->geStoreClientMock(false),
        );

        // Act
        $this->tester->getClient()->calculateProductOfferServicePointAvailabilities($productOfferServicePointAvailabilityCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenProductOfferServicePointAvailabilityConditionsPropertyIsMissing(): void
    {
        // Arrange
        $this->tester->setDependency(
            ProductOfferServicePointAvailabilityCalculatorDependencyProvider::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY,
            $this->geProductOfferServicePointAvailabilityClientMock((new ProductOfferServicePointAvailabilityCollectionTransfer())),
        );

        $productOfferServicePointAvailabilityCriteriaTransfer = $this->tester->createProductOfferServicePointAvailabilityCriteriaTransfer();

        $productOfferServicePointAvailabilityCriteriaTransfer->setProductOfferServicePointAvailabilityConditions(null);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getClient()->calculateProductOfferServicePointAvailabilities($productOfferServicePointAvailabilityCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenProductOfferServicePointAvailabilityRequestItemsPropertyIsMissing(): void
    {
        // Arrange
        $this->tester->setDependency(
            ProductOfferServicePointAvailabilityCalculatorDependencyProvider::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY,
            $this->geProductOfferServicePointAvailabilityClientMock((new ProductOfferServicePointAvailabilityCollectionTransfer())),
        );

        $productOfferServicePointAvailabilityCriteriaTransfer = $this->tester->createProductOfferServicePointAvailabilityCriteriaTransfer();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getClient()->calculateProductOfferServicePointAvailabilities($productOfferServicePointAvailabilityCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenProductConcreteSkuPropertyIsMissing(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(1)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1),
            );

        $this->tester->setDependency(
            ProductOfferServicePointAvailabilityCalculatorDependencyProvider::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY,
            $this->geProductOfferServicePointAvailabilityClientMock($productOfferServicePointAvailabilityCollectionTransfer),
        );

        $productOfferServicePointAvailabilityCriteriaTransfer = $this->tester->createProductOfferServicePointAvailabilityCriteriaTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::QUANTITY => 1,
            ],
        ]);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getClient()->calculateProductOfferServicePointAvailabilities($productOfferServicePointAvailabilityCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenQuantityPropertyIsMissing(): void
    {
        // Arrange
        $productOfferServicePointAvailabilityCollectionTransfer = (new ProductOfferServicePointAvailabilityCollectionTransfer())
            ->addProductOfferServicePointAvailabilityResponseItem(
                (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                    ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
                    ->setProductConcreteSku(static::PRODUCT_SKU_1)
                    ->setAvailableQuantity(1)
                    ->setIsNeverOutOfStock(false)
                    ->setServicePointUuid(ProductOfferServicePointAvailabilityCalculatorClientTester::SERVICE_POINT_UUID_1),
            );

        $this->tester->setDependency(
            ProductOfferServicePointAvailabilityCalculatorDependencyProvider::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY,
            $this->geProductOfferServicePointAvailabilityClientMock($productOfferServicePointAvailabilityCollectionTransfer),
        );

        $productOfferServicePointAvailabilityCriteriaTransfer = $this->tester->createProductOfferServicePointAvailabilityCriteriaTransfer([
            [
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU => static::PRODUCT_SKU_1,
            ],
        ]);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getClient()->calculateProductOfferServicePointAvailabilities($productOfferServicePointAvailabilityCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer
     *
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorToProductOfferServicePointAvailabilityClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function geProductOfferServicePointAvailabilityClientMock(
        ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer
    ): ProductOfferServicePointAvailabilityCalculatorToProductOfferServicePointAvailabilityClientInterface {
        $productOfferServicePointAvailabilityClientMock = $this->getMockBuilder(ProductOfferServicePointAvailabilityCalculatorToProductOfferServicePointAvailabilityClientInterface::class)
            ->onlyMethods(['getProductOfferServicePointAvailabilityCollection'])
            ->getMock();

        $productOfferServicePointAvailabilityClientMock
            ->method('getProductOfferServicePointAvailabilityCollection')
            ->willReturn($productOfferServicePointAvailabilityCollectionTransfer);

        return $productOfferServicePointAvailabilityClientMock;
    }

    /**
     * @param bool $isGetCurrentStoreMethodExpectsToBeCalled
     *
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorToStoreClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function geStoreClientMock(
        bool $isGetCurrentStoreMethodExpectsToBeCalled
    ): ProductOfferServicePointAvailabilityCalculatorToStoreClientInterface {
        $storeClientMock = $this->getMockBuilder(ProductOfferServicePointAvailabilityCalculatorToStoreClientInterface::class)
            ->onlyMethods(['getCurrentStore'])
            ->getMock();

        $storeClientMock
            ->expects($isGetCurrentStoreMethodExpectsToBeCalled ? $this->once() : $this->never())
            ->method('getCurrentStore');

        return $storeClientMock;
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorExtension\Dependency\Plugin\ProductOfferServicePointAvailabilityCalculatorStrategyPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getProductOfferServicePointAvailabilityCalculatorStrategyPluginMock(): ProductOfferServicePointAvailabilityCalculatorStrategyPluginInterface
    {
        $productOfferServicePointAvailabilityCalculatorStrategyPluginMock = $this->getMockBuilder(ProductOfferServicePointAvailabilityCalculatorStrategyPluginInterface::class)
            ->onlyMethods(['isApplicable', 'calculateProductOfferServicePointAvailabilities'])
            ->getMock();

        $productOfferServicePointAvailabilityCalculatorStrategyPluginMock
            ->expects($this->once())
            ->method('isApplicable')
            ->willReturn(true);

        $productOfferServicePointAvailabilityCalculatorStrategyPluginMock
            ->expects($this->once())
            ->method('calculateProductOfferServicePointAvailabilities')
            ->willReturn([]);

        return $productOfferServicePointAvailabilityCalculatorStrategyPluginMock;
    }
}
