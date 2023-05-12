<?php

namespace App\Http\Livewire\Admin;

use App\Models\AuthoritySite;
use Livewire\Component;
use App\Models\Link;
use App\Models\Article;
use Livewire\WithPagination;
use Carbon\Carbon;

class Approvements extends Component
{

	use WithPagination;

	public $title;
	public $section = 'payments';

	public $column_links  = 'links.created_at';
	public $column_articles  = 'articles.created_at';

	public $sort    = 'desc';
	public $confirm;
	public $confirmApprove;
	public $table_approve;

	public $tab = 'links';

    public $pagination;
    public $search = '';

	protected $paginationTheme = 'bootstrap';

	public function table($table) {
        $this->search     = '';
        $this->pagination = env('APP_PAGINATE');

        $this->tab = $table;
        $this->resetPage();
    }

	public function mount() {
        if(!permission('approvals', 'read')) {
            abort(404);
        }

		$this->title      = trans('Approvals');
        $this->pagination = env('APP_PAGINATE');
	}

	public function sort($table, $column) {
		$this->sort   = ($this->sort == 'asc') ? 'desc' : 'asc';
		$this->column_links = ($table == 'links') ? $column : 'links.created_at';
		$this->column_articles = ($table == 'articles') ? $column : 'articles.created_at';
	}

	public function confirmApprove($table, $id) {
		$this->table_approve = $table;
		$this->confirmApprove = $id;
		$this->dispatchBrowserEvent('confirmApprove');
	}

	public function approve() {
		$new_data = [];

		if ($this->table_approve == 'links') {
			$link         = Link::findOrFail($this->confirmApprove);
            $authority    = AuthoritySite::find($link->authority_site);
            $active       = null;
            $published_at = null;

            if(!empty($authority->wordpress)) {
                //
            } else {
                $active = 1;
                $published_at = Carbon::now();
            }

			if($link->visible_at <= Carbon::today()) {
				$detail   = json_decode($link->orders->details);
                $years    = self::get_years($detail);
				$tomorrow = Carbon::tomorrow();
				$new_expired_date = Carbon::tomorrow()->addYear($years);

				$new_data = array(
					'active'        => $active,
					'approved_at'   => Carbon::now(),
                    'published_at'  => $published_at,
					'visible_at'	=> $tomorrow,
					'ends_at'		=> $new_expired_date
				);
			} else {
				$new_data = array(
					'active'       => $active,
					'approved_at'  => Carbon::now(),
                    'published_at' => $published_at
				);
			}

			Link::for_approval($this->confirmApprove, $new_data);
		} else {
			$article      = Article::findOrFail($this->confirmApprove);
            $authority    = AuthoritySite::find($article->authority_site);
            $active       = null;
            $published_at = null;

            if(!empty($authority->wordpress)) {
                //
            } else {
                $active       = 1;
                $published_at = Carbon::now();
            }

			if ($article->visible_at <= Carbon::today()) {
				$detail   = json_decode($article->orders->details);
                $years    = self::get_years($detail);
				$tomorrow = Carbon::tomorrow();
				$new_expired_date = Carbon::tomorrow()->addYear($years);

				$new_data = array(
					'active'       => $active,
					'approved_at'  => Carbon::today(),
                    'published_at' => $published_at,
					'visible_at'   => $tomorrow,
					'ends_at'	   => $new_expired_date
				);
			}  else {
				$new_data = array(
					'active'      => $active,
					'approved_at' => Carbon::today(),
                    'published_at'=> $published_at
				);
			}

			Article::for_approval($this->confirmApprove, $new_data);
		}

		$this->confirmApprove = '';
		$this->table_approve  = '';
	}

    private function get_years($details) {
        $years = 1;

        if(!empty($detail->years)) {
            $years = $detail->years;
        }

        if(!empty($detail->years) and count($details) > 1) {
            $years = $details[0]->years;
        }

        return $years;
    }

	public function render(){
		$links = Link::waiting_for_approval($this->column_links, $this->sort, $this->pagination, $this->search);
		$articles = Article::waiting_for_approval($this->column_articles, $this->sort, $this->pagination, $this->search);
		return view('livewire.admin.approvements', compact('links', 'articles'))->layout('layouts.panel');
	}
}
