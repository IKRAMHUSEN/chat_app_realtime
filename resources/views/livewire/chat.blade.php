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
                            {{-- <img src="https://i.pravatar.cc/80?img=47" alt="Shanay cruz"
                                class="mt-1 h-12 w-12 rounded-full object-cover ring-4 ring-white"> --}}
                            <img src="https://i.pravatar.cc/80?img=47" alt="{{ $message->sender->name }}"
                                class="mt-1 h-12 w-12 rounded-full object-cover ring-4 ring-white">
                            <div class="max-w-xl">
                                <p class="mb-3 text-base font-semibold text-gray-900">{{ $message->sender->name }}</p>
                                @if ($message->message)
                                    <p class="text-lg leading-7 text-gray-800">{{ $message->message }}</p>
                                @endif
                                @if ($message->folder_path)
                                    <a href="{{ Storage::disk('public')->url($message->folder_path) }}"
                                        download="{{ $message->file_original_name }}" class="mt-2 block">
                                        @if (str_starts_with($message->file_type ?? '', 'image/'))
                                            <img class="h-20 w-20 rounded-lg object-cover shadow-md"
                                                src="{{ Storage::disk('public')->url($message->folder_path) }}"
                                                alt="Attached image">
                                        @else
                                            <div
                                                class="flex max-w-64 items-center justify-between gap-3 rounded bg-indigo-400 px-3 py-2 text-sm text-gray-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="h-5 w-5 shrink-0">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94a3 3 0 1 1 4.243 4.243l-10.94 10.94a1.5 1.5 0 0 1-2.121-2.121l9.525-9.525" />
                                                </svg>
                                                <span class="break-all">
                                                    {{ $message->file_original_name }}
                                                </span>
                                            </div>
                                        @endif
                                    </a>
                                @endif
                                <p class="mt-3 text-sm text-gray-500">{{ $message->created_at->format('h:i A') }}</p>


                            </div>
                        </div>
                    @else
                        {{-- sender --}}
                        <div class="flex justify-end">
                            <div class="flex max-w-xl items-start gap-4">
                                <div class="flex flex-col items-end gap-2">
                                    <p class="text-base font-semibold text-gray-900">You</p>
                                    {{-- message --}}
                                    @if ($message->message)
                                        <p
                                            class="rounded-md bg-indigo-600 px-4 py-2 text-lg leading-7 text-white shadow-sm">
                                            {{ $message->message }}
                                        </p>
                                    @endif
                                    @if ($message->folder_path)
                                        <a href="{{ Storage::disk('public')->url($message->folder_path) }}"
                                            download="{{ $message->file_original_name }}" class="block">
                                            @if (str_starts_with($message->file_type ?? '', 'image/'))
                                                <img class="h-20 w-20 rounded-lg object-cover shadow-md"
                                                    src="{{ Storage::disk('public')->url($message->folder_path) }}"
                                                    alt="Attached image">
                                            @else
                                                <div
                                                    class="flex items-center  justify-between px-3 py-2 bg-indigo-400 rounded">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="h-5 w-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94a3 3 0 1 1 4.243 4.243l-10.94 10.94a1.5 1.5 0 0 1-2.121-2.121l9.525-9.525" />
                                                    </svg>
                                                    <span>
                                                        {{ $message->file_original_name }}
                                                    </span>
                                                </div>
                                            @endif
                                        </a>
                                    @endif
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

        <form wire:submit="sendMessage"
            class="mt-10 flex shrink-0 items-center gap-2 border-t border-gray-200 pt-3 sm:gap-3">
            {{-- <button type="submit" aria-label="Add message"
                class="flex h-10 w-10 items-center justify-center rounded-full border border-indigo-100 bg-white text-2xl font-light text-indigo-700 shadow-sm">
                +
            </button> --}}

            {{-- type a message --}}
            <input id="message-input" wire:keydown="userTyping" wire:model="message" type="text"
                placeholder="Type here..." aria-label="Type a message"
                class="h-10 min-w-0 flex-1 rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-800 shadow-sm outline-none placeholder:text-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">

            {{-- file attachment --}}
            <label for="fileAttachment"
                class="flex h-10 w-10 shrink-0 cursor-pointer items-center justify-center rounded-full border border-indigo-100 bg-white text-indigo-700 shadow-sm transition hover:bg-indigo-50">
                <input wire:model="file" type="file" class="hidden" id="fileAttachment" />
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94a3 3 0 1 1 4.243 4.243l-10.94 10.94a1.5 1.5 0 0 1-2.121-2.121l9.525-9.525" />
                </svg>
            </label>

            {{-- send button --}}
            <button type="submit"
                class="shrink-0 rounded-full bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 sm:px-6">
                Send
            </button>

            @if ($file)
                @php
                    $isImage = str_starts_with($file->getMimeType(), 'image/') ? true : false;
                @endphp
                @if ($isImage)
                    <img src="{{ $file->temporaryUrl() }}"
                        class="h-10 w-10 shrink-0 rounded-lg border border-gray-300 object-cover shadow-md">
                @else
                    <span class="w-full max-w-64">{{ $file->getClientOriginalName() }}</span>
                @endif
                <button type="button" wire:click="$set('file', null)" class="text-red-400 ms-2 text-xl">X</button>
            @endif
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

        const scrollToBottom = (behavior = 'smooth') => {
            requestAnimationFrame(() => {
                const chatContainer = document.getElementById('chat-container');

                if (chatContainer) {
                    chatContainer.scrollTo({
                        top: chatContainer.scrollHeight,
                        behavior,
                    });
                }
            });
        };

        $wire.on('messages-updated', () => scrollToBottom('smooth'));
        scrollToBottom('auto');
    </script>
@endscript
