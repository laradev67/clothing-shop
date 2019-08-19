<?php

namespace Tests\Feature\Views\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoginPageTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @author Cho
     * @test
     */
    public function page_is_accessible_by_guest(): void
    {
        $this->get('/' . config('custom.enter_slug') . '/login')
            ->assertOk()
            ->assertViewIs('auth.login');
    }

    /**
     * @author Cho
     * @test
     */
    public function page_is_not_accessible_by_auth(): void
    {
        $this->actingAs(factory(User::class)->create())
            ->get('/' . config('custom.enter_slug') . '/login')
            ->assertRedirect();
    }
}
