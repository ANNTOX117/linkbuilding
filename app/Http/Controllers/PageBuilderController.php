<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPageBuilder\Theme;
use PHPageBuilder\Modules\GrapesJS\PageRenderer;
use PHPageBuilder\Repositories\PageRepository;

class PageBuilderController extends Controller {

    public function build($pageId = null, $option = null) {
        $route  = $_GET['route'] ?? null;
        $option  = $_GET['option'] ?? null;
        $action = $_GET['action'] ?? null;

        if($option){
            $config = config('pagebuilder.theme');
            $config['active_theme'] = site_theme();
            $theme  = new Theme($config, site_theme());
            $page   = (new PageRepository)->findWithId($pageId);
            $pageRenderer = new PageRenderer($theme, $page);
            return $pageRenderer->render();
        } else {
            $pageId = is_numeric($pageId) ? $pageId : ($_GET['page'] ?? null);
            $pageRepository = new PageRepository;
            $page = $pageRepository->findWithId($pageId);

            $phpPageBuilder = app()->make('phpPageBuilder');
            $pageBuilder = $phpPageBuilder->getPageBuilder();
            $customScripts = view("pagebuilder.scripts")->render();

            $pageBuilder->customScripts('head', $customScripts);
            $pageBuilder->handleRequest($route, $action, $page);
        }

        // if ($option) {
        //     $renderedContent = $pageBuilder->renderPage($page);
        //     echo $renderedContent;
        //     return true;
        // }
    }

}
