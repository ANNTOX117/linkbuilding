<?php

namespace App\Http\Livewire\Tables;

use App\Models\Package;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class Packages extends LivewireDatatable {

    public function builder() {
        return Package::query();
    }

    public function columns() {
        return [
            NumberColumn::name('id')->label('ID'),

            Column::name('name')->label('Name'),

            NumberColumn::name('price')->label('Price')
        ];
    }

}
