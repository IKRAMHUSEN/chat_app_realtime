<?php

namespace Tests\Feature;

use App\Livewire\Chat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_file_only_message_uses_an_empty_message_value(): void
    {
        Storage::fake('public');
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        $file = UploadedFile::fake()->create('coke.jpg', 10, 'image/jpeg');

        $chat = new Chat;
        $chat->senderId = $sender->id;
        $chat->receiverId = $receiver->id;
        $chat->message = null;
        $chat->file = $file;

        $chat->saveMessage();

        $this->assertDatabaseHas('messages', [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'message' => '',
            'file_original_name' => 'coke.jpg',
        ]);
    }
}
