<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Block\Adminhtml\System\Config\Poll\Field;

use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\BlockInterface;
use JaroslawZielinski\FeaturePoll\Block\Adminhtml\AbstractDraggableFieldArray;
use JaroslawZielinski\FeaturePoll\Block\Adminhtml\Feature\Field\Polls;

class Items extends AbstractDraggableFieldArray
{
    public const POLL_ITEM = 'item';

    public const ROUTE = 'route';

    /**
     * @var BlockInterface
     */
    private $surveyItemsRenderer;

    /**
     * @inheritdoc
     * @throws LocalizedException
     */
    protected function _prepareToRender(): void
    {
        $this->addColumn(self::POLL_ITEM, [
            'label' => 'Item',
            'renderer' => $this->getPollItemsRenderer()
        ]);

        $this->addColumn(self::ROUTE, [
            'label' => 'Route',
            'class' => 'required-entry',
            'style' => 'width: 320px'
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = 'Add Feature';
    }

    /**
     * @throws LocalizedException
     */
    private function getPollItemsRenderer(): BlockInterface
    {
        if (empty($this->surveyItemsRenderer)) {
            $this->surveyItemsRenderer = $this->getLayout()->createBlock(
                Polls::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->surveyItemsRenderer;
    }

    /**
     * {@inheritDoc}
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];
        $pollItems = $row->getData(self::POLL_ITEM);
        $options['option_' . $this->getPollItemsRenderer()->calcOptionHash($pollItems)] = 'selected="selected"';
        $row->setData('option_extra_attrs', $options);
    }

    /**
     * @inheritDoc
     */
    protected function getRowsHtmlId(): string
    {
        return 'row_jaroslawzielinski_featurepoll_polls_items';
    }
}
