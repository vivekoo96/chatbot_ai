<div class="min-h-[calc(100vh-100px)] lg:h-[calc(100vh-100px)] flex flex-col lg:flex-row gap-4 lg:gap-6"
    wire:poll.5s="loadMessages">
    <!-- Sidebar List -->
    <div
        class="w-full lg:w-1/3 flex flex-col bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-200 dark:border-slate-800/50 shadow-xl overflow-hidden max-h-[40vh] lg:max-h-none">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800/50">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Live Conversations</h3>

            <div class="flex gap-2">
                <button wire:click="$set('filter', 'all'); loadConversations()"
                    class="px-3 py-1 text-xs font-bold rounded-full transition-colors {{ $filter === 'all' ? 'bg-indigo-500 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-500' }}">
                    All
                </button>
                <button wire:click="$set('filter', 'manual'); loadConversations()"
                    class="px-3 py-1 text-xs font-bold rounded-full transition-colors {{ $filter === 'manual' ? 'bg-indigo-500 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-500' }}">
                    Manual Only
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-2">
            @foreach($conversations as $conv)
                <button wire:click="selectConversation({{ $conv->id }})"
                    class="w-full text-left p-4 rounded-2xl transition-all border {{ $selectedConversation?->id === $conv->id ? 'bg-indigo-50 dark:bg-indigo-500/10 border-indigo-200 dark:border-indigo-500/30' : 'bg-transparent border-transparent hover:bg-slate-50 dark:hover:bg-slate-800/50' }}">
                    <div class="flex justify-between items-start mb-1">
                        <span class="font-bold text-slate-800 dark:text-white truncate">{{ $conv->visitor_id }}</span>
                        @if($conv->is_manual_mode)
                            <span
                                class="px-2 py-0.5 text-[10px] font-black uppercase bg-amber-100 text-amber-600 rounded-full">Manual</span>
                        @else
                            <span
                                class="px-2 py-0.5 text-[10px] font-black uppercase bg-emerald-100 text-emerald-600 rounded-full">AI</span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                        {{ $conv->messages->last()?->content ?? 'No messages yet' }}
                    </p>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-[10px] font-bold text-slate-400">{{ $conv->chatbot->name }}</span>
                        <span class="text-[10px] text-slate-400">{{ $conv->updated_at->diffForHumans() }}</span>
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Chat Area -->
    <div
        class="w-full lg:w-2/3 flex flex-col bg-white dark:bg-slate-900/40 rounded-3xl border border-slate-200 dark:border-slate-800/50 shadow-xl overflow-hidden relative min-h-[50vh] lg:min-h-0">
        @if($selectedConversation)
            <!-- Chat Header -->
            <div class="p-6 border-b border-slate-100 dark:border-slate-800/50 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        {{ $selectedConversation->visitor_id }}
                        <span class="text-xs font-normal text-slate-500">{{ $selectedConversation->chatbot->name }}</span>
                    </h3>
                    <p class="text-xs text-slate-500 truncate max-w-md">{{ $selectedConversation->detected_url }}</p>
                </div>

                <button wire:click="toggleManualMode"
                    class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wide transition-all {{ $selectedConversation->is_manual_mode ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30 hover:bg-amber-600' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                    {{ $selectedConversation->is_manual_mode ? 'Switch to Auto (AI)' : 'Take Over (Manual)' }}
                </button>
            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-y-auto p-6 flex flex-col-reverse gap-y-6">
                <!-- Using flex-col-reverse with DESC ordered messages correctly places newest at bottom -->
                @foreach($conversationMessages as $msg)
                    <div class="flex {{ $msg->role === 'user' ? 'justify-start' : 'justify-end' }}">
                        <div
                            class="max-w-[80%] {{ $msg->role === 'user' ? 'bg-slate-100 dark:bg-slate-800 rounded-r-2xl rounded-bl-2xl' : 'bg-indigo-600 text-white rounded-l-2xl rounded-br-2xl' }} p-4 shadow-sm">
                            <p class="text-sm font-medium">{{ $msg->content }}</p>
                            <p
                                class="text-[10px] mt-1 opacity-70 {{ $msg->role === 'user' ? 'text-slate-500' : 'text-indigo-200' }}">
                                {{ $msg->created_at->format('g:i A') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-slate-50 dark:bg-slate-800/30 border-t border-slate-100 dark:border-slate-800/50">
                @if($selectedConversation->is_manual_mode)
                    <form wire:submit.prevent="sendMessage" class="relative">
                        <input type="text" wire:model="newMessage" placeholder="Type a message as operator..."
                            class="w-full pl-4 pr-12 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none shadow-sm dark:text-white transition-all">
                        <button type="submit"
                            class="absolute right-2 top-2 p-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/20">
                            <svg class="w-5 h-5 translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </form>
                @else
                    <div
                        class="text-center py-3 bg-slate-100 dark:bg-slate-800 rounded-xl border border-dashed border-slate-300 dark:border-slate-700">
                        <p class="text-sm font-bold text-slate-500">AI is handling this conversation.</p>
                        <button wire:click="toggleManualMode"
                            class="text-xs text-indigo-600 dark:text-indigo-400 font-bold hover:underline mt-1">Take
                            Over</button>
                    </div>
                @endif
            </div>

        @else
            <div class="flex-1 flex flex-col items-center justify-center text-slate-400">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <p class="font-bold">Select a conversation to start chatting</p>
            </div>
        @endif
    </div>
</div>