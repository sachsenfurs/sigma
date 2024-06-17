<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
#use App\Helper\Chat\ChatHelper;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        // 

        $chats    = auth()->user()->chats;
        $currChat = null;
        $currChatDepartments = [];
        $depCounter = 0;
        $showNewChatButton = true;

        foreach ($chats as $chat) {
            array_push($currChatDepartments, $chat->department);
            $depCounter++;
        }

        if($depCounter == 3) {
            $showNewChatButton = false;
        }

        if ($chats->count() > 0) {
          if (isset($request['chat_id'])) {
                $currChat = Chat::findOrFail($request['chat_id']);
            } else {
                $currChat = $chats->first();
            }
        }

        return view("chats.index", compact("chats", "currChat", "currChatDepartments", "showNewChatButton"));
    }

    public function create(Request $request)
    {   
        $attributes = $request->validate([
            'department' => 'required'
        ]);
        
        $requestDepartment = $attributes['department'];

        if ($this->validateDepartment($requestDepartment)) {
            $department = $requestDepartment;

            Chat::create(['user_id' => auth()->user()->id, 'department' => $department, 'status' => 'new']);

            return redirect()->back();
        }
        redirect()->back()->error(__('You have entered an invalid department!'));        
    }

    public function store(Request $request, Chat $chat)
    {
        $attributes = $request->validate([
            'text' => 'required|min:1|max:1024'
        ]);

        $attributes['user_id'] = auth()->user()->id;

        $chat->messages()->create($attributes);

        return redirect()->back();
    }

    public function destroy()
    {

    }

    /**
     * @param string $response
     * @return bool|Collection
     */
    private function validateDepartment($value)
    {
        $departments = [
            'artshow',
            'dealersden',
            'events',
        ];

        foreach ($departments as $department) {
            if (str_contains($value, $department)) {
                return true;
            }
        }
        return false;
    }
}
