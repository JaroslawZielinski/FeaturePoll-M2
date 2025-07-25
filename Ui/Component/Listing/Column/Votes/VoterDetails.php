<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Ui\Component\Listing\Column\Votes;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use JaroslawZielinski\FeaturePoll\Model\VoteManagement;

class VoterDetails extends Column
{
    public const COLUMN_VOTER_DETAILS = 'voter_details';

    public const COLUMN_VOTER_IP = 'voter_ip';

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @inheritDoc
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        UrlInterface $urlBuilder,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->customerRepository = $customerRepository;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }


    /**
     * {@inheritdoc}
     * @throws NoSuchEntityException
     * @throws LocalizedException
    */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            $storeId = $this->context->getFilterParam('store_id');

            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item[self::COLUMN_VOTER_DETAILS])) {
                    $item[self::COLUMN_VOTER_DETAILS] = $this->getVoterDetailsHtml($storeId, $item);
                }
            }
        }

        return $dataSource;
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    private function getCustomerHtmlDetails($storeId, int $customerId): string
    {
        $customer = $this->customerRepository->getById($customerId);
        $customerVisitCard = sprintf('%s %s', $customer->getFirstname(), $customer->getLastname());
        $customerEmail = $customer->getEmail();
        $customerUrl = $this->urlBuilder->getUrl(
            'customer/*/edit',
            ['id' => $customerId, 'store' => $storeId]
        );
        return <<<EOT
<a href="{$customerUrl}" target="_blank" title="{$customerEmail}" >{$customerVisitCard}</a>
EOT;
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    private function getVoterDetailsHtml($storeId, array $item): string
    {
        $voterIp = $item[self::COLUMN_VOTER_IP];
        if (VoteManagement::CUSTOMER_NON_GUEST === $voterIp) {
            $customerId = (int)$item[self::COLUMN_VOTER_DETAILS];
            return $this->getCustomerHtmlDetails($storeId, $customerId);
        }

        $voterDetails = $item[self::COLUMN_VOTER_DETAILS];
        $truncatedVoterDetails = strlen($voterDetails) > 50 ? substr($voterDetails, 0, 50) . '...' : $voterDetails;

        $voterDetails = sprintf(
            '%s <a href="#" onclick="showJsonModal(\'%s\'); return false;" style="color: #007bdb;">View Full</a>',
            htmlspecialchars($truncatedVoterDetails),
            htmlspecialchars(base64_encode($voterDetails)));

        return $voterDetails;
    }
}
