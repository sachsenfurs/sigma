<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\UserRole;
use App\Notifications\NewChatMessage;
use App\Notifications\SigUpdated;
use App\Settings\ChatSettings;
use Illuminate\Http\Request;
#use App\Helper\Chat\ChatHelper;

class ChatController extends Controller
{

    public function __construct() {
        if(!app(ChatSettings::class)->enabled)
            abort(403);
    }

    public function index(Request $request)
    {
        $chats    = auth()->user()->chats;
        $currChat = null;
        $currChatDepartments = [];
        $depCounter = 0;
        $showNewChatButton = true;

        if ($chats->count() > 0) {
          if (isset($request['chat_id'])) {
                $currChat = Chat::findOrFail($request['chat_id']);
                $this->authorize('view', $currChat);
            } else {
                $currChat = $chats->first();
            }
        }

        /*
         * Department handling
         */

        $departments = UserRole::Get()->where("chat_activated", "1");

        // Get Current Chats
        foreach ($chats as $chat) {
            array_push($currChatDepartments, $chat->userRole->id);
            $depCounter++;
        }

        if($depCounter == $departments->count()) {
            $showNewChatButton = false;
        }

        return view("chats.index", 
            compact("chats", 
                    "currChat", 
                    "currChatDepartments",
                    "showNewChatButton",
                    "departments"
                )
            );
    }

    public function create(Request $request)
    {
        $attributes = $request->validate([
            'departmentId' => 'required'
        ]);

        $department = UserRole::Get()->where("chat_activated", "1")->where("id", $attributes['departmentId'])->first();
        if ($department && !Chat::get()->where('user_id', auth()->user()->id)->where('user_role_id', $department->id)->first()) {
            $chat = Chat::create([
                'user_id' => auth()->user()->id,
                'user_role_id' => $department->id,
                'status' => 'new'
            ]);
            return redirect(route("chats.index", ['chat_id' => $chat->id]))->withSuccess(__("Chat Created"));    
        } else {
            return redirect()->back()->withError(__('You have entered an invalid department!'));
        }      
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
}
