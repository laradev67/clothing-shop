<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Mockery;
use Tests\TestCase;
use App\Models\Visitor;

class HelpersFunctionsTest extends TestCase
{
    /** @test */
    public function active_if_route_is_helper_returns_active_string(): void
    {
        $this->get('/');
        $this->assertEquals('active', active_if_route_is('/'));

        $this->get('/items?category=women');
        $this->assertEquals('active', active_if_route_is('items', 'category', 'women'));

        $this->get('/items?category=men');
        $this->assertEquals('active', active_if_route_is('items', 'category', 'men'));
    }

    /** @test */
    public function forget_all_cache_helper_removes_all_cache_that_are_listed_in_cache_config(): void
    {
        cache()->put('home_cards', ['nice'], 5);
        cache()->put('orders', ['nice'], 5);

        forget_all_cache();

        $this->assertFalse(cache()->has('home_cards'));
        $this->assertFalse(cache()->has('orders'));
    }

    /** @test */
    public function visitor_id_function_returns_correct_data(): void
    {
        $expected = factory(Visitor::class)->create()->value('id');
        $this->assertEquals($expected, visitor_id());
    }

    /** @test */
    public function no_connection_error_function_flashes_message_and_logs_given_error(): void
    {
        $exception = Mockery::mock('Exception');
        $exception->shouldReceive('getMessage')->andReturn('Lorem');

        no_connection_error($exception, 'SomeFile');

        $this->assertFileExists(storage_path('logs/laravel-' . date('Y-m-d') . '.log'));
        $this->get('/')->assertSessionHas('error', trans('messages.query_error'));

        File::delete(storage_path('logs/laravel-' . date('Y-m-d') . '.log'));
    }

    /** @test */
    public function get_current_category_returns_string_women_if_request_url_contains_women_word(): void
    {
        $this->assertSame('women', get_current_category('http://some-url/nice/women/'));
        $this->assertSame('women', get_current_category('http://some-url/women/nice/'));
        $this->assertSame('women', get_current_category('https://women/nice/hello/'));
        $this->assertSame('women', get_current_category('https://nicewomen/nice/hello/'));
    }

    /** @test */
    public function get_current_category_returns_string_men_if_request_url_contains_men_word(): void
    {
        $this->assertSame('men', get_current_category('http://some-url/nice/men/'));
        $this->assertSame('men', get_current_category('http://some-url/men/nice/'));
        $this->assertSame('men', get_current_category('https://men/nice/hello/'));
        $this->assertSame('men', get_current_category('https://nicemen/nice/hello/'));
    }

    /** @test */
    public function get_current_category_returns_null_if_request_url_is_not_containing_men_or_women(): void
    {
        $this->assertNull(get_current_category('http://some-url/nice/meddnnn/'));
        $this->assertNull(get_current_category('http://some-url/nicmnen/nwoice/'));
    }

    /** @test */
    public function get_current_title_returns_headline_for_women_if_request_url_contains_women_word(): void
    {
        $this->assertSame(trans('items.women_items'), get_current_title('http://some-url/nice/women/'));
        $this->assertSame(trans('items.women_items'), get_current_title('http://some-url/women/nice/'));
        $this->assertSame(trans('items.women_items'), get_current_title('https://women/nice/hello/'));
        $this->assertSame(trans('items.women_items'), get_current_title('https://nicewomen/nice/hello/'));
    }

    /** @test */
    public function get_current_title_returns_headline_for_men_if_request_url_contains_men_word(): void
    {
        $this->assertSame(trans('items.men_items'), get_current_title('http://some-url/nice/men/'));
        $this->assertSame(trans('items.men_items'), get_current_title('http://some-url/men/nice/'));
        $this->assertSame(trans('items.men_items'), get_current_title('https://men/nice/hello/'));
        $this->assertSame(trans('items.men_items'), get_current_title('https://nicemen/nice/hello/'));
    }

    /** @test */
    public function get_current_title_returns_headline_for_both_genders_if_no_gender_specified(): void
    {
        $this->assertSame(trans('items.all_items'), get_current_title('http://duckduckgo.com/'));
        $this->assertSame(trans('items.all_items'), get_current_title('http://duckduckgo.com/nice/search'));
        $this->assertSame(trans('items.all_items'), get_current_title('http://duckduckgo.com/nice/all'));
    }

    /** @test */
    public function get_file_name_returns_string(): void
    {
        $this->assertTrue(is_string(get_file_name('Nice', 'png')));
    }

    /** @test */
    public function get_file_name_returns_image_name_slug_based_on_given_params(): void
    {
        $this->assertTrue((bool) preg_match('/nice-[\w]{7}+.png/', get_file_name('Nice', 'png')));
        $this->assertTrue((bool) preg_match('/cool-dd-[\w]{7}+.jpg/', get_file_name('CooL dd', 'jpg')));
        $this->assertTrue((bool) preg_match('/hello-man-[\w]{7}+.png/', get_file_name('Hello man', 'png')));
    }

    /** @test */
    public function string_random_returns_string(): void
    {
        $this->assertTrue(is_string(string_random()));
    }

    /** @test */
    public function string_random_returns_string_with_given_chars_length_if_you_pass_the_number(): void
    {
        $this->assertEquals(10, strlen(string_random(10)));
        $this->assertEquals(0, strlen(string_random(0)));
        $this->assertEquals(33, strlen(string_random(33)));
        $this->assertEquals(12, strlen(string_random(12)));
    }
}
