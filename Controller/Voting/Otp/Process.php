<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Voting\Otp;

use Magento\Framework\App\RequestInterface;

class Process extends \JaroslawZielinski\OTPComponent\Controller\Form\Otp\Process
{
    /**
     * @inheritDoc
     */
    protected function validationEventDispatch(RequestInterface $request): void
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
