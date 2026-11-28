<?php

namespace App\Http\Controllers;

use App\Services\ProductFilterService;
use App\Services\TechCornerService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TechCornerController extends Controller
{
    public function __construct(
        protected TechCornerService $techCornerService,
        protected ProductFilterService $filterService,
    ) {}

    /**
     * Trang danh sách bài viết Góc công nghệ.
     */
    public function index(Request $request): View
    {
        $topics = $this->techCornerService->topics();
        $activeTopic = 'all';

        if ($request->filled('topic')) {
            $rawTopic = (string) $request->query('topic');
            if (! preg_match('/^[a-zA-Z0-9\-_]+$/', $rawTopic)) {
                abort(400, 'Tham số chuyên mục không hợp lệ.');
            }

            if (! array_key_exists($rawTopic, $topics)) {
                abort(404, 'Chuyên mục Góc công nghệ không tồn tại.');
            }

            $activeTopic = $rawTopic;
        }

        return view('client.tech-corner.index', [
            'topics' => $topics,
            'activeTopic' => $activeTopic,
            'articles' => $this->techCornerService->byTopic($activeTopic),
        ]);
    }

    /**
     * Trang chi tiết bài viết Góc công nghệ.
     */
    public function show(string $slug): View
    {
        if (! preg_match('/^[a-zA-Z0-9\-_]+$/', $slug)) {
            abort(400, 'Đường dẫn bài viết không hợp lệ.');
        }

        $article = $this->techCornerService->findBySlug($slug);
        if ($article === null) {
            abort(404, 'Bài viết Góc công nghệ không tồn tại.');
        }

        $recommendedProducts = collect(
            $this->filterService->filter($article['recommended_filters'], perPage: 4)->items()
        );

        return view('client.tech-corner.show', [
            'article' => $article,
            'relatedArticles' => $this->techCornerService->related($slug),
            'recommendedProducts' => $recommendedProducts,
        ]);
    }
}
