<?php

namespace App\Http\Controllers;

use App\Models\FlexiblePage;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Response;

class PageController extends Controller
{
    public function __invoke(FlexiblePage $page)
    {
        if (! $page->isPublished()) {
            return abort(Response::HTTP_NOT_FOUND);
        }

        SEOTools::setTitle($page->getSEOTitle());
        SEOTools::setDescription($page->getSEODescription());
        SEOTools::jsonLd()->addImage($page->getSEOImageUrl());
        SEOTools::opengraph()->addImage($page->getSEOImageUrl());

        return view('flexible-pages.index', [
            'page' => $page,
            'title' => $page->title,
        ]);
    }
}
