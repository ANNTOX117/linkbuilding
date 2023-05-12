<?php

namespace App\Http\Livewire\Pages;

use App\Models\Site;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class Terms extends Component {

    public $title;
    public $section;
    public $site;
    public $text;

    protected $domain;
    protected $category;
    protected $website;

    public function __construct() {
        // if(empty(session('website'))) {
        //     abort(404);
        // }

        $this->domain  = domain();
        $this->category = subdomain();
        $this->category = '';
        $this->website  = \App\Models\Site::get_info($this->domain);
        // $this->website  = \App\Models\Site::get_info('bullsandhornsmedia.com/');

        if(!empty($this->category)) {
            $this->domain = $this->category . '.' . $this->domain;
        }
        if(empty($this->website)) {
            abort(404);
        }
    }

    public function mount() {
        // $this->site    = session('website');
        $this->site    = $this->website;

        App::setLocale($this->site->languages->name ?? 'nl');

        $this->title   = trans('Terms and conditions');
        $this->section = 'terms';
        $this->text    = self::text();
    }

    public function render() {
        return view('livewire.pages.terms')->layout('layouts.website', ['title' => $this->title, 'section' => $this->section, 'site' => $this->site, 'page' => $this->site->name, 'header' => $this->site->header, 'footer' => $this->site->footer, 'footer2' => $this->site->footer2, 'footer3' => $this->site->footer3, 'footer4' => $this->site->footer4, 'headerText' => $this->site->headerText, 'footerText' => $this->site->footerText]);
    }

    private function text() {
        return "<p>Et itaque qui nobis quia dolorum corrupti tenetur deserunt similique veniam quae natus omnis quod et. Aspernatur fuga amet reiciendis quia sunt laboriosam rerum rerum dolores veniam dignissimos. Voluptas et rem qui cum molestiae assumenda molestiae occaecati magnam eaque sint et numquam. Numquam ut enim dolor praesentium aliquam non illo repudiandae maxime culpa quod mollitia facere. Vitae iusto rem tempore consequatur est magni in eos. Quis consequuntur qui sapiente consequuntur dolor dicta quisquam tempora sit eos accusantium.</p>
                <p>Hic dicta nobis autem doloremque architecto qui quia reprehenderit labore aut sequi nihil enim modi. Amet aperiam quae quia modi quasi ut nemo impedit qui. Eum dolores minima et aut dolorem totam beatae asperiores est quae dicta nobis odit. Et dolorem quaerat minima aut non rerum minus minus enim nesciunt eveniet vel. Dolorum tenetur quasi sequi tenetur non. Quia facilis excepturi eius exercitationem. Nesciunt ducimus aperiam accusantium beatae. Praesentium doloremque quia deserunt fuga eveniet ducimus aut aut voluptatibus. Id reprehenderit omnis minima repellendus voluptates et sed nesciunt similique temporibus cupiditate harum sunt non voluptas.</p>
                <p>Eum ab vel aut neque ad voluptatem non similique et ut qui. Voluptatem in adipisci voluptatem totam numquam in tempore doloremque nulla. Repellendus voluptas est placeat explicabo praesentium vero quo ipsa voluptates voluptas. Explicabo placeat natus porro numquam repellendus tenetur commodi quibusdam temporibus accusamus. Consectetur est quas autem vel facilis autem sed. Qui provident unde occaecati omnis. Ea voluptatem suscipit corrupti est aut sit at. Molestiae maxime vel distinctio dolor nihil non aut fuga a et inventore velit.</p>
                <p>Occaecati facere veniam dolore quia expedita. Occaecati quos provident magnam. Tempora est repudiandae ipsum quod. Enim delectus magni harum dolorum reiciendis numquam sequi et exercitationem dolor laboriosam. Quia culpa ab nisi est error ex inventore. Nisi dolor eveniet commodi eaque voluptas repudiandae non ad quis et soluta quidem eum neque. Architecto dolore deleniti minus in aliquam. Qui a nam ea a est.</p>
                <p>Facere eius eius sequi quia provident in nostrum laudantium minus. Voluptas fuga impedit tempora aut ipsa. Omnis voluptatem atque qui occaecati aliquid nesciunt molestiae quos sint dolorem voluptates reprehenderit. Necessitatibus beatae incidunt fugiat fuga reiciendis perspiciatis fuga mollitia et nesciunt omnis eos eos. Pariatur laudantium aut asperiores exercitationem ullam aut aspernatur dolores explicabo sint corporis atque est. Voluptas qui blanditiis incidunt vero dolor mollitia vel minima dolore optio. Non sunt consequatur eos libero totam ad laboriosam vero qui. Dicta possimus nisi omnis a accusantium voluptatem aut.</p>";
    }

}
