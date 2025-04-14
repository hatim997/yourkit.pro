<?php

namespace App\Repositories;

use App\Models\Faq;
use App\Models\Page;

class PageRepository extends BaseRepository
{

    public function __construct(Page $model)
    {
        parent::__construct($model);
    }

    public function getPageContent(string $id)
    {
        return Page::with('content')->where('id', $id)->first();
    }

    public function updatePage(string $id, array $data)
    {

        $page = $this->findOrFail($id);

        // dd($data);

        if ($page->content) {
            $page->content->update([
                'description' => $data['description']
            ]);
        } else {
            // Create content if it doesn't exist
            $page->content()->create([
                'description' => $data['description']
            ]);
        }

        
        return $page;
    }

    public function getPageContentByTag(string $tag)
    {
        return Page::with('content')->where('slug', $tag)->first();
    }
}
