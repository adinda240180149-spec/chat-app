<?php

test('halaman landing page dapat dimuat dengan sukses', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Mengobrol Kapan Saja');
    $response->assertSee('WebSocket Real-Time');
});
