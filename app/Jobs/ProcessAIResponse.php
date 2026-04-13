<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessAIResponse implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $conversation;
    protected $userMessage;

    /**
     * Create a new job instance.
     */
    public function __construct($conversation, $userMessage)
    {
        $this->conversation = $conversation;
        $this->userMessage = $userMessage;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Illuminate\Support\Facades\Log::info("AI Response Job Started for Conversation: " . $this->conversation->id);

        $aiUser = $this->conversation->participants()->where('is_ai', true)->first();
        if (!$aiUser) {
            \Illuminate\Support\Facades\Log::info("AI User not found in participants.");
            return;
        }

        $user = \App\Models\User::find($this->userMessage->user_id);
        \Illuminate\Support\Facades\Log::info("Responding to User: " . $user->name);

        // Start typing indicator
        broadcast(new \App\Events\AITypingEvent($user->id, $this->conversation->id, true));

        // Fetch context (last 10 messages)
        $messages = $this->conversation->messages()
            ->with('user')
            ->latest()
            ->limit(10)
            ->get()
            ->reverse();

        $contents = [];
        foreach ($messages as $msg) {
            $role = ($msg->user_id == $aiUser->id) ? 'model' : 'user';
            $contents[] = [
                'role' => $role,
                'parts' => [['text' => $msg->body]]
            ];
        }

        try {
            \Illuminate\Support\Facades\Log::info("Calling Gemini API...");
            $model = config('services.gemini.model', 'gemini-1.5-flash');
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . config('services.gemini.key');

            $response = \Illuminate\Support\Facades\Http::post($url, [
                'contents' => $contents,
                'systemInstruction' => [
                    'parts' => [['text' => 'You are Messenger AI, a helpful and friendly assistant.']]
                ]
            ]);

            if ($response->successful()) {
                \Illuminate\Support\Facades\Log::info("Gemini API call successful.");
                $data = $response->json();
                $aiText = $data['candidates'][0]['content']['parts'][0]['text'] ?? "I'm sorry, I couldn't process that.";

                $aiMessage = $this->conversation->messages()->create([
                    'user_id' => $aiUser->id,
                    'type' => 'text',
                    'body' => $aiText,
                ]);

                // Add recipients
                \Illuminate\Support\Facades\DB::statement('
                    INSERT INTO recipients (user_id, message_id)
                    SELECT user_id, ? FROM participants
                    WHERE conversation_id = ?
                    AND user_id <> ?
                ', [$aiMessage->id, $this->conversation->id, $aiUser->id]);

                $this->conversation->update(['last_message_id' => $aiMessage->id]);

                $aiMessage->load('user');
                broadcast(new \App\Events\MessageCreated($aiMessage));
                \Illuminate\Support\Facades\Log::info("AI Message broadcasted.");
            } else {
                \Illuminate\Support\Facades\Log::error("Gemini API Error Response: " . $response->body());
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Gemini API Exception: " . $e->getMessage());
        } finally {
            // Stop typing indicator
            broadcast(new \App\Events\AITypingEvent($user->id, $this->conversation->id, false));
        }
    }
}
