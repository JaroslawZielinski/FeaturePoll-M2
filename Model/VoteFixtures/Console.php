<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\VoteFixtures;

use Faker\Factory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use JaroslawZielinski\FeaturePoll\Helper\Data;
use JaroslawZielinski\FeaturePoll\Model\Source\Type;
use JaroslawZielinski\FeaturePoll\Model\Data\Vote;
use JaroslawZielinski\FeaturePoll\Model\VoteManagement;
use JaroslawZielinski\FeaturePoll\ViewModel\Content as ContentView;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use JaroslawZielinski\FeaturePoll\Model\ResourceModel\Vote\CollectionFactory as VoteCollectionFactory;
use JaroslawZielinski\FeaturePoll\Api\VoterRepositoryInterface;

class Console
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var VoteCollectionFactory
     */
    private $voteCollectionFactory;

    /**
     * @var VoterRepositoryInterface
     */
    private $voterRepository;

    /**
     * @var CustomerCollectionFactory
     */
    private $customerCollectionFactory;

    /**
     * @var ContentView
     */
    private $contentView;

    /**
     * @var VoteManagement
     */
    private $voteManagement;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    /**
     * @var array
     */
    private $tempFakeCustomer;

    /**
     */
    public function __construct(
        LoggerInterface $logger,
        VoteCollectionFactory $voteCollectionFactory,
        VoterRepositoryInterface $voterRepository,
        CustomerCollectionFactory $customerCollectionFactory,
        CustomerRepositoryInterface $customerRepository,
        ContentView $contentView,
        VoteManagement $voteManagement,
        JsonSerializer $jsonSerializer
    ) {
        $this->logger = $logger;
        $this->voteCollectionFactory = $voteCollectionFactory;
        $this->voterRepository = $voterRepository;
        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->customerRepository = $customerRepository;
        $this->contentView = $contentView;
        $this->voteManagement = $voteManagement;
        $this->jsonSerializer = $jsonSerializer;
        $this->tempFakeCustomer = [];
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function execute(
        int      $pollId,
        int      $voteCount,
        bool     $ifClear = false,
        callable $start = null,
        callable $iteration = null,
        callable $end = null
    ): void {
        if (!empty($start)) {
            $start($voteCount);
        }
        if ($ifClear) {
            $votesCollection = $this->voteCollectionFactory->create();
            $votesCollection->addFieldToFilter('poll_id', ['eq' => $pollId]);
            $votes = $votesCollection->getItems();
            /** @var Vote $vote */
            foreach ($votes as $vote) {
                $voterId = $vote->getVoterId();
                try {
                    $this->voterRepository->deleteById((string)$voterId);
                } catch (NoSuchEntityException $e) {
                    continue;
                }
            }
        }
        $customerCollection = $this->customerCollectionFactory->create();
        $mySurveyArray = $this->contentView->getFeaturePoll((string)$pollId);
        $maxVotes = count($mySurveyArray['features']);
        $customers = $customerCollection->getAllIds();
        $customerIds = [];
        foreach ($customers as $customerId) {
            for ($i = 0; $i < $maxVotes; $i++) {
                $customerIds[] = $customerId;
            }
        }
        $chessBoard = $mySurveyArray['features'];
        $chessBoardPawn = 0;
        for ($i = 0; $i < $voteCount; $i++) {
            if ($i < count($customerIds)) {
                $customer = $this->createNonFakeCustomer((int)$customerIds[$i]);
            } else {
                if (0 === $chessBoardPawn) {
                    $this->tempFakeCustomer = $this->createFakeCustomer();
                }
                $customer = $this->tempFakeCustomer;
            }
            $feature = $chessBoard[$chessBoardPawn];
            $featureId = $feature['featureId'];
            $questions = $feature['questions'];
            $questionIdKey = rand(0, count($questions) - 1);
            $question = $questions[$questionIdKey];
            $questionId = $question['position'];
            $featurePollId = Data::implodeFeaturePollId($pollId, (int)$featureId, (int)$questionId);
            $type = (int)$question['type'];
            $featurePollValue = null;
            if (Type::TYPE_OPEN === $type) {
                $featurePollValue = $this->getFeaturePollValue();
            }
            try {
                $this->voteManagement->vote(
                    $customer['voterName'],
                    $customer['voterEmail'],
                    $featurePollId,
                    $featurePollValue,
                    $customer['voterIp'],
                    $customer['voterDetails'],
                    $customer['voterSurname'],
                    $suppressTimeBan = true
                );
            }
            catch (\Exception $e) {
                $this->logger->critical($e);
            }
            if ($chessBoardPawn === $maxVotes - 1) {
                $chessBoardPawn = 0;
            } else {
                $chessBoardPawn++;
            }
            if (!empty($iteration)) {
                $iteration();
            }
        }
        if (!empty($end)) {
            $end();
        }
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    private function createNonFakeCustomer(int $customerId): array
    {
        $customer = $this->customerRepository->getById($customerId);
        return [
            'voterName' => $customer->getFirstname(),
            'voterEmail' => $customer->getEmail(),
            'voterIp' => VoteManagement::CUSTOMER_NON_GUEST,
            'voterDetails' => $customer->getId(),
            'voterSurname' => $customer->getLastname(),
        ];
    }

    private function createFakeCustomer(): array
    {
        $faker = Factory::create();
        $fullName = explode(' ', $faker->name());
        $name = $fullName[0];
        $surname = rand(0, 1) ? $fullName[1] : null;
        $email = $faker->email();
        $realIp = $faker->localIpv4();
        return [
            'voterName' => $name,
            'voterEmail' => $email,
            'voterIp' => $realIp,
            'voterDetails' => $this->fakeDetails($realIp),
            'voterSurname' => $surname,
        ];
    }

    private function fakeDetails(string $realIp): string
    {
        $faker = Factory::create();
        $domain = $faker->domainName();
        $realIp2 = $faker->localIpv4();
        $urlPart = $faker->word();
        $array = [
            'Cookie' => 'XDEBUG_SESSION=YOUR-NAME; form_key=sQtH8A2LYMNJJiLS; PHPSESSID=8a4e2eaa5e669a3853d8bb6e9abe102b; mage-cache-storage=%7B%7D; mage-cache-storage-section-invalidation=%7B%7D; mage-cache-sessid=true; mage-messages=; recently_viewed_product=%7B%7D; recently_viewed_product_previous=%7B%7D; recently_compared_product=%7B%7D; recently_compared_product_previous=%7B%7D; product_data_storage=%7B%7D; section_data_ids=%7B%7D; private_content_version=84c38147b2dad6f21295c5845fb4cce5',
            'X-Varnish' => '98347',
            'X-Forwarded-For' => "{$realIp}, {$realIp2}",
            'X-Requested-With' => 'XMLHttpRequest',
            'X-Real-Ip' => $realIp,
            'X-Forwarded-Server' => '656535c2451a',
            'X-Forwarded-Proto' => 'https',
            'X-Forwarded-Port' => '443',
            'X-Forwarded-Host' => $domain,
            'Sec-Fetch-Site' => 'same-origin',
            'Sec-Fetch-Mode' => 'cors',
            'Sec-Fetch-Dest' => 'empty',
            'Sec-Ch-Ua-Platform' => '"Linux"',
            'Sec-Ch-Ua-Mobile' => '?0',
            'Sec-Ch-Ua' => '"Not)A;Brand";v="8", "Chromium";v="138", "Google Chrome";v="138"',
            'Referer' => "https://{$domain}/{$urlPart}",
            'Priority' => 'u=1, i',
            'Origin' => "https://{$domain}",
            'Content-Type' => 'multipart/form-data; boundary=----WebKitFormBoundarymgkwnWyvmZ07Bb7z',
            'Accept-Language' => 'pl, en-US;q=0.9, en;q=0.8, de;q=0.7',
            'Accept-Encoding' => 'gzip, deflate, br, zstd',
            'Accept' => '*/*',
            'Content-Length' => '3346',
            'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36',
            'Host' => $domain
        ];
        return $this->jsonSerializer->serialize($array);
    }

    private function getFeaturePollValue(): string
    {
        $faker = Factory::create();
        $nb = rand(1, 5);
        return $faker->paragraph($nbSentences = $nb);
    }
}
