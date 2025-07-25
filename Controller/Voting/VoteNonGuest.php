<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Voting;


use JaroslawZielinski\OTPComponent\Controller\Form\Otp;
use Laminas\Validator\StaticValidator;
use JaroslawZielinski\FeaturePoll\Block\Poll\Panel\Content\Feature\Results;
use JaroslawZielinski\FeaturePoll\Model\Config;
use JaroslawZielinski\FeaturePoll\Model\VoteManagement;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory as ResultJsonFactory;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Encryption\Helper\Security;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Psr\Log\LoggerInterface;
use JaroslawZielinski\FeaturePoll\ViewModel\Content as ContentView;

class VoteNonGuest extends Action
{
    /**
     * @var ContentView
     */
    protected $contentView;

    /**
     * @var JsonSerializer
     */
    protected $jsonSerializer;

    /**
     * @var VoteManagement
     */
    protected $voteManagement;

    /**
     * @var ResultJsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var FormKey
     */
    protected $formKey;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Phrase[]
     */
    protected $errors;

    /**
     * @inheritDoc
     */
    public function __construct(
        ContentView $contentView,
        JsonSerializer $jsonSerializer,
        VoteManagement $voteManagement,
        ResultJsonFactory $resultJsonFactory,
        Config $config,
        FormKey $formKey,
        LoggerInterface $logger,
        Context $context
    ) {
        $this->contentView = $contentView;
        $this->jsonSerializer = $jsonSerializer;
        $this->voteManagement = $voteManagement;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->config = $config;
        $this->formKey = $formKey;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     * @throws NoSuchEntityException|LocalizedException
     */
    public function execute()
    {
        $request = $this->getRequest();
        $params = $request->getParams();
        $customer =  $this->contentView->getCustomer();
        $customerDataModel = $customer->getDataModel();
        $params['name'] = $customerDataModel->getFirstName();
        $params['surname'] = $customerDataModel->getLastName();
        $params['email'] = $customerDataModel->getEmail();
        $params['customerId'] = $customerDataModel->getId();
        $params['rr'] = 1;
        try {
            if (false === $request->isXmlHttpRequest()) {
                throw new LocalizedException(__('It is not an ajax call.'));
            }
            $formKey = $request->getParam('form_key', null);
            if (false === ($formKey && Security::compareStrings($formKey, $this->formKey->getFormKey()))) {
                throw new LocalizedException(__('Possibly it is a CSRF attack.'));
            }
            if (!$this->isBackendValid($params)) {
                $errorsString = $this->jsonSerializer->serialize($this->errors);
                throw new LocalizedException(__($errorsString));
            }
            $temporaryVote = [
                'featurepoll_id' => $params['featurepoll_id'],
                'featurepoll_value' => $params['featurepoll_value'],
                'name' => $params['name'],
                'surname' => $params['surname'],
                'email' => $params['email'],
                'customerId' => $params['customerId']
            ];
            $newResults = $this->voteManagement->vote(
                $temporaryVote['name'],
                $temporaryVote['email'],
                $temporaryVote['featurepoll_id'],
                $temporaryVote['featurepoll_value'],
                VoteManagement::CUSTOMER_NON_GUEST,
                $temporaryVote['customerId'],
                $temporaryVote['surname']
            );
            $thankYouMsg = (string)__('Thank you for your precious vote!');
            $totals = [
                'status' => Otp::STATUS_OK,
                'message' => $thankYouMsg,
                'results' => __('<div class="alert alert-success" role="alert">%1</div>',
                    $thankYouMsg),
                'results2' => $this->getResultsHtml($newResults),
                'ecommerce' => [
                    'name' => $temporaryVote['name'],
                    'surname' => $temporaryVote['surname'],
                    'customerId' => $temporaryVote['customerId']
                ]
            ];
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            $totals = [
                'status' => Otp::STATUS_FAILED,
                'message' => __('Error happened [%1]', $e->getMessage()),
                'results' => __('<div class="alert alert-danger" role="alert">%1</div>', $e->getMessage()),
                'results2' => [],
                'ecommerce' => [
                    'name' => $temporaryVote['name'],
                    'surname' => $temporaryVote['surname'],
                    'customerId' => $temporaryVote['customerId']
                ]
            ];
        }
        $result = $this
            ->resultJsonFactory
            ->create()
            ->setHeader('X-Robots-Tag', 'noindex,nofollow');
        return $result->setData($totals);
    }

    protected function getResultsHtml(array $results): array
    {
        return array_map(function ($result) {
            return $this->_view->getLayout()
                ->createBlock(Results::class)
                ->setData([
                    'value' => $result[0],
                    'total' => $result[1]
                ])->toHtml();
        }, $results);
    }

    protected function isBackendValid(array $params): bool
    {
        $this->errors = [];
        $featurePollId = $params['featurepoll_id'] ?? null;
        if (!StaticValidator::execute(trim((string)$featurePollId), 'NotEmpty')) {
            $this->errors[] = __('Feature you vote for is required.');
        }
        $name = $params['name'] ?? null;
        if (!StaticValidator::execute(trim((string)$name), 'NotEmpty')) {
            $this->errors[] = __('Name is required.');
        }
        $email = $params['email'] ?? null;
        if (!StaticValidator::execute(trim((string)$email), 'EmailAddress')) {
            $this->errors[] = __("Email '%1' of a voter is invalid.", $email);
        }
        $rulesAndRegulations = $params['rr'] ?? null;
        if (!StaticValidator::execute(trim((string)$rulesAndRegulations), 'NotEmpty')) {
            $this->errors[] = __('Rules and Regulations must be read and accepted.');
        }
        return empty($this->errors);
    }
}
