<?php

namespace App\Http\Controllers;

use App\Repositories\PageRepository;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $pageTitle;
    protected $metaDescription;
    protected $metaKeywords;
    protected $pageData;

    protected $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageTitle = 'Construction T-shirt';
        $this->metaDescription = 'Default description for my website.';
        $this->metaKeywords = 'default, keywords, website';
        $this->pageData = 'default, data, website';



        view()->share([
            'pageTitle' => $this->pageTitle,
            'metaDescription' => $this->metaDescription,
            'metaKeywords' => $this->metaKeywords,
            'pageData' => $this->pageData
        ]);
    }
}
