<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    private function autenticar(): void
    {
        Sanctum::actingAs(User::factory()->create());
    }

    public function test_rechaza_peticiones_sin_token(): void
    {
        $this->getJson('/api/products')->assertUnauthorized();
    }

    public function test_lista_productos_paginados(): void
    {
        $this->autenticar();
        Product::factory()->count(3)->create();

        $this->getJson('/api/products')
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure(['data' => [['id', 'name', 'price', 'currency']], 'links', 'meta']);
    }

    public function test_crea_un_producto(): void
    {
        $this->autenticar();
        $currency = Currency::factory()->create();

        $this->postJson('/api/products', [
            'name' => 'Teclado',
            'description' => 'Teclado mecánico',
            'price' => 120.50,
            'currency_id' => $currency->id,
            'tax_cost' => 22.90,
            'manufacturing_cost' => 60,
        ])->assertCreated()->assertJsonPath('data.name', 'Teclado');

        $this->assertDatabaseHas('products', ['name' => 'Teclado']);
    }

    public function test_valida_campos_obligatorios(): void
    {
        $this->autenticar();

        $this->postJson('/api/products', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'price', 'currency_id', 'tax_cost', 'manufacturing_cost']);
    }

    public function test_muestra_un_producto(): void
    {
        $this->autenticar();
        $product = Product::factory()->create();

        $this->getJson("/api/products/{$product->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $product->id);
    }

    public function test_devuelve_404_si_no_existe(): void
    {
        $this->autenticar();

        $this->getJson('/api/products/999')
            ->assertNotFound()
            ->assertJson(['message' => 'Recurso no encontrado.']);
    }

    public function test_actualiza_un_producto(): void
    {
        $this->autenticar();
        $product = Product::factory()->create();

        $this->putJson("/api/products/{$product->id}", ['name' => 'Nuevo nombre'])
            ->assertOk()
            ->assertJsonPath('data.name', 'Nuevo nombre');
    }

    public function test_elimina_un_producto(): void
    {
        $this->autenticar();
        $product = Product::factory()->create();

        $this->deleteJson("/api/products/{$product->id}")->assertNoContent();
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }
}
