<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_guest_is_redirected_from_protected_pages(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $this->get('/')
            ->assertRedirect(route('login'));

        $this->post(route('notif.send', 1))
            ->assertRedirect(route('login'));
    }

    public function test_login_page_is_accessible(): void
    {
        $this->get(route('login'))
            ->assertOk();
    }
}
