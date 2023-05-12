<?php

namespace App\Http\Livewire\Admin;

use App\Models\Batch;
use App\Models\Group;
use App\Models\Language;
use App\Models\Mailing;
use App\Models\MailingText;
use App\Models\Member;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class Emails extends Component {

    use WithPagination;

    public $title;
    public $section = 'categories';
    public $column_emails = 'name';
    public $column_batches = 'created_at';
    public $sort    = 'asc';
    public $confirm;
    public $cancel;
    public $tab     = 'emails';
    public $table;
    public $customers;
    public $email_id;
    public $name;
    public $type;
    public $description;
    public $content;
    public $item;
    public $recipients;
    public $groups;
    public $roles;
    public $subject;
    public $batch = false;
    public $batch_size;
    public $batch_interval;
    public $custom_error;
    public $language;
    public $languages;
    public $pagination;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name'           => 'required|max:50',
        'type'           => 'nullable|max:50',
        'description'    => 'required',
        'recipients'     => 'required',
        'subject'        => 'required|max:50',
        'batch'          => 'nullable',
        'batch_size'     => 'nullable|numeric',
        'batch_interval' => 'nullable|numeric'
    ];

    public function updated($propertyName){
        $this->validateOnly($propertyName);
    }

    public function mount() {
        if(!permission('mailing', 'read')) {
            abort(404);
        }

        $this->title      = trans('Emails');
        $this->languages  = Language::all();
        $this->pagination = env('APP_PAGINATE');
    }

    public function render() {
        $emails  = MailingText::with_pagination($this->column_emails, $this->sort, $this->pagination, $this->search);
        $batches = Mailing::with_pagination($this->column_batches, $this->sort, $this->pagination, $this->search);

        if(!empty($batches)) {
            foreach($batches as $i => $batch) {
                $batches[$i]['sent']     = Batch::count_sent($batch->id);
                $batches[$i]['not_sent'] = Batch::count_not_sent($batch->id);
            }
        }

        return view('livewire.admin.emails', compact('emails', 'batches'))->layout('layouts.panel');
    }

    public function table($table) {
        $this->tab = $table;

        if($this->tab == 'emails') {
            $this->column_emails = 'name';
        }

        if($this->tab == 'batches') {
            $this->column_batches = 'created_at';
        }

        $this->resetPage();
    }

    public function sort($table, $column) {
        $this->sort = ($this->sort == 'asc') ? 'desc' : 'asc';

        if($table == 'emails') {
            $this->column_emails = $column;
        }

        if($table == 'batches') {
            $this->column_batches = $column;
        }
    }

    public function modalAddEmail() {
        self::resetEmailsInputFields();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showAddEmail');
    }

    public function modalEditEmail($id) {
        $email = MailingText::find($id);

        if(!empty($email)) {
            $this->email_id    = $email->id;
            $this->name        = $email->name;
            $this->type        = $email->type;
            $this->description = $email->description;
            $this->content     = $email->description;
            $this->language    = $email->language;

            $this->resetErrorBag();
            $this->dispatchBrowserEvent('showEditEmail', ['editor' => $this->content]);
        }
    }

    public function modalEditBatch($id) {
        $email = Mailing::find($id);

        if(!empty($email)) {
            $this->email_id    = $email->id;
            $this->name        = $email->subject;
            $this->description = $email->templates->description;

            $this->resetErrorBag();
            $this->dispatchBrowserEvent('showEditBatch', ['editor' => $this->description]);
        }
    }

    public function addEmail() {
        $data = $this->validate([
            'name'        => 'required|max:50',
            'type'        => 'nullable|max:50',
            'description' => 'required',
            'language'    => 'required'
        ]);

        if(MailingText::already_exists($data['name'], $data['type'], $data['language'])) {
            $this->addError('name', trans('This email already exists on the database'));
            return false;
        }

        MailingText::create([
            'name'        => mysql_null($data['name']),
            'type'        => mysql_null($data['type']),
            'description' => mysql_null($data['description']),
            'language'    => mysql_null($data['language'])
        ]);

        self::resetEmailsInputFields();

        session()->flash('successEmail', trans('Email succesfully created'));
        $this->dispatchBrowserEvent('hideAddEmail');
    }

    public function editEmail() {
        $this->description = $this->content;

        $data = $this->validate([
            'name'        => 'required|max:50',
            'type'        => 'nullable|max:50',
            'description' => 'required',
            'language'    => 'required'
        ]);

        if(MailingText::already_exists($data['name'], $data['type'], $data['language'], $this->email_id)) {
            $this->addError('name', trans('This email already exists on the database'));
            return false;
        }

        $email = MailingText::find($this->email_id);

        if(!empty($email)) {
            $email->name        = mysql_null($data['name']);
            $email->type        = mysql_null($data['type']);
            $email->description = mysql_null($data['description']);
            $email->language    = mysql_null($data['language']);
            $email->save();
        }

        self::resetEmailsInputFields();

        session()->flash('successEmail', trans('Email succesfully edited'));
        $this->dispatchBrowserEvent('hideEditEmail');
    }

    public function editBatch() {
        $data = $this->validate([
            'name'        => 'required|max:50',
            'description' => 'required'
        ]);

        $email = Mailing::find($this->email_id);
        $text  = MailingText::find($email->email);

        if(!empty($email)) {
            $email->subject = mysql_null($data['name']);
            $email->save();
        }

        if(!empty($text)) {
            $text->description = mysql_null($data['description']);
            $text->save();
        }

        self::resetEmailsInputFields();

        session()->flash('successBatch', trans('Batch email succesfully edited'));
        $this->dispatchBrowserEvent('hideEditBatch');
    }

    public function modalSendEmail($id) {
        $this->email_id = $id;

        self::resetSendInputFields();
        self::loadCustomers();

        $this->dispatchBrowserEvent('resetCustomers', ['options' =>  $this->customers, 'all' => trans('All customers'), 'roles' => $this->roles, 'groups' => $this->groups]);
        $this->dispatchBrowserEvent('showSendEmail');
    }

    public function sendEmail() {
        $is_admin = false;

        // For all customers
        if(in_array('0', $this->recipients)) {
            $this->recipients = User::select_filtered_for_newsletter_to_array();
            $is_admin = true;
        }

        // If not admin
        if(!$is_admin) {
            // For all roles
            if($this->recipients) {
                foreach($this->recipients as $i => $recipient) {
                    if(strpos($recipient, 'R:') !== false) {
                        $recipient = str_replace('R:', '', $recipient);
                        $role      = Role::by_name($recipient);
                        if(!empty($role)) {
                            $members = User::members_for_recipients($role->id);
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

            // For all groups
            if($this->recipients) {
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

        if($this->batch) {
            if(empty($this->batch_size)) {
                $this->custom_error = trans('The batch size is required');
                $this->dispatchBrowserEvent('showError', ['message' => $this->custom_error]);
                return false;
            }

            if(empty($this->batch_interval)) {
                $this->custom_error = trans('The batch interval is required');
                $this->dispatchBrowserEvent('showError', ['message' => $this->custom_error]);
                return false;
            }

            $mailing = Mailing::create(['email' => $this->email_id, 'subject' => $this->subject, 'batch' => $this->batch, 'size' => $this->batch_size, 'interval' => $this->batch_interval]);

            $timer = array();

            if(!empty($this->recipients)) {
                date_default_timezone_set(config('app.timezone'));
                $now       = date('Y-m-d H:i:s');
                $hours     = $this->batch_interval;
                $customers = array_chunk($this->recipients, $this->batch_size);

                foreach($customers as $chunks) {
                    $send_at = date("Y-m-d H:i:s", strtotime('+'. $hours .' hours', strtotime($now)));
                    foreach($chunks as $chunk) {
                        $timer[] = $send_at;
                    }
                    $hours = $hours + $this->batch_interval;
                }
            }

            foreach($this->recipients as $i => $customer) {
                Batch::create(['mailing' => $mailing->id, 'customer' => $customer, 'send_at' => $timer[$i], 'waiting' => 1]);
            }

            $this->tab = 'batches';
        } else {
            $template = MailingText::find($this->email_id);

            foreach($this->recipients as $customer) {
                $customer = User::find($customer);
                self::send($customer, $this->subject, $template->description);
            }
        }

        $this->dispatchBrowserEvent('hideSendEmail');
    }

    public function confirm($id) {
        if($this->tab == 'emails') {
            $this->item = trans('email');
        }

        $this->confirm = $id;
        $this->dispatchBrowserEvent('confirmDelete');
    }

    public function confirmCancel($id) {
        $this->cancel = $id;
        $this->dispatchBrowserEvent('confirmCancel');
    }

    public function cancel() {
        Mailing::cancel($this->cancel);

        $this->cancel = '';
    }

    public function delete() {
        if($this->tab == 'emails') {
            MailingText::destroy($this->confirm);
        }

        $this->confirm = '';
    }

    private function loadCustomers() {
        $this->customers = User::select_filtered_for_newsletter();
        $this->groups    = Group::groups_for_recipients();
        $this->roles     = Role::roles_for_recipients();
    }

    private function resetEmailsInputFields() {
        $this->name        = '';
        $this->type        = '';
        $this->description = '';
        $this->content     = '';
        $this->language    = '';
    }

    public function resetSendInputFields() {
        $this->recipients     = '';
        $this->subject        = '';
        $this->subject        = '';
        $this->batch          = false;
        $this->batch_size     = '';
        $this->batch_interval = '';

        $this->dispatchBrowserEvent('resetCustomers', ['options' =>  $this->customers]);
    }

    private function send($user, $subject, $content) {
        $subject = replace_variables($subject, $user->id);
        $content = replace_variables($content, $user->id);

        Mail::send('mails.template', ['content' => $content], function ($mail) use ($user, $subject) {
            $mail->from(env('APP_EMAIL'), config('app.name'));
            $mail->to($user->email, $user->name .' '. $user->lastname)->subject($subject);
        });
    }

}
