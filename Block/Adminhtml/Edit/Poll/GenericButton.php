<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Block\Adminhtml\Edit\Poll;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use JaroslawZielinski\FeaturePoll\Api\PollRepositoryInterface;

class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var PollRepositoryInterface
     */
    protected $pollRepository;

    /**
     */
    public function __construct(
        Context $context,
        PollRepositoryInterface $pollRepository
    ) {
        $this->context = $context;
        $this->pollRepository = $pollRepository;
    }

    public function getPollId(): ?int
    {
        try {
            return (int)$this->pollRepository->get(
                (int)$this->context->getRequest()->getParam('poll_id')
            )->getPollId();
        } catch (NoSuchEntityException|LocalizedException $e) {
        }
        return null;
    }

    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
