<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Block\Adminhtml\System\Config\Poll\Field;

use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\BlockInterface;
use JaroslawZielinski\FeaturePoll\Block\Adminhtml\AbstractDraggableFieldArray;
use JaroslawZielinski\FeaturePoll\Block\Adminhtml\Feature\Field\Polls;
use JaroslawZielinski\FeaturePoll\Block\Adminhtml\System\Config\Column\CustomMultiselect;

class Items extends AbstractDraggableFieldArray
{
    public const ROUTES_WIDTH = '320px';

    public const POLL_ITEM = 'item';

    public const ROUTES = 'routes';

    /**
     * @var BlockInterface
     */
    private $pollItemsRenderer;

    /**
     * @var BlockInterface
     */
    private $multiselectRenderer;

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

        $this->addColumn(self::ROUTES, [
            'label' => 'Routes',
            'renderer' => $this->getMultiselectRenderer()
                ->setExtraParams('multiple="multiple" style="width: ' . self::ROUTES_WIDTH . ';height: 300px;"')
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = 'Add Feature';
    }

    /**
     * @throws LocalizedException
     */
    private function getPollItemsRenderer(): BlockInterface
    {
        if (empty($this->pollItemsRenderer)) {
            $this->pollItemsRenderer = $this->getLayout()->createBlock(
                Polls::class,
                '',
                [
                    'data' => [
                        'is_render_to_js_template' => true,
                        'class' => 'required-entry'
                    ]
                ]
            );
        }
        return $this->pollItemsRenderer;
    }

    /**
     * Get the multiselect renderer
     */
    private function getMultiselectRenderer()
    {
        if (empty($this->multiselectRenderer)) {
            $this->multiselectRenderer = $this->getLayout()->createBlock(
                CustomMultiselect::class,
                '',
                [
                    'data' => [
                        'is_render_to_js_template' => true,
                        'class' => 'required-entry'
                    ]
                ]
            );
        }
        return $this->multiselectRenderer;
    }

    /**
     * {@inheritDoc}
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];
        $pollItems = $row->getData(self::POLL_ITEM);
        $routes = $row->getData(self::ROUTES) ?? [];
        $options['option_' . $this->getPollItemsRenderer()->calcOptionHash($pollItems)] = 'selected="selected"';
        if (count($routes) > 0) {
            foreach ($routes as $route) {
                $options['option_' . $this->getMultiselectRenderer()->calcOptionHash($route)] = 'selected="selected"';
            }
        }
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
