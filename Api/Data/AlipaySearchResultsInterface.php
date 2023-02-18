<?php
declare(strict_types=1);

namespace Deloitte\Alipay\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface AlipaySearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get list
     *
     * @return \Deloitte\Alipay\Api\Data\AlipayInterface[]
     */
    public function getItems();

    /**
     * Set list
     *
     * @param \Deloitte\Alipay\Api\Data\AlipayInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
