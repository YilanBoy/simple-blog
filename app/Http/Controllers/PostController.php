<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Services\PostService;
use App\Services\FormatTransferService;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Route;

class PostController extends Controller
{
    public function __construct(
        protected PostService           $postService,
        protected FormatTransferService $formatTransferService
    )
    {
    }

    /**
     * 文章首頁
     *
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {
        $pageTitle = (Route::currentRouteName() === 'root')
            ? '生活記錄函式'
            : '所有文章';

        return view('posts.index', compact('pageTitle'));
    }

    /**
     * 文章內容
     *
     * @param Request $request
     * @param Post $post
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function show(Request $request, Post $post): Application|Factory|View|RedirectResponse|Redirector
    {
        // URL 修正，使用帶 slug 的網址
        if ($post->slug && $post->slug !== $request->slug) {
            return redirect($post->link_with_slug, 301);
        }

        return view('posts.show', ['post' => $post]);
    }

    /**
     * 新增文章
     *
     * @param PostRequest $request
     * @param Post $post
     * @return Application|RedirectResponse|Redirector
     */
    public function store(PostRequest $request, Post $post): Application|RedirectResponse|Redirector
    {
        $post->fill($request->validated());
        $post->user_id = auth()->id();
        $post->slug = $this->postService->makeSlug($request->title);
        // XSS 過濾
        $post->body = $this->postService->htmlPurifier($request->body);
        // 生成摘錄
        $post->excerpt = $this->postService->makeExcerpt($post->body);
        $post->save();

        // 將傳過來的 JSON 資料轉成 array
        $tagIdsArray = $this->formatTransferService
            ->tagsJsonToTagIdsArray($request->tags);

        // 在關聯表新增關聯
        $post->tags()->attach($tagIdsArray);

        return redirect($post->link_with_slug)->with('success', '成功新增文章！');
    }

    /**
     * 文章編輯頁面
     *
     * @param Post $post
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function edit(Post $post): View|Factory|Application
    {
        // 只能編輯自己發佈的文章，規則寫在 PostPolicy
        $this->authorize('update', $post);

        return view('posts.edit', ['post' => $post]);
    }

    /**
     * 更新文章
     *
     * @param PostRequest $request
     * @param Post $post
     * @return Application|RedirectResponse|Redirector
     * @throws AuthorizationException
     */
    public function update(PostRequest $request, Post $post): Application|RedirectResponse|Redirector
    {
        $this->authorize('update', $post);

        $post->fill($request->validated());
        $post->slug = $this->postService->makeSlug($request->title);
        $post->body = $this->postService->htmlPurifier($request->body);
        $post->excerpt = $this->postService->makeExcerpt($post->body);
        $post->save();

        $tagIdsArray = $this->formatTransferService
            ->tagsJsonToTagIdsArray($request->tags);

        // 關聯表更新
        $post->tags()->sync($tagIdsArray);

        return redirect($post->link_with_slug)->with('success', '成功更新文章！');
    }

    /**
     * 軟刪除文章
     *
     * @param Post $post
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function softDelete(Post $post): RedirectResponse
    {
        $this->authorize('destroy', $post);

        $post->delete();

        return redirect()
            ->route('users.index', ['user' => auth()->id()])
            ->with('success', '成功標記文章為刪除狀態！');
    }

    /**
     * 恢復軟刪除的文章
     *
     * @param int $id 文章的 ID
     * @return Application|RedirectResponse|Redirector
     * @throws AuthorizationException
     */
    public function restore(int $id): Application|RedirectResponse|Redirector
    {
        $softDeletedPost = Post::withTrashed()->find($id);

        $this->authorize('update', $softDeletedPost);

        $softDeletedPost->restore();

        return redirect($softDeletedPost->link_with_slug)
            ->with('success', '成功恢復文章！');
    }

    /**
     * 完全刪除文章
     *
     * @param int $id 文章的 ID
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(int $id): RedirectResponse
    {
        $softDeletedPost = Post::withTrashed()->find($id);

        $this->authorize('destroy', $softDeletedPost);

        $softDeletedPost->forceDelete();

        return redirect()
            ->route('users.index', ['user' => auth()->id()])
            ->with('success', '成功刪除文章！');
    }
}
