<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Article;
use App\Models\Link;
use Carbon\Carbon;


class Links extends Component {

	use WithPagination;

	public $sortBy = 'authority_sites.url';

	public $sortDirection = 'asc';
	public $perPage = 10;
	public $search = '';

	public $title;
	public $tab = 'articles';
	public $menu;

	public $expired_all = false;
	public $expired_selected = [];
	public $actionexpired = '';
    public $thefollow = 1;
	public $followexpired = 1;

	public $about_all = false;
	public $about_selected = [];
	public $actionabout = '';
	public $followabout = 1;

	public $link_all = false;
	public $link_selected = [];
	public $actionlink = '';
	public $followlink = 1;
    public $opentab;

	public $article_all = false;
	public $article_selected = [];
	public $actionarticle = '';
    public $openarticle;

	protected $paginationTheme = 'bootstrap';

	public function tab($tab) {
		$this->tab = $tab;
		$this->sortBy = 'authority_sites.url';
		$this->sortDirection = 'asc';
		$this->perPage = 10;
		$this->search = '';

        $this->resetPage();
	}

	public function mount() {
        $this->opentab = request()->tab;
		$this->title   = trans('My links');
		$this->menu    = 'My links';
	}

	public function sortBy($field){
		if ($this->sortDirection == 'asc') {
			$this->sortDirection = 'desc';
		} else {
			$this->sortDirection = 'asc';
		}
		return $this->sortBy = $field;
	}

	public function updatingSearch(){
		$this->resetPage();
	}

	public function updatedactionlink($option){
        if($option == 'follow'){
			if ($this->thefollow == 1) {
                Link::query()->whereIn('id', $this->link_selected)->update(['follow' => 1]);
                $this->thefollow = 2;
            } else {
                Link::query()->whereIn('id', $this->link_selected)->update(['follow' => null]);
                $this->thefollow = 1;
            }
		}

		if($option == 'renewal') {
            $products = implode(',', $this->link_selected); // count($this->link_selected) > 1 ? $this->link_selected->toArray() : $this->link_selected
            return redirect()->route('customer_renewal', ['article' => 'false', 'p' => base64_encode($products)]);
		}

		$this->link_selected = '';
	}

	public function selectalllink(){
		if (!$this->link_all) {
			$this->link_selected = Link::mylinks()->pluck('id');
			$this->link_selected = $this->link_selected->toArray();
			$this->link_all = true;
		}
		else{
			$this->link_selected = [];
			$this->link_all = false;
		}
	}

	public function updatedactionabout($option){
        if($option == 'follow'){

			if ($this->followexpired == 1) {
				Link::query()->whereIn('id', $this->about_selected)->update(['follow' => 1]);
				$this->followexpired = 2;
			} else {
				Link::query()->whereIn('id', $this->about_selected)->update(['follow' => null]);
				$this->followexpired = 1;
			}
		}

        if($option == 'renew') {
            $products = implode(',', $this->about_selected);
            return redirect()->route('customer_renewal', ['article' => 'false', 'p' => base64_encode($products)]);
        }

		$this->about_selected = '';
	}

	public function about_all(){
		if (!$this->about_all) {
			$this->about_selected = Link::mylinksabout()->pluck('id');
			$this->about_selected = $this->about_selected->toArray();
			$this->about_all = true;
		}
		else{
			$this->about_selected = [];
			$this->about_all = false;
		}
	}

	public function updatedactionexpired($option){
        if($option == 'follow'){

			if ($this->followabout == 1) {
				Link::query()->whereIn('id', $this->expired_selected)->update(['follow' => 1]);
				$this->followabout = 2;
			} else {
				Link::query()->whereIn('id', $this->expired_selected)->update(['follow' => null]);
				$this->followabout = 1;
			}
		}

        if($option == 'renew') {
            $products = implode(',', $this->expired_selected);
            return redirect()->route('customer_renewal', ['article' => 'false', 'p' => base64_encode($products)]);
        }

		$this->about_selected = '';
	}

	public function selectallexpired(){
		if (!$this->expired_all) {
			$this->expired_selected = Link::myliksexpired()->pluck('id');
			$this->expired_selected = $this->expired_selected->toArray();
			$this->expired_all = true;
		}
		else{
			$this->expired_selected = [];
			$this->expired_all = false;
		}
	}

	public function selectallarticles(){
		if (!$this->article_all) {
			$this->article_selected = Article::Myarticle()->pluck('id');
			$this->article_selected = $this->article_selected->toArray();
			$this->article_all = true;
		}
		else{
			$this->article_selected = [];
			$this->article_all = false;
		}
	}

	public function updatedactionarticle($option){
		if($option == 'renewal') {
            $products = implode(',', $this->article_selected); // count($this->link_selected) > 1 ? $this->link_selected->toArray() : $this->link_selected
            return redirect()->route('customer_renewal', ['article' => 'true', 'p' => base64_encode($products)]);
		}

		$this->article_selected = '';
	}

	public function render() {

        if(!empty($this->opentab)) {
            self::triggerTab();
        }

		$link_link = [];
		$article_link = [];
		$about_link = [];
		$expired_link = [];

		switch ($this->tab) {
			case 'articles':
					$article_link = Article::myarticle()
						->filter($this->search)
						->orderBy($this->sortBy, $this->sortDirection)
						->paginate($this->perPage);
				break;
			case 'activelinks':
					$link_link = Link::mylinks()
						->filter($this->search)
						->orderBy($this->sortBy, $this->sortDirection)
						->paginate($this->perPage);
				break;
			case 'aboutlinks':
					$about_link = Link::mylinksabout()
						->filter($this->search)
						->orderBy($this->sortBy, $this->sortDirection)
						->paginate($this->perPage);
				break;
			case 'expired':
					$expired_link = Link::myliksexpired()
						->filter($this->search)
						->orderBy($this->sortBy, $this->sortDirection)
						->paginate($this->perPage);
				break;
		}

		return view('livewire.account.links', [
			'link_link' 	=> $link_link,
			'article_link' 	=> $article_link,
			'about_link' 	=> $about_link,
			'expired_link' 	=> $expired_link,
			'all_article' 	=> Article::myarticle()->get()->count(),
			'all_link' 		=> Link::mylinks()->get()->count(),
			'all_about' 	=> Link::mylinksabout()->get()->count(),
			'all_expired' 	=> Link::myliksexpired()->get()->count()

		])->layout('layouts.account', ['title' => $this->title, 'menu' => $this->menu]);
	}

    public function triggerTab() {
        if($this->opentab == 2) {
            self::tab('activelinks');
        }

        if($this->opentab == 3) {
            self::tab('aboutlinks');
        }

        if($this->opentab == 4) {
            self::tab('expired');
        }

        $this->opentab = '';
    }

}
