<?php

namespace App\Http\Controllers;

use App\Models\TranslatableFlexiblePage;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Response;

class TranslatablePageController extends Controller
{
    public function __invoke(TranslatableFlexiblePage $page)
    {
        if (! $page->isPublished()) {
            return abort(Response::HTTP_NOT_FOUND);
        }

        SEOTools::setTitle($page->getSEOTitle());
        SEOTools::setDescription($page->getSEODescription());
        SEOTools::jsonLd()->addImage($page->getSEOImageUrl());
        SEOTools::opengraph()->addImage($page->getSEOImageUrl());
        SEOMeta::setKeywords($page->seo_keywords);

        return view('flexible-pages.index', [
            'page' => $page,
            'title' => $page->title,
        ]);
    }
}
