<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Block\Adminhtml\Feature\Field;

use JaroslawZielinski\FeaturePoll\Model\Data\Poll;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Poll\CollectionFactory;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;

class Polls extends Select
{
    /**
     * @var array
     */
    private $optionsArray;

    /**
     * @var CollectionFactory
     */
    private $pollCollectionFactory;

    /**
     * @inheritDoc
     */
    public function __construct(
        CollectionFactory $pollCollectionFactory,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->optionsArray = [];
        $this->pollCollectionFactory = $pollCollectionFactory;
    }

    public function setInputName(string $value): self
    {
        return $this->setName($value);
    }

    public function setInputId(string $value): self
    {
        return $this->setId($value);
    }

    /**
     * @inheritDoc
     */
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $options[] = [
                'value' => '',
                'label' => __('Choose Item')
            ];
            $polls = $this->pollCollectionFactory->create();
            /** @var Poll $poll */
            foreach ($polls as $poll) {
                $options[] = ['value' => $poll->getPollId(), 'label' => $poll->getGroupName()];
            }
            $this->setOptions($options);
        }
        return parent::_toHtml();
    }
}
