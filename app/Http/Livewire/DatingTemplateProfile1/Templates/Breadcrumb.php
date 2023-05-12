<?php

namespace App\Http\Livewire\DatingTemplateProfile1\Templates;

use App\Models\Article;
use App\Models\Banner;
use App\Models\Category;
use GuzzleHttp\Psr7\Request;
use Livewire\Component;

class Breadcrumb extends Component
{
    public $path_and_route;
    public function mount()
    {
        $current_url = url()->current();
        if(!isset(parse_url($current_url)["path"])){
            $this->path_and_route[] = array("path"=>"home","route"=>"home");
        }else{
            $path = parse_url($current_url)["path"];
            $path_separeted = array_filter(explode("/",$path));
            foreach ($path_separeted as $path) {
                switch (strtolower($path)) {
                    case 'ads':
                        $this->path_and_route[] = array("path"=>"home","route"=>"home");
                        $this->path_and_route[] = array("path"=>"ads","route"=>"ads");
                        break;
                    case 'advertenties':
                        $this->path_and_route[] = array("path"=>"home","route"=>"home");
                        $this->path_and_route[] = array("path"=>"Alle advertenties","route"=>"ads");
                        break;
                    case 'blogs':
                        $this->path_and_route[] = array("path"=>"home","route"=>"home");
                        $this->path_and_route[] = array("path"=>"blogs","route"=>"blog");
                        break;
                    case 'regions':
                        $this->path_and_route[] = array("path"=>"home","route"=>"home");
                        $this->path_and_route[] = array("path"=>"regions","route"=>"regions");
                        break;
                    case 'categories':
                        $this->path_and_route[] = array("path"=>"home","route"=>"home");
                        $this->path_and_route[] = array("path"=>"categories","route"=>"all-categories");
                        break;
                    case 'categorieen':
                        $this->path_and_route[] = array("path"=>"home","route"=>"home");
                        $this->path_and_route[] = array("path"=>"categorieen","route"=>"all-categories");
                        break;
                    case 'profiles':
                        $this->path_and_route[] = array("path"=>"home","route"=>"home");
                        $this->path_and_route[] = array("path"=>"ads","route"=>"ads");
                        break;
                    case 'profielen':
                        $this->path_and_route[] = array("path"=>"home","route"=>"home");
                        $this->path_and_route[] = array("path"=>"alle advertenties","route"=>"ads");
                        break;
                    case 'blog':
                        $this->path_and_route[] = array("path"=>"home","route"=>"home");
                        $this->path_and_route[] = array("path"=>"blogs","route"=>"blog");
                        break;
                    case 'category':
                        $this->path_and_route[] = array("path"=>"home","route"=>"home");
                        $this->path_and_route[] = array("path"=>"categories","route"=>"all-categories");
                        break;
                    case 'find-a-date':
                        $this->path_and_route[] = array("path"=>"home","route"=>"home");
                        $this->path_and_route[] = array("path"=>"regions","route"=>"regions");
                        break;
                    case 'vind-een-date':
                        $this->path_and_route[] = array("path"=>"home","route"=>"home");
                        $this->path_and_route[] = array("path"=>"regions","route"=>"regions");
                        break;
                    case 'category-by-city':
                        $this->path_and_route[] = array("path"=>"home","route"=>"home");
                        $this->path_and_route[] = array("path"=>"categories","route"=>"all-categories");
                        break;
                    case 'categorie':
                        $this->path_and_route[] = array("path"=>"home","route"=>"home");
                        $this->path_and_route[] = array("path"=>"categories","route"=>"all-categories");
                        break;
                        
                    default:
                        if (preg_match("/^([a-z]+-\d+)$/",$path)) {
                            $path_exploded = explode("-",$path);
                            $category = Category::where("url",$path_exploded[0])->where("id",$path_exploded[1])->first();
                            if(isset($category)){
                                $this->path_and_route[] = array("path"=>"home","route"=>"home");
                                $this->path_and_route[] = array("path"=>"categories","route"=>"all-categories");
                                $this->path_and_route[] = array("path"=>$path_exploded[0],"route"=>"category","params_route"=>["url"=>$path_exploded[0]]);
                                break;
                            }
                        }
                        $this->path_and_route[] = array("path"=>$path,"route"=>"home");
                    break;
                }
            }
        }
    }
    public function render()
    {
        return view('linksbuildingNew.livewire.dating-template-profile1.templates.breadcrumb');
    }
}
