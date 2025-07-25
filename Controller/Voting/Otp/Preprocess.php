<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Voting\Otp;

class Preprocess extends \JaroslawZielinski\OTPComponent\Controller\Form\Otp\Preprocess
{
    /**
     * @inheritDoc
     */
    protected function getSubmitUrl(): string
    {
        return '/featurepoll/voting_otp/process';
    }

    /**
     * @inheritDoc
     */
    protected function validationEventDispatch(\Magento\Framework\App\RequestInterface $request): void
    {
        $this->eventManager->dispatch('featurepoll_controller_validation', [
            'request' => $request
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function processAuthenticatedEventDispatch(array $tuple, array &$totals): void
    {
        $this->eventManager->dispatch('featurepoll_controller_authenticated', [
            'tuple' => $tuple,
            'totals' => &$totals
        ]);
    }
}
