<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductPriceApiTest extends TestCase
{
    use RefreshDatabase;

    private Currency $usd;
    private Currency $cop;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::factory()->create());

        $this->usd = Currency::factory()->create(['name' => 'USD', 'exchange_rate' => 1]);
        $this->cop = Currency::factory()->create(['name' => 'COP', 'exchange_rate' => 4000]);
        $this->product = Product::factory()->create(['price' => 10, 'currency_id' => $this->usd->id]);
    }

    public function test_registra_precio_explicito(): void
    {
        $this->postJson("/api/products/{$this->product->id}/prices", [
            'currency_id' => $this->cop->id,
            'price' => 39000,
        ])->assertCreated()->assertJsonPath('data.price', '39000.00');
    }

    public function test_calcula_precio_con_la_tasa_de_cambio(): void
    {
        $this->postJson("/api/products/{$this->product->id}/prices", [
            'currency_id' => $this->cop->id,
        ])->assertCreated()->assertJsonPath('data.price', '40000.00');
    }

    public function test_no_permite_precio_duplicado_en_la_misma_divisa(): void
    {
        $url = "/api/products/{$this->product->id}/prices";
        $this->postJson($url, ['currency_id' => $this->cop->id])->assertCreated();

        $this->postJson($url, ['currency_id' => $this->cop->id])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['currency_id']);
    }

    public function test_lista_precios_del_producto(): void
    {
        $this->postJson("/api/products/{$this->product->id}/prices", ['currency_id' => $this->cop->id]);

        $this->getJson("/api/products/{$this->product->id}/prices")
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }
}
