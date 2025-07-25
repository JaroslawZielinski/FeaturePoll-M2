<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Ui\Component\Listing\Column\Features;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Actions extends Column
{
    /**
     * Url path
     */
    public const FEATURES_URL_PATH_EDIT = 'jaroslawzielinski_featurepoll/features/edit';

    public const FEATURE_ID = 'feature_id';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var string
     */
    private $editUrl;

    /**
     * @inheritDoc
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        string $editUrl = self::FEATURES_URL_PATH_EDIT
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->editUrl = $editUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item[self::FEATURE_ID])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl($this->editUrl, [self::FEATURE_ID => $item[self::FEATURE_ID]]),
                        'label' => __('Edit')
                    ];
                }
            }
        }
        return $dataSource;
    }
}
