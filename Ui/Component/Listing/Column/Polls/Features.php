<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Ui\Component\Listing\Column\Polls;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use JaroslawZielinski\FeaturePoll\Api\FeatureRepositoryInterface;

class Features extends Column
{
    public const COLUMN_POLLS_FEATURES = 'features';

    /**
     * @var FeatureRepositoryInterface
     */
    private $featureRepository;

    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    /**
     * @inheritDoc
     */
    public function __construct(
        FeatureRepositoryInterface $featureRepository,
        JsonSerializer $jsonSerializer,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->featureRepository = $featureRepository;
        $this->jsonSerializer = $jsonSerializer;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item[self::COLUMN_POLLS_FEATURES])) {
                    $item[self::COLUMN_POLLS_FEATURES] = $this->getFeaturesHtml($item);
                }
            }
        }

        return $dataSource;
    }

    /**
     * @throws LocalizedException
     */
    private function getFeaturesHtml(array $item): string
    {
        $features = $item['features'] ?? [];
        $featuresArray = $this->jsonSerializer->unserialize($features);
        $htmlArray = [];
        foreach ($featuresArray as $i => $feature) {
            $featureId = $feature['feature'];
            $featureModel = $this->featureRepository->get($featureId);
            $featureGroupName = $featureModel->getGroupName();
            $featureDescription = $featureModel->getDescription();
            $featureHtml = sprintf('%s. %s', $i + 1, $featureGroupName);
            $htmlArray[] = sprintf(
                '<a href="javascript:void(0)" class="disabled-link" title="%s">%s</a>',
                $featureDescription,
                $featureHtml
            );
        }
        $count = count($htmlArray);
        $html = sprintf('%s', implode('<br />', $htmlArray));
        $html= rtrim($html,'<br />');
        return $count > 0 ? $html : 'none';
    }
}
