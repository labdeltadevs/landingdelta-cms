<?php

use App\Models\News;
use App\Models\Product;

test('el sitemap responde 200 aunque haya productos sin timestamps', function () {
    $product = Product::factory()->create();
    Product::query()->whereKey($product->id)->update(['updated_at' => null]);

    $response = $this->get('/sitemap.xml');

    $response->assertOk();

    $xml = simplexml_load_string($response->getContent());

    expect($xml)->not->toBeFalse()
        ->and($response->getContent())->toContain(route('public.products.show', $product));
});

test('el sitemap responde 200 aunque haya noticias sin timestamps', function () {
    $news = News::factory()->create();
    News::query()->whereKey($news->id)->update(['updated_at' => null]);

    $response = $this->get('/sitemap.xml');

    $response->assertOk();

    expect(simplexml_load_string($response->getContent()))->not->toBeFalse();
});
