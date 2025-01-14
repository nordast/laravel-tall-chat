<?php

use App\Models\Conversation;
use App\Models\Message;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {

    public $query;
    public $selectedConversation;

    public function mount()
    {
        $this->selectedConversation = Conversation::findOrFail($this->query);

        #mark message belonging to receiver as read
        Message::where('conversation_id', $this->selectedConversation->id)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}; ?>

<div class="fixed h-full flex bg-white border lg:shadow-sm overflow-hidden inset-0 lg:top-16 lg:inset-x-2 m-auto lg:h-[90%] rounded-t-lg">
    <div class="hidden lg:flex relative w-full md:w-[320px] xl:w-[400px] overflow-y-auto shrink-0 h-full border">
        <livewire:chat.list :$selectedConversation :$query />
    </div>

    <div class="grid w-full border-l h-full relative overflow-y-auto" style="contain:content">
        <livewire:chat.box :$selectedConversation />
    </div>
</div>
