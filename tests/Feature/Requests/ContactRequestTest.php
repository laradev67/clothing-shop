<?php

namespace Tests\Feature\Requests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ConractRequestTest extends TestCase
{
    use DatabaseTransactions;

    private $form_data;
    private $admin;

    /**
     * @author Cho
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->admin = factory(User::class)->state('admin')->create();
        $this->form_data = [
            'phone' => '(095) 777-77-' . rand(10, 99),
            'icon' => rand(1, 10),
        ];
    }
    /**
     * @author Cho
     * @test
     */
    public function icon_is_not_required(): void
    {
        $this->makeRequest();

        $this->assertDatabaseHas('contacts', [
            'phone' => $this->form_data['phone'],
        ]);
    }

    /**
     * @author Cho
     * @test
     */
    public function icon_must_be_numeric(): void
    {
        $this->form_data['icon'] = 'text';

        $this->makeRequest();

        $this->assertDatabaseMissing('contacts', [
            'phone' => $this->form_data['phone'],
        ]);
    }

    /**
     * @author Cho
     * @test
     */
    public function icon_must_be_beetween_two_numbers(): void
    {
        $this->form_data['icon'] = config('valid.contact.icon.max') + 1;
        $this->makeRequest();
        $this->assertDatabaseMissing('contacts', [
            'phone' => $this->form_data['phone'],
        ]);
    }

    /**
     * @author Cho
     * @test
     */
    public function phone_must_have_min_length(): void
    {
        $phone_min = config('valid.contact.phone.min');

        $this->form_data['phone'] = str_random($phone_min - 1);
        $this->makeRequest();
        $this->assertDatabaseMissing('contacts', [
            'phone' => $this->form_data['phone'],
        ]);
    }

    /**
     * @author Cho
     * @test
     */
    public function phone_must_have_max_length(): void
    {
        $phone_max = config('valid.contact.phone.max');

        $this->form_data['phone'] = str_random($phone_max + 1);
        $this->makeRequest();
        $this->assertDatabaseMissing('contacts', [
            'phone' => $this->form_data['phone'],
        ]);
    }

    /**
     * @author Cho
     * @test
     */
    public function phone_is_required(): void
    {
        $this->form_data['phone'] = '';

        $this->makeRequest();
        $this->assertDatabaseMissing('contacts', [
            'phone' => $this->form_data['phone'],
        ]);
    }

    /**
     * Method helper
     *
     * @return void
     */
    public function makeRequest(): void
    {
        $this->actingAs($this->admin)
            ->post(action('Admin\ContactController@store'), $this->form_data);
    }
}
