<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index() {
        $notifications = [
            [
                'Title' => 'Test1',
                'Message' => 'Message1',
            ],
            [
                'Title' => 'Test2',
                'Message' => 'Message2',
            ],
            [
                'Title' => 'Test3',
                'Message' => 'Message3',
            ],
            [
                'Title' => 'Test4',
                'Message' => 'Message4',
            ],
            [
                'Title' => 'Test5',
                'Message' => 'Message5',
            ],
        ];
        return view("notifications.index", compact("notifications"));
    }

    public function store() {

    }

    public function update() {
    
    }
}
