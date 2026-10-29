<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductPrice;

class ProductPriceService
{
    /**
     * Registra el precio de un producto en una divisa.
     * Si no se envía el precio, se calcula desde el precio base con las tasas de cambio.
     */
    public function store(Product $product, array $data): ProductPrice
    {
        $currency = Currency::findOrFail($data['currency_id']);
        $price = $data['price'] ?? $this->convert($product, $currency);

        return $product->prices()
            ->create(['currency_id' => $currency->id, 'price' => $price])
            ->load('currency');
    }

    /**
     * Convierte el precio base del producto a la divisa destino.
     */
    public function convert(Product $product, Currency $target): float
    {
        $base = $product->currency;

        return round((float)$product->price / (float)$base->exchange_rate * (float)$target->exchange_rate, 2);
    }
}
