<?php

use App\Models\Conversation;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public $users = [];

    public function mount()
    {
        $this->users = User::where('id', '!=', auth()->id())->get()
    }

    public function message($userId)
    {
        $authenticatedUserId = auth()->id();

        // Check if conversation already exists
        $existingConversation = Conversation::where(function ($query) use ($authenticatedUserId, $userId) {
            $query->where('sender_id', $authenticatedUserId)
                ->where('receiver_id', $userId);
        })
            ->orWhere(function ($query) use ($authenticatedUserId, $userId) {
                $query->where('sender_id', $userId)
                    ->where('receiver_id', $authenticatedUserId);
            })->first();

        if ($existingConversation) {
            // Conversation already exists, redirect to existing conversation
            return redirect()->route('chat', ['query' => $existingConversation->id]);
        }

        // Create new conversation
        $createdConversation = Conversation::create([
            'sender_id'   => $authenticatedUserId,
            'receiver_id' => $userId,
        ]);

        return redirect()->route('chat', ['query' => $createdConversation->id]);
    }
}; ?>

<div class="max-w-6xl mx-auto my-16">
    <h5 class="text-center text-5xl font-bold py-3">Users</h5>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 p-2 ">

        @foreach ($users as $user)
            <div class="w-full bg-white border border-gray-200 rounded-lg p-5 shadow">
                <div class="flex flex-col items-center pb-10">

                    <img src="https://i.pravatar.cc/300?img={{ $user->id }}" alt="image"
                         class="w-24 h-24 mb-2 5 rounded-full shadow-lg">

                    <h5 class="mb-1 text-xl font-medium text-gray-900 ">
                        {{ $user->name }}
                    </h5>

                    <span class="text-sm text-gray-500">{{ $user->email }} </span>

                    <div class="flex mt-4 space-x-3 md:mt-6">
                        <x-secondary-button>
                            Add Friend
                        </x-secondary-button>

                        <x-primary-button wire:click="message({{ $user->id }})">
                            Message
                        </x-primary-button>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
</div>
