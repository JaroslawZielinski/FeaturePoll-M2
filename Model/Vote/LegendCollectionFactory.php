<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Vote;

use Magento\Backend\Model\Session as BackendSession;
use Magento\Framework\Data\Collection;
use Magento\Framework\Data\CollectionFactory;
use Magento\Framework\DataObject;

class LegendCollectionFactory
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var BackendSession
     */
    private $backendSession;

    /**
     * @inheritDoc
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        BackendSession $backendSession
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->backendSession = $backendSession;
    }

    public function create(string $featurePollId, string $emailSearch = ''): Collection
    {
        $legend = $this->backendSession
            ->getData(VoteResultsBarChartDataBuilder::BARCHART_VOTES_COLECTION_LEGEND_TAG);
        $dataSet = $legend[$featurePollId];
        $details = $dataSet['details'];
        $emails = $dataSet['emails'];
        $collection = $this->collectionFactory->create();
        for ($i = 0; $i < count($details); $i++) {
            $item = new DataObject([
                'detail' => $details[$i],
                'email' => $emails[$i]
            ]);
            $collection->addItem($item);
        }
        foreach ($collection as $key => $item) {
            if (!(empty($emailSearch) || str_contains($item->getEmail(), $emailSearch))) {
                $collection->removeItemByKey($key);
            }
        }
        return $collection;
    }
}
