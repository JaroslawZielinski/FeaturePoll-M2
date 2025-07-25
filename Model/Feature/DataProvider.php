<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Feature;

use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Feature\CollectionFactory;
use JaroslawZielinski\FeaturePoll\Model\Feature;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;

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
        $features = $this->collection->getItems();
        /** @var $features Feature */
        foreach ($features as $feature) {
            $questions = $feature->getQuestions() ?? '{}';
            $questionsArray = $this->jsonSerializer->unserialize($questions);
            $this->loadedData[$feature->getId()] =
                array_merge($feature->getData(), ['dynamic_rows_container' => $questionsArray]);
        }
        /** @var Feature $feature */
        $feature = $this->dataPersistor->get('feature_poll_features');
        if (!empty($feature)) {
            $questions = $feature->getQuestions() ?? '{}';
            $questionsArray = $this->jsonSerializer->unserialize($questions);
            $this->loadedData[$feature->getFeatureId()] =
                array_merge($feature->getData(), ['dynamic_rows_container' => $questionsArray]);
            $this->dataPersistor->clear('feature_poll_features');
        }
        return $this->loadedData;
    }
}
