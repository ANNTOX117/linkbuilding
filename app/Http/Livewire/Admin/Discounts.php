<?php

namespace App\Http\Livewire\Admin;

use App\Models\Discount;
use App\Models\DiscountDefault;
use App\Models\DiscountPrice;
use App\Models\Group;
use App\Models\GroupDiscount;
use App\Models\RuleDiscount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Discounts extends Component {

    use WithPagination;

    public $title;
    public $section = 'discounts';
    public $column_staffels = 'name';
    public $column_rules    = 'id';
    public $sort    = 'asc';
    public $tab     = 'staffels';
    public $table;
    public $confirm;
    public $discount_id;
    public $from;
    public $to;
    public $percentage;
    public $more;
    public $group;
    public $discounts = [];
    public $i = 1;
    public $what;
    public $rule_id;
    public $rule_staffel;
    public $rule_user;
    public $rule_group;
    public $rule_product;
    public $rule_price;
    public $rule_percentage;
    public $rule_active = true;
    public $users;
    public $groups;
    public $status = [];
    public $open = false;
    public $edit_default;
    public $discounts_default;
    public $discounts_price;
    public $default_id;
    public $default_percentage;
    public $default_years;
    public $edit_default_price;
    public $default_price_id;
    public $default_price_percentage;
    public $default_price_minimum;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'from'       => 'required|numeric',
        'to'         => 'nullable|numeric',
        'percentage' => 'required|numeric'
    ];

    public function updated($propertyName){
        $this->validateOnly($propertyName);
    }

    public function updatedRuleUser($param) {
        $this->dispatchBrowserEvent('enabled_users');
    }

    public function updatedRuleGroup($param) {
        $this->dispatchBrowserEvent('enabled_groups');
    }

    public function updatedRuleProduct($param) {
        $this->dispatchBrowserEvent('enabled_products');
    }

    public function mount() {
        if(!permission('discounts', 'read')) {
            abort(404);
        }

        $this->title  = trans('Discounts');
        $this->users  = User::get_customers();
        $this->groups = Group::all_items();

        self::loadDefaults();
        self::loadDefaultsPrice();
    }

    public function render() {
        $staffels  = GroupDiscount::with_pagination($this->column_staffels, $this->sort);
        $ruleslist = RuleDiscount::with_pagination($this->column_rules, $this->sort);

        if(!empty($ruleslist)) {
            foreach($ruleslist as $item) {
                $this->status[$item->id] = $item->active === 1;
            }
        }

        return view('livewire.admin.discounts', compact('staffels', 'ruleslist'))->layout('layouts.panel');
    }

    public function table($table) {
        $this->tab = $table;

        if($this->tab == 'staffels') {
            $this->column_staffels = 'name';
        }

        if($this->tab == 'rules') {
            $this->column_rules = 'id';
        }

        $this->resetPage();
    }

    public function sort($table, $column) {
        $this->sort = ($this->sort == 'asc') ? 'desc' : 'asc';

        if($table == 'staffels') {
            $this->column_staffels = $column;
        }

        if($table == 'rules') {
            $this->column_rules = $column;
        }
    }

    public function add($i, $edit = false) {
        if($edit) {
            $i = count($this->from) + 1;
            $this->i = $i;
            $this->from[$i] = '';
        } else {
            $i = $i + 1;
            $this->i = $i;
            array_push($this->discounts ,$i);
        }
    }

    public function remove($i, $edit = false) {
        if($edit) {
            unset($this->from[$i]);
        } else {
            unset($this->discounts[$i]);
        }
    }

    public function changeStatus($discount) {
        RuleDiscount::status($discount, $this->status[$discount]);
    }

    public function modalAddStaffel() {
        $this->discounts = [];
        self::resetStaffelInputFields();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showAddStaffel');
    }

    public function modalAddRule() {
        self::resetRuleInputFields();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showAddRule');
    }

    public function modalAddDefault() {
        self::resetDefaultInputFields();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showAddDefault');
    }

    public function modalAddDefaultPrice() {
        self::resetDefaultPriceInputFields();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showAddDefaultPrice');
    }

    public function modalEditStaffel($id) {
        $staffel = GroupDiscount::find($id);

        if(!empty($staffel)) {
            $this->discount_id = $id;
            $this->group       = $staffel->name;
            $this->from        = Discount::get_values($id, 'from');
            $this->to          = Discount::get_values($id, 'to');
            $this->percentage  = Discount::get_values($id, 'percentage');

            $this->resetErrorBag();
            $this->dispatchBrowserEvent('showEditStaffel');
        }
    }

    public function modalEditRule($id) {
        $rule = RuleDiscount::find($id);

        if(!empty($rule)) {
            $this->rule_id         = $rule->id;
            $this->rule_staffel    = $rule->discount;
            $this->rule_user       = $rule->user;
            $this->rule_group      = $rule->group;
            $this->rule_product    = $rule->product;
            $this->rule_price      = $rule->price;
            $this->rule_percentage = $rule->percentage;
            $this->rule_active     = $rule->active;

            $this->resetErrorBag();
            $this->dispatchBrowserEvent('showEditRule');

            if(!empty($this->rule_user)) {
                $this->dispatchBrowserEvent('enabled_users');
            }

            if(!empty($this->rule_group)) {
                $this->dispatchBrowserEvent('enabled_groups');
            }

            if(!empty($this->rule_product)) {
                $this->dispatchBrowserEvent('enabled_products');
            }
        }
    }

    public function modalOpenStaffel($id) {
        $staffel = GroupDiscount::find($id);

        if(!empty($staffel)) {
            $this->discount_id = $id;
            $this->group       = $staffel->name;
            $this->from        = Discount::get_values($id, 'from');
            $this->to          = Discount::get_values($id, 'to');
            $this->percentage  = Discount::get_values($id, 'percentage');

            $this->open = true;
            $this->dispatchBrowserEvent('showOpenStaffel');
        }
    }

    public function addStaffel() {
        $data = $this->validate([
            'group'      => 'required',
            'from'       => 'required',
            'to'         => 'required',
            'percentage' => 'required'
        ]);

        $group = GroupDiscount::create(['name' => mysql_null($data['group'])]);

        $discounts = array();

        foreach($this->from as $i => $item) {
            array_push($discounts, [
                'group'      => $group->id,
                'from'       => mysql_null($this->from[$i]),
                'to'         => (isset($this->to[$i])) ? mysql_null($this->to[$i]) : null,
                'percentage' => mysql_null($this->percentage[$i])
            ]);
        }

        DB::table('discounts')->insert($discounts);

        self::resetStaffelInputFields();

        session()->flash('successStaffel', trans('Staffel succesfully created'));
        $this->dispatchBrowserEvent('hideAddStaffel');

    }

    public function editStaffel() {
        $data = $this->validate([
            'group'      => 'required',
            'from'       => 'required',
            'to'         => 'required',
            'percentage' => 'required'
        ]);

        GroupDiscount::update_name($this->discount_id, $this->group);

        $discounts = array();

        foreach($this->from as $i => $item) {
            array_push($discounts, [
                'group'      => $this->discount_id,
                'from'       => mysql_null($this->from[$i]),
                'to'         => (isset($this->to[$i])) ? mysql_null($this->to[$i]) : null,
                'percentage' => mysql_null($this->percentage[$i])
            ]);
        }

        Discount::remove($this->discount_id);
        DB::table('discounts')->insert($discounts);

        self::resetStaffelInputFields();

        session()->flash('successStaffel', trans('Staffel succesfully edited'));
        $this->dispatchBrowserEvent('hideEditStaffel');
    }

    public function addDefault() {
        $data = $this->validate([
            'default_years'      => 'required',
            'default_percentage' => 'required'
        ]);

        DiscountDefault::create(['percentage' => mysql_null($data['default_percentage']), 'years' => mysql_null($data['default_years'])]);

        self::loadDefaults();
        self::resetDefaultInputFields();

        session()->flash('successDefault', trans('Default succesfully created'));
        $this->dispatchBrowserEvent('hideAddDefault');

    }

    public function addDefaultPrice() {
        $data = $this->validate([
            'default_price_minimum'    => 'required',
            'default_price_percentage' => 'required'
        ]);

        DiscountPrice::create(['percentage' => mysql_null($data['default_price_percentage']), 'price' => mysql_null($data['default_price_minimum'])]);

        self::loadDefaultsPrice();
        self::resetDefaultPriceInputFields();

        session()->flash('successDefaultPrice', trans('Discount succesfully created'));
        $this->dispatchBrowserEvent('hideAddDefaultPrice');
    }

    public function addRule() {
        if(empty($this->rule_user) and empty($this->rule_group) and empty($this->rule_product)) {
            session()->flash('errorRule', trans('Select an user or group or product'));
            return false;
        }

        $data = $this->validate([
            'rule_staffel'    => 'required',
            'rule_user'       => 'nullable',
            'rule_group'      => 'nullable',
            'rule_product'    => 'nullable',
            'rule_price'      => 'required|numeric',
            'rule_percentage' => 'required|numeric',
            'rule_active'     => 'nullable|boolean'
        ]);

        $group = RuleDiscount::create([
            'discount'   => mysql_null($data['rule_staffel']),
            'user'       => mysql_null($data['rule_user']),
            'group'      => mysql_null($data['rule_group']),
            'product'    => mysql_null($data['rule_product']),
            'price'      => mysql_null($data['rule_price']),
            'percentage' => mysql_null($data['rule_percentage']),
            'active'     => mysql_null($data['rule_active'])
        ]);

        self::resetRuleInputFields();

        session()->flash('successRule', trans('Rule succesfully created'));
        $this->dispatchBrowserEvent('hideAddRule');
    }

    public function editRule() {
        if(empty($this->rule_user) and empty($this->rule_group) and empty($this->rule_product)) {
            session()->flash('errorRule', trans('Select an user or group or product'));
            return false;
        }

        $data = $this->validate([
            'rule_staffel'    => 'required',
            'rule_user'       => 'nullable',
            'rule_group'      => 'nullable',
            'rule_product'    => 'nullable',
            'rule_price'      => 'required|numeric',
            'rule_percentage' => 'required|numeric',
            'rule_active'     => 'nullable|boolean'
        ]);

        $rule = RuleDiscount::find($this->rule_id);

        if(!empty($rule)) {
            $rule->discount   = mysql_null($data['rule_staffel']);
            $rule->user       = mysql_null($data['rule_user']);
            $rule->group      = mysql_null($data['rule_group']);
            $rule->product    = mysql_null($data['rule_product']);
            $rule->price      = mysql_null($data['rule_price']);
            $rule->percentage = mysql_null($data['rule_percentage']);
            $rule->active     = mysql_null($data['rule_active']);
            $rule->save();
        }

        self::resetRuleInputFields();

        session()->flash('successRule', trans('Rule succesfully updated'));
        $this->dispatchBrowserEvent('hideEditRule');
    }

    public function editDefaultRow($id) {
        $default = DiscountDefault::find($id);

        if(!empty($default)) {
            $this->default_id         = $default->id;
            $this->default_percentage = $default->percentage;
            $this->default_years      = $default->years;

            $this->dispatchBrowserEvent('showEditDefault');
        }
    }

    public function editDefault() {
        $data = $this->validate([
            'default_percentage' => 'required|numeric',
            'default_years'      => 'required|numeric',
        ]);

        $default = DiscountDefault::find($this->default_id);

        if(!empty($default)) {
            $default->percentage = mysql_null($data['default_percentage']);
            $default->years      = mysql_null($data['default_years']);
            $default->save();
        }

        self::resetDefaultInputFields();
        self::loadDefaults();

        $this->dispatchBrowserEvent('hideEditDefault');
    }

    public function editDefaultPriceRow($id) {
        $default = DiscountPrice::find($id);

        if(!empty($default)) {
            $this->default_price_id         = $default->id;
            $this->default_price_percentage = $default->percentage;
            $this->default_price_minimum    = $default->price;

            $this->dispatchBrowserEvent('showEditDefaultPrice');
        }
    }

    public function editDefaultPrice() {
        $data = $this->validate([
            'default_price_minimum'    => 'required',
            'default_price_percentage' => 'required'
        ]);

        $default = DiscountPrice::find($this->default_price_id);

        if(!empty($default)) {
            $default->percentage = mysql_null($data['default_price_percentage']);
            $default->price      = mysql_null($data['default_price_minimum']);
            $default->save();
        }

        self::resetDefaultPriceInputFields();
        self::loadDefaultsPrice();

        $this->dispatchBrowserEvent('hideEditDefaultPrice');
    }

    public function cancelDefaultRow() {
        self::resetDefaultInputFields();
    }

    public function confirm($id) {
        if($this->tab == 'staffels') {
            $this->what = trans('staffel');
        }

        if($this->tab == 'rules') {
            $this->what = trans('rule');
        }

        if($this->tab == 'defaults') {
            $this->what = trans('discount');
        }

        if($this->tab == 'by_price') {
            $this->what = trans('discount');
        }

        $this->confirm = $id;
        $this->dispatchBrowserEvent('confirmDelete');
    }

    public function delete() {
        if($this->tab == 'staffels') {
            Discount::remove($this->confirm);
            GroupDiscount::destroy($this->confirm);
        }

        if($this->tab == 'rules') {
            RuleDiscount::destroy($this->confirm);
        }

        if($this->tab == 'defaults') {
            DiscountDefault::destroy($this->confirm);
            self::loadDefaults();
        }

        if($this->tab == 'by_price') {
            DiscountPrice::destroy($this->confirm);
            self::loadDefaultsPrice();
        }

        $this->confirm = '';
    }

    private function loadDefaults() {
        $this->discounts_default = DiscountDefault::all_items();
    }

    private function loadDefaultsPrice() {
        $this->discounts_price = DiscountPrice::all_items();
    }

    private function resetStaffelInputFields() {
        $this->group      = '';
        $this->from       = '';
        $this->to         = '';
        $this->percentage = '';
    }

    private function resetRuleInputFields() {
        $this->rule_staffel    = '';
        $this->rule_user       = '';
        $this->rule_group      = '';
        $this->rule_product    = '';
        $this->rule_price      = '';
        $this->rule_percentage = '';
        $this->rule_active     = true;
    }

    private function resetDefaultInputFields() {
        $this->edit_default       = false;
        $this->default_id         = '';
        $this->default_percentage = '';
        $this->default_years      = '';
    }

    private function resetDefaultPriceInputFields() {
        $this->edit_default_price       = false;
        $this->default_price_id         = '';
        $this->default_price_percentage = '';
        $this->default_price_minimum    = '';
    }

}
