<div class="h-[calc(100vh-136px)] bg-[#f1f3f6]">
    <x-slot name="header">
        <h2 class="text-2xl font-semibold tracking-tight text-gray-900">{{ __($user->name) }}</h2>
    </x-slot>

    <div class="mx-auto flex h-full max-w-7xl flex-col px-6 py-10 sm:px-10 lg:px-14">
        <div id="chat-container" class="min-h-0 flex-1 overflow-y-auto pr-2">
            <div class="flex-1 space-y-8">

                @foreach ($messages as $message)
                    {{-- if sender user is not auth user --}}
                    @if ($message->sender->id !== auth()->user()->id)
                        {{-- receiver --}}
                        <div class="flex items-start gap-4">
                            <img src="https://i.pravatar.cc/80?img=47" alt="Shanay cruz"
                                class="mt-1 h-12 w-12 rounded-full object-cover ring-4 ring-white">
                            <div class="max-w-xl">
                                <p class="mb-3 text-base font-semibold text-gray-900">{{ $message->sender->name }}</p>
                                <p class="text-lg leading-7 text-gray-800">{{ $message->message }}</p>
                                <p class="mt-3 text-sm text-gray-500">{{ $message->created_at->format('h:i A') }}</p>


                            </div>
                        </div>
                    @else
                        {{-- sender --}}
                        <div class="flex justify-end">
                            <div class="flex max-w-xl items-start gap-4">
                                <div class="flex flex-col items-end gap-2">
                                    <p class="text-base font-semibold text-gray-900">You</p>
                                    <p
                                        class="rounded-md bg-indigo-600 px-4 py-2 text-lg leading-7 text-white shadow-sm">
                                        {{ $message->message }}
                                    </p>
                                    <p class="self-start text-sm text-gray-500">
                                        {{ $message->created_at->format('h:i A') }}
                                    </p>

                                </div>
                                <img src="https://i.pravatar.cc/80?img=12" alt="John Doe"
                                    class="mt-1 h-12 w-12 rounded-full object-cover ring-4 ring-white">
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

        </div>

        <form wire:submit="sendMessage" class="mt-10 flex shrink-0 items-center gap-3 border-t border-gray-200 pt-3">
            <button type="submit" aria-label="Add message"
                class="flex h-10 w-10 items-center justify-center rounded-full border border-indigo-100 bg-white text-2xl font-light text-indigo-700 shadow-sm">
                +
            </button>
            <input id="message-input" wire:keydown="userTyping" wire:model="message" type="text"
                placeholder="Type here..." aria-label="Type a message"
                class="h-10 flex-1 border border-gray-400 bg-white px-4 text-sm text-gray-800 outline-none placeholder:text-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            <button type="submit"
                class="rounded-full bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                Send
            </button>
        </form>
    </div>
</div>

@script
    <script>
        let typingTimeout = null;
        window.Echo.private(`chat-channel.{{ $senderId }}`).listen('.userTyping', (event) => {
            // console.log('User typing sender ID:', event.senderId);
            const messageInput = document.getElementById('message-input');

            if (messageInput) {
                messageInput.placeholder = 'Typing...';

            }
            clearTimeout(typingTimeout);
            typingTimeout = setTimeout(() => {
                if (messageInput) {
                    messageInput.placeholder = 'Type here...';
                }
            }, 2000);
        });

        const scrollToBottom = () => {
            requestAnimationFrame(() => {
                const chatContainer = document.getElementById('chat-container');

                if (chatContainer) {
                    chatContainer.scrollTo({
                        top: chatContainer.scrollHeight,
                        behavior: 'smooth',
                    });
                }
            });
        };

        $wire.on('messages-updated', scrollToBottom);
        scrollToBottom();
    </script>
@endscript
