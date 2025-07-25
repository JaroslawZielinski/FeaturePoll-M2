<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Block\Poll\Panel\Content;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use JaroslawZielinski\FeaturePoll\Helper\Data;
use Magento\Framework\Data\Form\FormKey;

/**
 * @method getPollId(): int
 * @method setPollId(int $pollId): self
 * @method getTab(): array
 * @method setTab(array $tab): self
 */
class Feature extends Template
{
    public const STANDARD_DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var FormKey
     */
    private $formKey;

    /**
     * @inheritDoc
     */
    public function __construct(
        FormKey $formKey,
        Context $context,
        array $data = []
    ) {
        $this->formKey = $formKey;
        parent::__construct($context, $data);
    }

    /**
     * @throws LocalizedException
     */
    public function getFormKey(): string
    {
        return $this->formKey->getFormKey();
    }

    /**
     */
    public function getRequired(bool $isRequired = true, string $requiredMessage = 'This is a required field.'): string
    {
        return Data::getRequired($isRequired, $requiredMessage);
    }

    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        parent::_construct();

        $this->setTemplate('JaroslawZielinski_FeaturePoll::poll/panel/content/feature.phtml');
    }
}
