<?php

namespace App\Livewire;

use App\Models\Post\Post;
use App\Services\SigmaMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use Livewire\Component;
use Livewire\WithPagination;

class Announcements extends Component
{
    use WithPagination;

    public function render() {




        return view('livewire.announcements', [
            'posts' => Post::latest()->paginate(12),
        ]);
    }
}
