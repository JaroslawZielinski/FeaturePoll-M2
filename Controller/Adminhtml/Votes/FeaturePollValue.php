<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Votes;

use JaroslawZielinski\FeaturePoll\Block\Adminhtml\Analytics\Reports\Grid\Results;
use JaroslawZielinski\FeaturePoll\Controller\Adminhtml\Ajax;
use JaroslawZielinski\FeaturePoll\Model\Vote\LegendCollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Block\Admin\Formkey;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Data\Collection;
use Magento\Framework\View\Result\PageFactory;

class FeaturePollValue extends Ajax
{
    /**
     * @var LegendCollectionFactory
     */
    private $legendCollectionFactory;

    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @inheritDoc
     */
    public function __construct(
        LegendCollectionFactory $legendCollectionFactory,
        PageFactory $pageFactory,
        Formkey $formKey,
        JsonFactory $resultJsonFactory,
        Context $context
    ) {
        $this->legendCollectionFactory = $legendCollectionFactory;
        $this->pageFactory = $pageFactory;
        parent::__construct($formKey, $resultJsonFactory, $context);
    }


    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $request = $this->getRequest();
        $params = $request->getParams();
        $featurePollId = $params['featurePollId'];
        // text
        $emailSearch = $params['email'] ?? '';
        $collection = $this->createCollection($featurePollId, $emailSearch);
        try {
            // text
            $curPage = $request->getParam('p');
            $curPage = empty($curPage) ? 1 : $curPage;
            // text
            $pageLimit = (int)($request->getParam('limiter') ?? 5);
            /** @var Results $results */
            $results = $resultPage->getLayout()
                ->getBlock('analytics.reports.grid.results')
                ->setPageSize($pageLimit)
                ->setCurPage($curPage)
                ->setEmailSearch($emailSearch)
                ->setCollection($collection);
            $resultHtml = $results->toHtml();
            $data['status'] = 'ok';
        } catch (\Exception $e) {
            $resultHtml = $e->getMessage();
            $data['status'] = 'error';
        }
        $data['result'] = $resultHtml;
        return $this->ajax($data);
    }

    /**
     * @throws \Exception
     */
    private function createCollection(string $featurePollId, string $emailSearch = ''): Collection
    {
        return $this->legendCollectionFactory->create($featurePollId, $emailSearch);
    }
}
