<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPageBuilder\Theme;
use PHPageBuilder\Modules\GrapesJS\PageRenderer;
use PHPageBuilder\Repositories\PageRepository;

class HomeController extends Controller {

    public function build($pageId = 2) {
        $config = config('pagebuilder.theme');
        $config['active_theme'] = site_theme();
        $theme  = new Theme($config, site_theme());
        $page   = (new PageRepository)->findWithId($pageId);
        $pageRenderer = new PageRenderer($theme, $page);
        return $pageRenderer->render();

        // $route  = $_GET['route'] ?? null;
        // $action = $_GET['action'] ?? null;
        // $pageId = is_numeric($pageId) ? $pageId : ($_GET['page'] ?? null);
        // $pageRepository = new \PHPageBuilder\Repositories\PageRepository;
        // $page = $pageRepository->findWithId($pageId);

        // $phpPageBuilder = app()->make('phpPageBuilder');
        // $pageBuilder = $phpPageBuilder->getPageBuilder();

        // $customScripts = view("pagebuilder.scripts")->render();
        // $pageBuilder->customScripts('head', $customScripts);

        // if ($page) {
        //     $renderedContent = $pageBuilder->renderPage($page, 'en');
        //     echo $renderedContent;
        //     return true;
        // }

        // $pageBuilder->handleRequest($route, $action, $page);
    }

}
