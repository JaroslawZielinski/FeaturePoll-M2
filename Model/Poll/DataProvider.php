<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Poll;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Ui\DataProvider\AbstractDataProvider;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Poll\CollectionFactory;
use JaroslawZielinski\FeaturePoll\Model\Poll;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var array
     */
    private $loadedData;

    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @see https://magento.stackexchange.com/questions/352671/magento-2-4-constructing-controller-url-adminbackend-in-javascript#answer-352675
     *
     * @inheritDoc
     */
    public function __construct(
        JsonSerializer $jsonSerializer,
        CollectionFactory $groupCollectionFactory,
        DataPersistorInterface $dataPersistor,
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->jsonSerializer = $jsonSerializer;
        $this->collection = $groupCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $polls = $this->collection->getItems();
        /** @var $polls Poll */
        foreach ($polls as $poll) {
            $features = $poll->getFeatures() ?? '{}';
            $featuresArray = $this->jsonSerializer->unserialize($features);
            $this->loadedData[$poll->getId()] =
                array_merge($poll->getData(), ['dynamic_rows_container' => $featuresArray]);
        }
        /** @var Poll $poll */
        $poll = $this->dataPersistor->get('feature_poll_polls');
        if (!empty($poll)) {
            $features = $poll->getFeatures() ?? '{}';
            $featuresArray = $this->jsonSerializer->unserialize($features);
            $this->loadedData[$poll->getPollId()] =
                array_merge($poll->getData(), ['dynamic_rows_container' => $featuresArray]);
            $this->dataPersistor->clear('feature_poll_polls');
        }
        return $this->loadedData;
    }
}
