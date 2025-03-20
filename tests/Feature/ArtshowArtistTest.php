<?php

namespace Tests\Feature;

use Tests\TestCase;

class ArtshowArtistTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/artshow');

        $response->assertStatus(302);
    }
}
