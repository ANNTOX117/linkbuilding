<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailingText extends Model {

    use HasFactory;

    protected $table = 'mailing_text';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'type',
        'description',
        'language'
    ];

    public function languages() {
        return $this->hasOne('App\Models\Language', 'id', 'language');
    }

    public function scopeFilterSearch($query, $val) {
        return $query->leftJoin('languages', 'languages.id', '=', 'mailing_text.language')
            ->where('mailing_text.name', 'like', '%'.$val.'%')
            ->orWhere('mailing_text.type', 'like', '%'.$val.'%')
            ->orWhere('mailing_text.description', 'like', '%'.$val.'%')
            ->orWhere('languages.description', 'like', '%'.$val.'%');
    }

    public static function all_items($sort = 'name', $order = 'asc') {
        return MailingText::orderBy($sort, $order)->get();
    }

    public static function with_pagination($sort = 'name', $order = 'asc', $pagination, $search = null) {
        return MailingText::select('mailing_text.*')->filterSearch($search)->orderBy($sort, $order)->paginate($pagination);
    }

    public static function template($type, $lang = null) {
        return MailingText::select('mailing_text.*')
            ->leftJoin('languages', 'languages.id', '=', 'mailing_text.language')
            ->where('mailing_text.type', $type)->where('languages.name', $lang)
            ->first();
    }

    public static function reminder_info($period, $lang = null) {
        return MailingText::select('mailing_text.*')
            ->leftJoin('languages', 'languages.id', '=', 'mailing_text.language')
            ->where('mailing_text.type', 'reminder')
            ->where('languages.name', $lang)
            ->when($period, function ($query) use ($period) {
                if($period == '2 months') {
                    return $query->where('mailing_text.name', 'like', '%2 months%')->orWhere('mailing_text.name', 'like', '%2 maanden%');
                }
                if($period == '15 days') {
                    return $query->where('mailing_text.name', 'like', '%15 days%')->orWhere('mailing_text.name', 'like', '%15 dagen%');
                }
                if($period == '2 days') {
                    return $query->where('mailing_text.name', 'like', '%2 days%')->orWhere('mailing_text.name', 'like', '%2 dagen%');
                }
            })
            ->first();
    }

    public static function already_exists($name, $type, $language, $id = null) {
        if(!empty($id)) {
            return MailingText::where('id', '!=', $id)->where('name', $name)->where('type', $type)->where('language', $language)->exists();
        }

        return MailingText::where('name', $name)->where('type', $type)->where('language', $language)->exists();
    }

}
