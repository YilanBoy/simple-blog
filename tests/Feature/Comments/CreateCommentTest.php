<?php

use App\Livewire\Shared\Comments\CreateCommentModal;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

use function Pest\Faker\fake;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

test('non-logged-in users can leave a anonymous comment', function () {
    $post = Post::factory()->create();

    $body = fake()->words(5, true);

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->set('recaptcha', 'fake-g-recaptcha-response')
        ->call('store')
        ->assertDispatched('add-id-to-group-new')
        ->assertDispatched('close-create-comment-modal')
        ->assertDispatched('update-comment-counts')
        ->assertDispatched('info-badge',
            status: 'success',
            message: '成功新增留言！',
        );

    $this->assertDatabaseHas('comments', [
        'body' => $body,
    ]);

    get($post->link_with_slug)
        ->assertSee($body);
});

test('logged-in users can leave a comment', function () {
    $this->actingAs(User::factory()->create());

    $post = Post::factory()->create();

    $body = fake()->words(5, true);

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->set('recaptcha', 'fake-g-recaptcha-response')
        ->call('store')
        ->assertDispatched('add-id-to-group-new')
        ->assertDispatched('close-create-comment-modal')
        ->assertDispatched('update-comment-counts')
        ->assertDispatched('info-badge',
            status: 'success',
            message: '成功新增留言！',
        );

    $this->assertDatabaseHas('comments', [
        'body' => $body,
    ]);

    get($post->link_with_slug)
        ->assertSee($body);
});

test('if the comment addition fails, no data will be available in the database.', function () {
    // make App\Models\Comment::create() throw an exception
    // https://stackoverflow.com/questions/37456518/how-to-mock-static-methods-of-a-laravel-eloquent-model
    Mockery::mock('overload:'.Comment::class)
        ->shouldReceive('create')
        ->once()
        ->andThrow(new Exception('comment creating failed'));

    Log::shouldReceive('error')->once();

    $post = Post::factory()->create();

    $body = fake()->words(5, true);

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->set('recaptcha', 'fake-g-recaptcha-response')
        ->call('store')
        ->assertDispatched('close-create-comment-modal')
        ->assertDispatched('info-badge',
            status: 'danger',
            message: 'Oops！新增留言失敗！',
        );

    $this->assertDatabaseMissing('comments', [
        'body' => $body,
    ]);
})->skip(true, 'skip until pest support run in separate process');

it('can see the comment preview', function () {
    $this->actingAs(User::factory()->create());

    $post = Post::factory()->create();

    $body = <<<'MARKDOWN'
    # Title

    This is a **comment**

    Show a list

    - item 1
    - item 2
    - item 3
    MARKDOWN;

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->set('recaptcha', 'fake-g-recaptcha-response')
        ->set('convertToHtml', true)
        ->assertSeeHtmlInOrder([
            '<p>Title</p>',
            '<p>This is a <strong>comment</strong></p>',
            '<p>Show a list</p>',
            '<ul>',
            '<li>item 1</li>',
            '<li>item 2</li>',
            '<li>item 3</li>',
            '</ul>',
        ]);
});

test('when a new comment is added, the post comments will be increased by one', function () {
    $this->actingAs(User::factory()->create());

    $post = Post::factory()->create();

    $this->assertDatabaseHas('posts', ['comment_counts' => 0]);

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', 'Hello World!')
        ->set('recaptcha', 'fake-g-recaptcha-response')
        ->call('store');

    $this->assertDatabaseHas('posts', ['comment_counts' => 1]);
});
