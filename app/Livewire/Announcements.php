<?php

namespace App\Livewire;

use App\Models\Post\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Announcements extends Component
{
    use WithPagination;

    public function render() {
        return view('livewire.announcements', [
            'posts' => Post::public()->latest()->paginate(12),
        ]);
    }
}
