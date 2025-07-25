<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Block\Adminhtml\Analytics\Reports\Grid;

use Magento\Backend\Block\Template;
use Magento\Framework\Data\Collection;


/**
 * @see https://magento.stackexchange.com/questions/177465/why-is-setpagesize-and-setcurpage-functions-not-working-with-the-collecti#answer-315902
 *
 * @method getCurPage(): int
 * @method setCurPage(int $curPage): self
 * @method getPageSize(): int
 * @method setPageSize(int $pageSize): self
 * @method getEmailSearch(): string
 * @method setEmailSearch(string $emailSearch): self
 * @method getCollection(): Collection
 * @method setCollection(Collection $collection): self
 */
class Results extends Template
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->setTemplate('JaroslawZielinski_FeaturePoll::analytics/reports/grid/results.phtml');
        parent::_construct();
    }

    /**
     */
    public function getFilteredCollection(string $emailSearch): Collection
    {
        $page = $this->getCurPage();
        $pageSize = $this->getPageSize();
        /** @var Collection $collection */
        $collection = clone $this->getCollection();
        foreach ($collection as $key => $item) {
            if (!(empty($emailSearch) || str_contains($item->getEmail(), $emailSearch))) {
                $collection->removeItemByKey($key);
            }
        }
        $collection->setPageSize($pageSize);
        if ($page > $collection->getLastPageNumber()) {
            $page = $collection->getLastPageNumber();
        }
        $currentPage = $page;
        $firstItem = (($currentPage - 1) * $pageSize + 1);
        $lastItem = $firstItem + ($pageSize - 1);
        $iterator = 1;
        foreach ($collection as $key => $item) {
            $pos = $iterator;
            $iterator++;
            if ($pos >= $firstItem && $pos <= $lastItem) {
                continue;
            }
            $collection->removeItemByKey($key);
        }
        return $collection;
    }
}
