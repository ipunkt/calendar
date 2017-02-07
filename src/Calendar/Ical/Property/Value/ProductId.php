<?php

namespace Ipunkt\Calendar\Ical\Property\Value;

class ProductId extends Text
{
    /**
     * Set product id.
     *
     * @param  string $productId
     * @return self
     */
    public function setProductId($productId)
    {
        $this->text = (string)$productId;

        return $this;
    }

    /**
     * Get product id.
     *
     * @return string
     */
    public function getProductId()
    {
        return $this->text;
    }
}