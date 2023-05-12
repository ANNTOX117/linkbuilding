<?php

namespace App\Http\Livewire\Admin;

use App\Models\Country;
use App\Models\Group;
use App\Models\MailingText;
use App\Models\Member;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SettingUser;
use App\Models\Setting;

class Users extends Component {

    use WithPagination;

    public $title;
    public $section = 'users';
    public $column_users = 'users.name';
    public $column_roles = 'name';
    public $column_groups = 'groups.name';
    public $sort    = 'asc';
    public $confirm;
    public $tab = 'users';
    public $table;
    public $countries;
    public $edit_role = false;
    public $user_id;
    public $name;
    public $lastname;
    public $company;
    public $email;
    public $city;
    public $country;
    public $kvk;
    public $tax;
    public $postal_code;
    public $role_user;
    public $role_id;
    public $role_name;
    public $role_description;
    public $credits = '1';
    public $item;
    public $permissions_dashboard = [];
    public $permissions_languages = [];
    public $permissions_categories = [];
    public $permissions_sites = [];
    public $permissions_authorities = [];
    public $permissions_wordpress = [];
    public $permissions_articles = [];
    public $permissions_packages = [];
    public $permissions_links = [];
    public $permissions_approvals = [];
    public $permissions_users = [];
    public $permissions_mailing = [];
    public $permissions_taxes = [];
    public $permissions_texts = [];
    public $permissions_pages = [];
    public $permissions_payments = [];
    public $permissions_discounts = [];
    public $permissions_general = [];
    public $group_id;
    public $group_name;
    public $group_users;
    public $options;
    public $selected = [];
    public $what;
    public $pagination;
    public $search = '';
    public $recipients;
    public $customers;
    public $groups;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name'             => 'required|max:255',
        'lastname'         => 'nullable|max:50',
        'company'          => 'required|max:50',
        'email'            => 'required|email|max:255',
        'city'             => 'required|max:50',
        'country'          => 'required|numeric',
        'kvk'              => 'required|max:25',
        'tax'              => 'required|numeric',
        'postal_code'      => 'nullable|max:10',
        'role_user'        => 'required|numeric',
        'role_name'        => 'required|max:25',
        'role_description' => 'required|max:50',
        'credits'          => 'required|numeric|min:1'
    ];

    public function updated($propertyName){
        $this->validateOnly($propertyName);
    }

    public function mount() {
        if(!permission('users', 'read')) {
            abort(404);
        }

        $this->title      = trans('Users');
        $this->countries  = Country::all_items();
        $this->pagination = env('APP_PAGINATE');
    }

    public function render() {
        $users   = User::with_pagination($this->column_users, $this->sort, $this->pagination, $this->search);
        $groups  = Group::with_pagination($this->column_groups, $this->sort, $this->pagination, $this->search);
        $roles   = Role::with_pagination($this->column_roles, $this->sort, $this->pagination, $this->search);
        $members = User::all_items($this->column_users, $this->sort);

        return view('livewire.admin.users', compact('users', 'groups', 'roles', 'members'))->layout('layouts.panel');
    }

    public function table($table) {
        $this->tab = $table;

        $this->search     = '';
        $this->pagination = env('APP_PAGINATE');

        if($this->tab == 'users') {
            $this->column_users = 'users.name';
        }

        if($this->tab == 'groups') {
            $this->column_groups = 'name';
        }

        if($this->tab == 'roles') {
            $this->column_roles = 'name';
            $this->dispatchBrowserEvent('loadTooltip');
        }

        $this->resetPage();
    }

    public function sort($table, $column) {
        $this->sort = ($this->sort == 'asc') ? 'desc' : 'asc';

        if($table == 'users') {
            $this->column_users = 'users.' . $column;
        }

        if($table == 'groups') {
            $this->column_groups = $column;
        }

        if($table == 'roles') {
            $this->column_roles = $column;
        }
    }

    public function roleUsers($param, $index) {
        if($param == 'pictures') {
            $this->dispatchBrowserEvent('showUsers', ['option' => 'pictures', 'index' => $index]);
        }
        if($param == 'list') {
            $this->dispatchBrowserEvent('showUsers', ['option' => 'list', 'index' => $index]);
        }
    }

    public function modalAddUser() {
        self::resetUsersInputFields();

        $this->options = User::select_customers();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('resetCustomers', ['options' => $this->options]);
        $this->dispatchBrowserEvent('showAddUser');
    }

    public function modalEditUser($id) {
        $user = User::find($id);

        if(!empty($user)) {
            $this->user_id     = $user->id;
            $this->name        = $user->name;
            $this->lastname    = $user->lastname;
            $this->company     = $user->company;
            $this->email       = $user->email;
            $this->city        = $user->city;
            $this->country     = $user->country;
            $this->kvk         = $user->kvk_number;
            $this->tax         = $user->tax;
            $this->postal_code = $user->postal_code;
            $this->role_user   = $user->role;

            $this->resetErrorBag();
            $this->dispatchBrowserEvent('showEditUser');
        }
    }

    public function modalAddGroup() {
        self::resetGroupsInputFields();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showAddGroup');
    }

    public function modalEditGroup($id) {
        $group = Group::find($id);

        if(!empty($group)) {
            $this->group_id   = $group->id;
            $this->group_name = $group->name;

            $selected = array();

            foreach($group->members as $member) {
                $selected[] = $member->user;
            }

            $this->selected = $selected;
            $this->options  = User::selected_on_group($id);
            $this->resetErrorBag();
            $this->dispatchBrowserEvent('resetEditCustomers', ['options' => $this->options]);
            $this->dispatchBrowserEvent('showEditGroup');
        }
    }

    public function modalAddRole() {
        self::resetRolesInputFields();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showAddRole');
    }

    public function modalEditRole($id) {
        $role = Role::find($id);

        if(!empty($role)) {
            $this->role_id          = $role->id;
            $this->role_name        = $role->name;
            $this->role_description = $role->description;

            $this->resetErrorBag();
            $this->dispatchBrowserEvent('showEditRole');
        }
    }

    public function modalCredits($id) {
        $this->user_id = $id;
        self::resetCreditsInputFields();
        $this->dispatchBrowserEvent('showCredits');
    }

    public function modalAddCredits() {
        self::loadCustomers();
        self::resetCreditsInputFields();

        $this->dispatchBrowserEvent('resetRecipients', ['options' =>  $this->customers, 'all' => trans('All customers'), 'groups' => $this->groups]);
        $this->dispatchBrowserEvent('showAddCredits');
    }

    public function modalPermissions($id) {
        $this->role_id = $id;
        self::resetPermissionsInputFields();

        $permissions = Permission::by_role($id);

        if(empty($permissions)) {
            Permission::create(['role' => $id]);
            $permissions = Permission::by_role($id);
        }

        $this->permissions_dashboard   = array_boolean($permissions->dashboard);
        $this->permissions_languages   = array_boolean($permissions->languages);
        $this->permissions_categories  = array_boolean($permissions->categories);
        $this->permissions_sites       = array_boolean($permissions->sites);
        $this->permissions_authorities = array_boolean($permissions->authorities);
        $this->permissions_wordpress   = array_boolean($permissions->wordpress);
        $this->permissions_articles    = array_boolean($permissions->articles);
        $this->permissions_packages    = array_boolean($permissions->packages);
        $this->permissions_links       = array_boolean($permissions->links);
        $this->permissions_approvals   = array_boolean($permissions->approvals);
        $this->permissions_users       = array_boolean($permissions->users);
        $this->permissions_mailing     = array_boolean($permissions->mailing);
        $this->permissions_taxes       = array_boolean($permissions->taxes);
        $this->permissions_texts       = array_boolean($permissions->texts);
        $this->permissions_pages       = array_boolean($permissions->pages);
        $this->permissions_payments    = array_boolean($permissions->payments);
        $this->permissions_discounts   = array_boolean($permissions->discounts);
        $this->permissions_general     = array_boolean($permissions->general);

        $this->dispatchBrowserEvent('showPermissions');
    }

    public function addUser() {
        $data = $this->validate([
            'name'             => 'required|max:255',
            'lastname'         => 'nullable|max:50',
            'company'          => 'required|max:50',
            'email'            => 'required|email|max:255',
            'city'             => 'required|max:50',
            'country'          => 'required|numeric',
            'kvk'              => 'required|max:25',
            'tax'              => 'required|numeric',
            'postal_code'      => 'nullable|max:10',
            'role_user'        => 'required|numeric'
        ]);

        if(User::already_exists($data['email'])) {
            $this->addError('email', trans('This email already exists on the database'));
            return false;
        }

        $password = get_password();
        $verified = ($data['role_user'] != 4) ? Carbon::now() : null;

        $user = User::create([
            'name'              => mysql_null($data['name']),
            'lastname'          => mysql_null($data['lastname']),
            'company'           => mysql_null($data['company']),
            'email'             => mysql_null($data['email']),
            'city'              => mysql_null($data['city']),
            'country'           => mysql_null($data['country']),
            'kvk_number'        => mysql_null($data['kvk']),
            'tax'               => mysql_null($data['tax']),
            'postal_code'       => mysql_null($data['postal_code']),
            'role'              => mysql_null($data['role_user']),
            'email_verified_at' => $verified,
            'password'          => bcrypt($password)
        ]);

        if ($data['role_user'] == 4) {
            $options = Setting::all();
            if (!empty($options->toArray())) {
                foreach ($options as $index => $option) {
                    SettingUser::updateOrCreate(
                        ['user' => $user->id, 'option' => $option->id],
                        ['value' => 1]
                    );
                }
            }
        }

        $template = MailingText::template('Signup', App::getLocale());

        if(!empty($template)) {
            $user    = User::find($user->id);
            $subject = replace_variables($template->name, $user->id);
            $content = replace_variables($template->description, $user->id);
            $name    = $user->name . ' ' . $user->lastname;
            $email   = $user->email;

            Mail::send('mails.template', ['content' => $content, 'align' => 'center', 'password' => $password, 'email' => $user->email, 'link' => env('APP_URL').'/login' , 'link_text' => 'Log in' ], function ($mail) use ($email, $name, $subject) {
                $mail->from(env('APP_EMAIL'), env('APP_NAME'));
                $mail->to($email, $name)->subject($subject);
            });
        }

        self::resetUsersInputFields();

        session()->flash('successUser', trans('User succesfully created'));
        $this->dispatchBrowserEvent('hideAddUser');
    }

    public function editUser() {
        $data = $this->validate([
            'name'        => 'required|max:255',
            'lastname'    => 'nullable|max:50',
            'company'     => 'required|max:50',
            'email'       => 'required|email|max:255',
            'city'        => 'required|max:50',
            'country'     => 'required|numeric',
            'kvk'         => 'required|max:25',
            'tax'         => 'required|numeric',
            'postal_code' => 'nullable|max:10',
            'role_user'   => 'required|numeric',
        ]);

        if(User::already_exists($data['email'], $this->user_id)) {
            $this->addError('email', trans('This email already exists on the database'));
            return false;
        }

        $user = User::find($this->user_id);

        if(!empty($user)) {
            $user->name        = mysql_null($data['name']);
            $user->lastname    = mysql_null($data['lastname']);
            $user->company     = mysql_null($data['company']);
            $user->email       = mysql_null($data['email']);
            $user->city        = mysql_null($data['city']);
            $user->country     = mysql_null($data['country']);
            $user->kvk_number  = mysql_null($data['kvk']);
            $user->tax         = mysql_null($data['tax']);
            $user->postal_code = mysql_null($data['postal_code']);
            $user->role        = mysql_null($data['role_user']);
            $user->save();
        }

        self::resetUsersInputFields();

        session()->flash('successUser', trans('User succesfully edited'));
        $this->dispatchBrowserEvent('hideEditUser');
    }

    public function addRole() {
        $data = $this->validate([
            'role_name'        => 'required|max:25',
            'role_description' => 'required|max:50'
        ]);

        Role::create([
            'name'        => mysql_null($data['role_name']),
            'description' => mysql_null($data['role_description'])
        ]);

        self::resetRolesInputFields();

        session()->flash('successRole', trans('Role succesfully created'));
        $this->dispatchBrowserEvent('hideAddRole');
    }

    public function editRole() {
        $data = $this->validate([
            'role_name'        => 'required|max:25',
            'role_description' => 'required|max:50'
        ]);

        $role = Role::find($this->role_id);

        if(!empty($role)) {
            $role->name        = mysql_null($data['role_name']);
            $role->description = mysql_null($data['role_description']);
            $role->save();
        }

        self::resetRolesInputFields();

        session()->flash('successRole', trans('Role succesfully edited'));
        $this->dispatchBrowserEvent('hideEditRole');
    }

    public function addGroup() {
        $data = $this->validate([
            'group_name' => 'required',
            'selected'   => 'required|array'
        ]);

        $group  = Group::create(['name' => mysql_null($data['group_name'])]);
        $groups = array();

        foreach($this->selected as $value) {
            if(is_numeric($value)) {
                array_push($groups, [
                    'group' => $group->id,
                    'user'  => $value
                ]);
            }
        }

        DB::table('members')->insert($groups);

        self::resetGroupsInputFields();

        session()->flash('successGroup', trans('Group succesfully created'));
        $this->dispatchBrowserEvent('hideAddGroup');
    }

    public function editGroup() {
        $data = $this->validate([
            'group_name' => 'required',
            'selected'   => 'required|array'
        ]);

        $group = Group::find($this->group_id);

        if(!empty($group)) {
            $group->name = mysql_null($data['group_name']);
            $group->save();
        }

        Member::cleanup($this->group_id);

        $groups = array();

        foreach($this->selected as $value) {
            if(is_numeric($value)) {
                array_push($groups, [
                    'group' => $this->group_id,
                    'user'  => $value
                ]);
            }
        }

        DB::table('members')->insert($groups);

        self::resetGroupsInputFields();

        session()->flash('successRole', trans('Group succesfully edited'));
        $this->dispatchBrowserEvent('hideEditGroup');
    }

    public function addCredits() {
        $data = $this->validate([
            'credits' => 'required|numeric|min:1'
        ]);

        $user = User::find($this->user_id);

        if(!empty($user)) {
            $user->credit = floatval($user->credit) + floatval($data['credits']);
            $user->save();
        }

        self::resetCreditsInputFields();

        session()->flash('successCredits', trans('Credits succesfully added'));
        $this->dispatchBrowserEvent('hideCredits');
    }

    public function addAllCredits() {
        $data = $this->validate([
            'recipients' => 'required',
            'credits'    => 'required|numeric|min:1'
        ]);

        // For all customers
        if(in_array('0', $this->recipients)) {
            $this->recipients = User::members_for_recipients(4)->toArray();
        } else {
            // For all groups
            if(!empty($this->recipients)) {
                foreach($this->recipients as $i => $recipient) {
                    if(strpos($recipient, 'G:') !== false) {
                        $recipient = str_replace('G:', '', $recipient);
                        $group     = Group::by_name($recipient);
                        if(!empty($group)) {
                            $members = Member::members_for_recipients($group->id);
                            if(!empty($members)) {
                                foreach($members as $member) {
                                    $this->recipients[] = strval($member);
                                }
                            }
                        }
                        unset($this->recipients[$i]);
                    }
                }
                $this->recipients = array_unique($this->recipients);
            }
        }

        if(!empty($this->recipients)) {
            foreach($this->recipients as $user_id) {
                $user = User::find($user_id);

                if(!empty($user)) {
                    $user->credit = floatval($user->credit) + floatval($data['credits']);
                    $user->save();
                }
            }
        }

        self::resetCreditsInputFields();

        session()->flash('successAddCredits', trans('Credits succesfully added'));
        $this->dispatchBrowserEvent('hideAddCredits');
    }

    public function editPermissions() {
        if(!empty($this->role_id)) {
            $permission = Permission::by_role($this->role_id);

            if(!empty($permission)) {
                $permission->dashboard   = array_boolean_revert($this->permissions_dashboard);
                $permission->languages   = array_boolean_revert($this->permissions_languages);
                $permission->categories  = array_boolean_revert($this->permissions_categories);
                $permission->sites       = array_boolean_revert($this->permissions_sites);
                $permission->authorities = array_boolean_revert($this->permissions_authorities);
                $permission->wordpress   = array_boolean_revert($this->permissions_wordpress);
                $permission->articles    = array_boolean_revert($this->permissions_articles);
                $permission->packages    = array_boolean_revert($this->permissions_packages);
                $permission->links       = array_boolean_revert($this->permissions_links);
                $permission->approvals   = array_boolean_revert($this->permissions_approvals);
                $permission->users       = array_boolean_revert($this->permissions_users);
                $permission->mailing     = array_boolean_revert($this->permissions_mailing);
                $permission->taxes       = array_boolean_revert($this->permissions_taxes);
                $permission->texts       = array_boolean_revert($this->permissions_texts);
                $permission->pages       = array_boolean_revert($this->permissions_pages);
                $permission->payments    = array_boolean_revert($this->permissions_payments);
                $permission->discounts   = array_boolean_revert($this->permissions_discounts);
                $permission->general     = array_boolean_revert($this->permissions_general);
                $permission->save();

                session()->flash('successPermissions', trans('Permissions succesfully saved'));
                $this->dispatchBrowserEvent('hidePermissions');
            }
        }
    }

    public function confirm($id) {
        if($this->tab == 'users') {
            $this->what = trans('user');
        }

        if($this->tab == 'groups') {
            $this->what = trans('group');
        }

        if($this->tab == 'roles') {
            $this->what = trans('role');
        }

        $this->confirm = $id;
        $this->dispatchBrowserEvent('confirmDelete');
    }

    public function delete() {
        if($this->tab == 'users') {
            User::destroy($this->confirm);
        }

        if($this->tab == 'groups') {
            Group::destroy($this->confirm);
        }

        if($this->tab == 'roles') {
            Role::destroy($this->confirm);
        }

        $this->confirm = '';
    }

    private function loadCustomers() {
        $this->customers = User::select_customers();
        $this->groups    = Group::groups_for_recipients();
    }

    private function resetUsersInputFields() {
        $this->user_id     = '';
        $this->name        = '';
        $this->lastname    = '';
        $this->company     = '';
        $this->email       = '';
        $this->city        = '';
        $this->country     = '';
        $this->kvk         = '';
        $this->tax         = '';
        $this->postal_code = '';
        $this->role_user   = '';
    }

    private function resetGroupsInputFields() {
        $this->group_name  = '';
        $this->group_users = '';
        $this->selected    = [];
    }

    private function resetRolesInputFields() {
        $this->role_id          = '';
        $this->role_name        = '';
        $this->role_description = '';
    }

    private function resetCreditsInputFields() {
        $this->recipients = '';
        $this->credits    = '1';
    }

    private function resetPermissionsInputFields() {
        $this->permissions             = '';
        $this->permissions_dashboard   = '';
        $this->permissions_languages   = '';
        $this->permissions_categories  = '';
        $this->permissions_sites       = '';
        $this->permissions_authorities = '';
        $this->permissions_wordpress   = '';
        $this->permissions_articles    = '';
        $this->permissions_packages    = '';
        $this->permissions_links       = '';
        $this->permissions_approvals   = '';
        $this->permissions_users       = '';
        $this->permissions_mailing     = '';
        $this->permissions_taxes       = '';
        $this->permissions_texts       = '';
        $this->permissions_pages       = '';
        $this->permissions_payments    = '';
        $this->permissions_discounts   = '';
        $this->permissions_general     = '';
    }

}
