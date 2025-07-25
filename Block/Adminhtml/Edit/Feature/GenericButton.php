<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Block\Adminhtml\Edit\Feature;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use JaroslawZielinski\FeaturePoll\Api\FeatureRepositoryInterface;

class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var FeatureRepositoryInterface
     */
    protected $featureRepository;

    /**
     */
    public function __construct(
        Context $context,
        FeatureRepositoryInterface $featureRepository
    ) {
        $this->context = $context;
        $this->featureRepository = $featureRepository;
    }

    public function getFeatureId(): ?int
    {
        try {
            return (int)$this->featureRepository->get(
                (int)$this->context->getRequest()->getParam('feature_id')
            )->getFeatureId();
        } catch (NoSuchEntityException|LocalizedException $e) {
        }
        return null;
    }

    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
