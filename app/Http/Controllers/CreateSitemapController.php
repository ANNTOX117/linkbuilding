<?php

namespace App\Http\Controllers;
use App\Models\Article;
use App\Models\City;
use App\Models\Province;
use App\Models\SeoPage;
use Illuminate\Http\Request;
use SimpleXMLElement;
class CreateSitemapController //extends Controller
{
    public function index(Request $request, string $segment)
    {
        $allDataBysegment = $this->getDataBySegment($segment);
        $base_url = $request->getSchemeAndHttpHost();
        //$base_url = "https://www.bullsandhornsmedia.com";
        if ($segment === "blogs") $segment="blog";
        $url_by_controller = $base_url."/";
        if($segment !== "seo-pages")$url_by_controller .= $segment."/";
        $xml = new SimpleXMLElement('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');
        foreach ($allDataBysegment as $data) {
            $this->generateUrlXML($url_by_controller,$data,$xml);
        }
        return response($xml->asXML(), 200)->header('Content-Type', 'text/xml');
    }

    private function generateUrlXML(string $url_by_controller,Object $data,SimpleXMLElement $xml)
    {
        $url = $xml->addChild("url");
        $url->addChild('loc', $url_by_controller.$data->url);
        $url->addChild('lastmod', date('Y-m-dTH:i:sP', strtotime($data->created_at)));
        $url->addChild('changefreq', "weekly");
        $url->addChild('priority', "0.9");
    }

    private function getDataBySegment(string $segment)
    {
        switch (strtolower($segment)) {
            case 'profiles':
                return Article::getAllProfileUrl();
                break;
            case 'regions':
                return Province::getAllProvincesUrl();
                break;
            case 'blogs':
                return Article::getAllBlogsUrl();
                break;
            case 'seo-pages':
                return SeoPage::getAllSeoPagesUrl();
                break;
            case 'find-a-date':
                return City::getAllCitiesUrl();
                break;
            
            default:
                # code...
                break;
        }
    }
}