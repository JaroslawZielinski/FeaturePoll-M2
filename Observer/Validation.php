<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Observer;


use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use JaroslawZielinski\OTPComponent\Model\Config;

class Validation implements ObserverInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    /**
     * @var string[]
     */
    private $errors;

    public function __construct(
        Config $config,
        JsonSerializer $jsonSerializer
    ) {
        $this->jsonSerializer = $jsonSerializer;
        $this->errors = [];
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     * @throws LocalizedException
     */
    public function execute(Observer $observer): void
    {
        $event = $observer->getEvent();
        /** @var Http $request */
        $request = $event->getRequest();
        $params = $request->getParams();
        if (!$this->isBackendValid($params)) {
            $errorsString = $this->jsonSerializer->serialize($this->errors);
            throw new LocalizedException(__($errorsString));
        }
    }

    /**
     */
    private function isBackendValid(array $params): bool
    {
        $this->errors = [];
        $formId = $params['form_id'] ?? null;
        if (!\Laminas\Validator\StaticValidator::execute(trim((string)$formId), 'NotEmpty')) {
            $this->errors[] = __('Form ID is required.');
        }
        if ($this->config->isNameEnabled() && $this->config->isNameRequired()) {
            $name = $params['name'] ?? null;
            if (!\Laminas\Validator\StaticValidator::execute(trim((string)$name), 'NotEmpty')) {
                $this->errors[] = __('Name is required.');
            }
        }
        if ($this->config->isSurnameEnabled() && $this->config->isSurnameRequired()) {
            $surname = $params['surname'] ?? null;
            if (!\Laminas\Validator\StaticValidator::execute(trim((string)$surname), 'NotEmpty')) {
                $this->errors[] = __('Surname is required.');
            }
        }
        $email = $params['email'] ?? null;
        if (!\Laminas\Validator\StaticValidator::execute(trim((string)$email), 'EmailAddress')) {
            $this->errors[] = __("Email '%1' of a voter is invalid.", $email);
        }
        $rulesAndRegulations = $params['rr'] ?? null;
        if (!\Laminas\Validator\StaticValidator::execute(trim((string)$rulesAndRegulations), 'NotEmpty')) {
            $this->errors[] = __('Rules and Regulations must be read and accepted.');
        }
        $otpNumber = $params['otp6_value'] ?? null;
        if (!empty($otpNumber) && 1 !== preg_match('/^\d{6}$/', $otpNumber)) {
            $this->errors[] = __('OTP Number must contain 6 digits only.');
        }
        return empty($this->errors);
    }
}
