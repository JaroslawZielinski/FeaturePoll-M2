<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Emails;

use Magento\Framework\App\Area;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Sender
{
    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var StateInterface
     */
    private $inlineTranslation;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        StateInterface $state,
        LoggerInterface $logger
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $state;
        $this->logger = $logger;
    }

    /**
     */
    public function send(
        array $templateVars,
        ?string $templateId,
        ?string $fromEmail,
        ?string $fromName,
        ?string $toEmail,
        ?string $toEmailName,
        ?array $cc = null,
        ?array $bcc = null
    ): bool {
        try {
            if (empty($templateId)) {
                throw new LocalizedException(__('TemplateID is required.'));
            }
            /** @var StoreInterface|Store $store */
            $store = $this->storeManager->getStore();
            $storeCode = $store->getCode();
            $storeId = $store->getId();
            // restore store_id for cron process purposes
            if ('admin' === $storeCode) {
                $storeId = $templateVars['store_id'] ?? $storeId;
            }
            $from = ['email' => $fromEmail, 'name' => $fromName];
            $this->inlineTranslation->suspend();
            $templateOptions = [
                'area' => Area::AREA_FRONTEND,
                'store' => (int)$storeId
            ];
            $transportBuilder = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars($templateVars)
                ->setFrom($from)
                ->addTo($toEmail, $toEmailName);
            if (!empty($cc)) {
                $transportBuilder->addCc($cc);
            }
            if (!empty($bcc)) {
                $transportBuilder->addBcc($bcc);
            }
            $transport = $transportBuilder
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return false;
        }
        return true;
    }
}
