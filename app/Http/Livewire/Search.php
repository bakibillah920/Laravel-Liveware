<?php

namespace App\Http\Livewire;

use App\Models\Upload;
use Livewire\Component;

class Search extends Component
{
    public $query;
    public $uploads;

    public function mount()
    {
        $this->query = '';
        $this->uploads = '';
    }

    public function updatedQuery()
    {
        $this->uploads = Upload::where('name', 'like', '%' . $this->query . '%')
            ->join('approvals','uploads.id','approvals.upload_id')
            ->where('approvals.is_approved','=','Approved')
            ->select('uploads.*')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.search');
//        return view('livewire.search');
    }

}
