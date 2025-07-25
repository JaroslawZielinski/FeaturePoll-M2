<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Voting;

use JaroslawZielinski\FeaturePoll\Model\MyFormFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory as ResultJsonFactory;


class Reset extends Action
{
    /**
     * @var MyFormFactory
     */
    private $formFactory;

    /**
     * @var ResultJsonFactory
     */
    private $resultJsonFactory;

    /**
     * @inheritDoc
     */
    public function __construct(
        MyFormFactory $formFactory,
        ResultJsonFactory $resultJsonFactory,
        Context $context
    ) {
        $this->formFactory = $formFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $request = $this->getRequest();
        $params = $request->getParams();
        $params['form_id'] = sprintf('form-%s', $params['featurepoll_id']);
        $formBlock = $this->formFactory->create(
            $this->_view->getLayout(),
            array_merge($params, [
                'submit_url' => '/featurepoll/voting_otp/preprocess',
                'disable_fields' => false,
                'show_codes' => false
            ]),
            [],
            true,
            false
        );
        $totals = [
            'status' => 'OK',
            'message' => __('Thank you for your precious vote!'),
            'results' => $formBlock->toHtml(),
            'results2' => []
        ];
        $result = $this
            ->resultJsonFactory
            ->create()
            ->setHeader('X-Robots-Tag', 'noindex,nofollow');
        return $result->setData($totals);
    }
}
