<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container mx-auto p-8">
                    <h2 class="text-2xl font-bold mb-2">List of users</h2>
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-4 py-2 text-left w-12">#</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="border border-gray-300 px-4 py-2">{{ $loop->index + 1 }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $user->name }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $user->email }}</td>
                                        <td class="border border-gray-300 px-4 py-2 relative">
                                            <a navigate href="{{ route('chat', $user->id) }}"
                                                class="relative inline-flex">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                                </svg>
                                                <span id="unread-count-{{ $user->id }}"
                                                    class="{{ $user->unread_messages_count > 0 ? '' : 'hidden' }} absolute -right-2 -top-2 inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-xs font-bold text-white">
                                                    {{ $user->unread_messages_count }}
                                                </span>
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script type="module">
    window.Echo.private('unread-channel.{{ auth()->user()->id }}').listen('.unreadMessage', (event) => {
        // console.log('Unread message event:', event);
        // console.log('Sender ID:', event.senderId);
        // console.log('Unread message count:', event.unreadMessageCount);
        const unreadElementCount = document.getElementById(`unread-count-${event.senderId}`);
        if (unreadElementCount) {
            const unreadCount = Number(event.unreadMessageCount);

            unreadElementCount.textContent = unreadCount > 0 ? unreadCount : '';
            unreadElementCount.classList.toggle('hidden', unreadCount === 0);
        }
    });
</script>
