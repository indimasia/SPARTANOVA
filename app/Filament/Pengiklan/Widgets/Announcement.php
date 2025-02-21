<?php

namespace App\Filament\Pengiklan\Widgets;

use App\Models\Annoucement;
use Filament\Widgets\Widget;
use Livewire\WithPagination;

class Announcement extends Widget
{

  use WithPagination;
    //  public function annoucement(){
    //    Annoucement::whereIn('role', ['pengiklan', 'both'])->get();
    //  }
    protected static string $view = 'filament.pengiklan.widgets.announcement';

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array{
      return [
        'announcements' => Annoucement::whereIn('role', ['pengiklan', 'both'])->paginate(2)
      ];
    }
}
