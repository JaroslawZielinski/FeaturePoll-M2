<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Config\Source\Features;

use JaroslawZielinski\FeaturePoll\Model\Poll;
use JaroslawZielinski\FeaturePoll\Model\Data\Poll as DataPoll;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Poll\CollectionFactory;
use Magento\Framework\Option\ArrayInterface;

class Polls implements ArrayInterface
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var array
     */
    protected $options;

    /**
     */
    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function toOptionArray(): ?array
    {
        if (empty($this->options)) {
            $options = [];
            $optionsCollection = $this->collectionFactory->create()->load();
            /** @var Poll $poll */
            foreach ($optionsCollection as $poll) {
                /** @var DataPoll $pollData */
                $pollData = $poll->getDataModel();
                $options[] = [
                    'value' => $pollData->getPollId(),
                    'label' => __($pollData->getDescription())
                ];
            }
            $this->options = $options;
        }
        return $this->options;
    }
}
