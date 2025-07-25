<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Observer;


use JaroslawZielinski\FeaturePoll\Helper\Data;
use Laminas\Http\Headers;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\View\LayoutInterface;
use Psr\Log\LoggerInterface;
use JaroslawZielinski\FeaturePoll\Block\Poll\Panel\Content\Feature\Results;
use JaroslawZielinski\FeaturePoll\Model\VoteManagement;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;

class Action implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var RemoteAddress
     */
    private $remoteAddress;

    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var LayoutInterface
     */
    private $layout;

    /**
     * @var VoteManagement
     */
    private $voteManagement;

    /**
     */
    public function __construct(
        LoggerInterface $logger,
        RemoteAddress $remoteAddress,
        JsonSerializer $jsonSerializer,
        RequestInterface $request,
        LayoutInterface $layout,
        VoteManagement $voteManagement
    ) {
        $this->logger = $logger;
        $this->remoteAddress = $remoteAddress;
        $this->jsonSerializer = $jsonSerializer;
        $this->request = $request;
        $this->layout = $layout;
        $this->voteManagement = $voteManagement;
    }

    /**
     * {@inheritDoc}
     * @throws LocalizedException
     * @throws \DateMalformedStringException
     */
    public function execute(Observer $observer): void
    {
        $event = $observer->getEvent();
        $tuple = $event->getTuple();
        $totals = $event->getTotals();
        $status = $totals['status'];
        $message = $totals['message'];
        $results = $totals['results'];
        // Save Voter and Vote
        $voterIp = $this->remoteAddress->getRemoteAddress();
        /** @var Headers $headers */
        $headers = $this->request->getHeaders();
        $voterDetails = $this->jsonSerializer->serialize($headers->toArray());
        $newResults = $this->voteManagement->vote(
            $tuple['name'],
            $tuple['email'],
            $tuple['featurepoll_id'],
            $tuple['featurepoll_value'] ?? null,
            $voterIp,
            $voterDetails,
            $tuple['surname']
        );
        $results2 = $this->getResultsHtml($newResults);
        $thankYouMsg = (string)__('Thank you for your precious vote!');
        $newTotals = [
            'status' => $status,
            'message' => $message,
            'results' => __('<div class="alert alert-success" role="alert">%1</div>', $thankYouMsg),
            'results2' => $results2,
            'ecommerce' => [
                'name' => $tuple['name'],
                'surname' => $tuple['surname'],
                'token' => Data::getTokenByEmail($tuple['email'])
            ]
        ];
        $this->logger->info('Frontend action peformed!', ['totals' => $newTotals]);
        $event->setTotals($newTotals);
    }

    protected function getResultsHtml(array $results): array
    {
        return array_map(function ($result) {
            return $this->layout
                ->createBlock(Results::class)
                ->setData([
                    'value' => $result[0],
                    'total' => $result[1]
                ])->toHtml();
        }, $results);
    }
}
