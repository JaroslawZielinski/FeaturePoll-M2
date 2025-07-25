<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Ui\Component\Listing\Column\Voters;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use JaroslawZielinski\FeaturePoll\Helper\Data;

class Origin extends Column
{
    public const COLUMN_TOKEN = 'token';

    public const COLUMN_ORIGIN = 'origin';

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
                if (isset($item[self::COLUMN_ORIGIN])) {
                    $item[self::COLUMN_ORIGIN] = $this->getVoterDetailsHtml($storeId, $item);
                }
            }
        }

        return $dataSource;
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    private function getCustomerHtmlDetails($storeId, string $email): string
    {
        $customer = $this->customerRepository->get($email);
        $customerVisitCard = (string)__('Customer');
        $customerUrl = $this->urlBuilder->getUrl(
            'customer/*/edit',
            ['id' => $customer->getId(), 'store' => $storeId]
        );
        return <<<EOT
<a href="{$customerUrl}" target="_blank">{$customerVisitCard}</a>
EOT;
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    private function getVoterDetailsHtml($storeId, array $item): string
    {
        $origin = $item[self::COLUMN_ORIGIN];
        if ($origin) {
            $token = strip_tags($item[self::COLUMN_TOKEN]);
            $email = Data::getEmailByToken($token);
            return $this->getCustomerHtmlDetails($storeId, $email);
        } else {
            return (string)__('Guest Customer');
        }
    }
}
