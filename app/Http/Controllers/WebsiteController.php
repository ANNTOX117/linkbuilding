<?php

namespace App\Http\Controllers;

use App\Models\PagebuilderTranslation;
use Illuminate\Http\Request;
use PHPageBuilder\Theme;
use PHPageBuilder\Modules\GrapesJS\PageRenderer;
use PHPageBuilder\Repositories\PageRepository;

class WebsiteController extends Controller {

    public function uri(Request $request) {
        if(empty($request->uri)) {
            $request->uri = '/';
        }

        $url = $request->uri;
        $config = config('pagebuilder.theme');
        $config['active_theme'] = site_theme();
        $theme  = new Theme($config, site_theme());
        $pageId = PagebuilderTranslation::select('page_id')->where('route', '/'.$url)->first();
        if(empty($pageId)){
            return abort(404);
        }
        $page   = (new PageRepository)->findWithId($pageId->page_id);
        $pageRenderer = new PageRenderer($theme, $page);
        return $pageRenderer->render();
    }
}
