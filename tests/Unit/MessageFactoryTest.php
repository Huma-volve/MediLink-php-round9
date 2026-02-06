<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class MessageFactoryTest extends TestCase
{
     use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_create_message(): void
    {
        $this->assertTrue(true);
    }
}
