<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Plugin\Model;

class FormFactoryPlugin extends \JaroslawZielinski\OTPComponent\Plugin\Model\FormFactoryPlugin
{
    /**
     * @inheritDoc
     */
    protected function getAllowedRoutes(): array
    {
        return [
            'jaroslawzielinski_featurepoll_voting_otp_preprocess',
            'jaroslawzielinski_featurepoll_voting_otp_process'
        ];
    }
}
