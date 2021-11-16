<?php

namespace App\Http\Livewire;

use App\Models\Upload;
use Livewire\Component;

class OffcanvasSearch extends Component
{
    public $offcanvas_query;
    public $uploads;

    public function mount()
    {
        $this->offcanvas_query = '';
        $this->uploads = '';
    }

    public function updatedOffcanvasQuery()
    {
        $this->uploads = Upload::where('name', 'like', '%' . $this->offcanvas_query . '%')
            ->join('approvals','uploads.id','approvals.upload_id')
            ->where('approvals.is_approved','=','Approved')
            ->select('uploads.*')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.offcanvas-search');
//        return view('livewire.search');
    }
}
