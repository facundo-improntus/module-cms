<?php
/**
 * Copyright © PassKeeper, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace PassKeeper\Cms\Setup\Patch\Data;

use PassKeeper\Cms\Setup\ContentConverter;
use PassKeeper\Framework\App\ResourceConnection;
use PassKeeper\Framework\DB\Select\QueryModifierFactory;
use PassKeeper\Framework\Setup\ModuleDataSetupInterface;
use PassKeeper\Framework\Setup\Patch\DataPatchInterface;
use PassKeeper\Framework\Setup\Patch\PatchVersionInterface;
use PassKeeper\Framework\DB\AggregatedFieldDataConverter;
use PassKeeper\Framework\DB\FieldToConvert;
use PassKeeper\Framework\EntityManager\MetadataPool;
use PassKeeper\Widget\Setup\LayoutUpdateConverter;
use PassKeeper\Cms\Api\Data\BlockInterface;
use PassKeeper\Cms\Api\Data\PageInterface;

/**
 * Class ConvertWidgetConditionsToJson
 * @package PassKeeper\Cms\Setup\Patch
 */
class ConvertWidgetConditionsToJson implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var QueryModifierFactory
     */
    private $queryModifierFactory;

    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @var AggregatedFieldDataConverter
     */
    private $aggregatedFieldDataConverter;

    /**
     * ConvertWidgetConditionsToJson constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param QueryModifierFactory $queryModifierFactory
     * @param MetadataPool $metadataPool
     * @param AggregatedFieldDataConverter $aggregatedFieldDataConverter
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        QueryModifierFactory $queryModifierFactory,
        MetadataPool $metadataPool,
        AggregatedFieldDataConverter $aggregatedFieldDataConverter
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->queryModifierFactory = $queryModifierFactory;
        $this->metadataPool = $metadataPool;
        $this->aggregatedFieldDataConverter = $aggregatedFieldDataConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $queryModifier = $this->queryModifierFactory->create(
            'like',
            [
                'values' => [
                    'content' => '%conditions_encoded%'
                ]
            ]
        );
        $layoutUpdateXmlFieldQueryModifier = $this->queryModifierFactory->create(
            'like',
            [
                'values' => [
                    'layout_update_xml' => '%conditions_encoded%'
                ]
            ]
        );
        $customLayoutUpdateXmlFieldQueryModifier = $this->queryModifierFactory->create(
            'like',
            [
                'values' => [
                    'custom_layout_update_xml' => '%conditions_encoded%'
                ]
            ]
        );
        $blockMetadata = $this->metadataPool->getMetadata(BlockInterface::class);
        $pageMetadata = $this->metadataPool->getMetadata(PageInterface::class);
        $this->aggregatedFieldDataConverter->convert(
            [
                new FieldToConvert(
                    ContentConverter::class,
                    $this->moduleDataSetup->getTable('cms_block'),
                    $blockMetadata->getIdentifierField(),
                    'content',
                    $queryModifier
                ),
                new FieldToConvert(
                    ContentConverter::class,
                    $this->moduleDataSetup->getTable('cms_page'),
                    $pageMetadata->getIdentifierField(),
                    'content',
                    $queryModifier
                ),
                new FieldToConvert(
                    LayoutUpdateConverter::class,
                    $this->moduleDataSetup->getTable('cms_page'),
                    $pageMetadata->getIdentifierField(),
                    'layout_update_xml',
                    $layoutUpdateXmlFieldQueryModifier
                ),
                new FieldToConvert(
                    LayoutUpdateConverter::class,
                    $this->moduleDataSetup->getTable('cms_page'),
                    $pageMetadata->getIdentifierField(),
                    'custom_layout_update_xml',
                    $customLayoutUpdateXmlFieldQueryModifier
                ),
            ],
            $this->moduleDataSetup->getConnection()
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [
            UpdatePrivacyPolicyPage::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '2.0.2';
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
