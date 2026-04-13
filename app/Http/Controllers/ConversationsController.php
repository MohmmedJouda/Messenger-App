<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Recipient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ensure AI communication exists
        $aiUser = \App\Models\User::where('is_ai', true)->first();
        if ($aiUser && $aiUser->id !== $user->id) {
            $this->ensureAIConversation($user, $aiUser);
        }

        return $user->conversations()->with([
            'lastMessage',
            'participants' => function ($builder) use ($user) {
                $builder->where('id', '<>', $user->id);
            },
        ])
            ->withCount([
                'recipients as new_messages' => function ($builder) use ($user) {
                    $builder->where('recipients.user_id', '=', $user->id)
                        ->whereNull('read_at');
                }
            ])
            // Pin AI conversation to the top
            ->orderByRaw('EXISTS (
                SELECT 1 FROM participants p 
                JOIN users u ON u.id = p.user_id 
                WHERE p.conversation_id = conversations.id 
                AND u.is_ai = 1
            ) DESC')
            ->orderBy('conversations.last_message_id', 'desc')
            ->paginate();
    }

    protected function ensureAIConversation($user, $aiUser)
    {
        $conversation = \App\Models\Conversation::where('type', 'peer')
            ->whereHas('participants', function ($builder) use ($aiUser, $user) {
                $builder->join('participants as p2', 'p2.conversation_id', '=', 'participants.conversation_id')
                    ->where('participants.user_id', '=', $aiUser->id)
                    ->where('p2.user_id', '=', $user->id);
            })->first();

        if (!$conversation) {
            $conversation = \App\Models\Conversation::create([
                'user_id' => $user->id,
                'type' => 'peer',
            ]);

            $conversation->participants()->attach([
                $user->id => ['joined_at' => now()],
                $aiUser->id => ['joined_at' => now()],
            ]);
            
            // Send welcome message from AI
            $message = $conversation->messages()->create([
                'user_id' => $aiUser->id,
                'type' => 'text',
                'body' => 'Hello! I am Messenger AI. How can I help you today?',
            ]);

            $conversation->update(['last_message_id' => $message->id]);
            
            \App\Models\Recipient::create([
                'user_id' => $user->id,
                'message_id' => $message->id,
            ]);
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        return $user->conversations()->with([
            'lastMessage',
            'participants' => function ($builder) use ($user) {
                $builder->where('id', '<>', $user->id);
            },
        ])
            ->withCount([
                'recipients as new_messages' => function ($builder) use ($user) {
                    $builder->where('recipients.user_id', '=', $user->id)
                        ->whereNull('read_at');
                }
            ])
            ->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'participants' => 'required|array|min:1',
            'participants.*' => 'integer|exists:users,id',
        ]);

        $avatar_url = null;
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $avatar_url = asset('storage/' . $path);
        }

        $user = Auth::user();

        $conversation = Conversation::create([
            'user_id' => $user->id,
            'label' => $request->post('label'),
            'description' => $request->post('description'),
            'avatar_url' => $avatar_url,
            'type' => 'group',
        ]);

        $conversation->participants()->attach($user->id, [
            'joined_at' => Carbon::now(),
            'role' => 'admin',
        ]);

        foreach ($request->post('participants') as $participant_id) {
            if ($participant_id != $user->id) {
                $conversation->participants()->attach($participant_id, [
                    'joined_at' => Carbon::now(),
                    'role' => 'member',
                ]);
            }
        }

        return $conversation->load([
            'lastMessage',
            'participants' => function ($builder) use ($user) {
                $builder->where('id', '<>', $user->id);
            },
        ])->loadCount([
            'recipients as new_messages' => function ($builder) use ($user) {
                $builder->where('recipients.user_id', '=', $user->id)
                    ->whereNull('read_at');
            }
        ]);
    }

    public function addParticipant(Request $request, Conversation $conversation)
    {
        $request->validate([
            'user_id' => ['required', 'int', 'exists:users,id'],
        ]);

        $conversation->participants()->attach($request->post('user_id'), [
            'joined_at' => Carbon::now(),
        ]);
    }

    public function removeParticipant(Request $request, Conversation $conversation)
    {
        $request->validate([
            'user_id' => ['required', 'int', 'exists:users,id'],
        ]);

        $conversation->participants()->detach($request->post('user_id'));
    }

    public function markAsRead($id)
    {
        // 1. حماية إضافية للـ ID
        if (!$id || $id == 'null' || $id == 0) {
            return response()->json(['message' => 'Invalid conversation ID'], 400);
        }

        // 2. التأكد من أن المستخدم مسجل دخول (إجراء إضافي)
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $updated = Recipient::where('user_id', '=', Auth::id())
            ->whereNull('read_at')
            ->whereRaw('message_id IN (
            SELECT id FROM messages WHERE conversation_id = ?
        )', [$id])
            ->update([
                'read_at' => Carbon::now(),
            ]);

        return response()->json([
            'message' => 'Messages marked as read',
            'updated_count' => $updated
        ]);
    }

    public function destroy($id)
    {
        if (!$id || $id == 0 || $id == 'null') {
            return response()->json(['message' => 'Nothing to delete'], 200);
        }

        Recipient::where('user_id', '=', Auth::id())
            ->whereRaw('message_id IN (
            SELECT id FROM messages WHERE conversation_id = ?
        )', [$id])
            ->delete();

        return [
            'message' => 'Conversation deleted',
        ];
    }
}
