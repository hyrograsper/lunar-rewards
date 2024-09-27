<?php

it('can apply fixed amount rewards', function () {
    $cartLine = \Lunar\Models\CartLine::factory(1);
    $cart = $cartLine->cart;

    $this->assertNotNull($cart);

});
