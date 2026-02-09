<div>
    <!-- Flash Message -->
    @if (session()->has('message'))
        <div
            class="mb-8 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-3 animate-fade-in-down">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('message') }}
        </div>
    @endif

    <!-- Dynamic Header -->
    <div
        class="mb-10 p-2 bg-slate-100 dark:bg-slate-900/50 rounded-[2rem] flex flex-col sm:flex-row justify-between items-center gap-4 border border-slate-200/50 dark:border-slate-800/50">
        <div class="flex items-center gap-2 p-1 bg-white dark:bg-slate-950 rounded-full shadow-sm">
            <button wire:click="setTab('assistants')"
                class="px-8 py-3 text-xs font-black uppercase tracking-widest transition-all rounded-full {{ $activeTab === 'assistants' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                Assistants
            </button>
            <button wire:click="setTab('conversations')"
                class="px-8 py-3 text-xs font-black uppercase tracking-widest transition-all rounded-full {{ $activeTab === 'conversations' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                Conversations
            </button>
        </div>

        @if($activeTab === 'assistants' && !auth()->user()->isTeamViewer())
            <button wire:click="toggleCreateForm"
                class="group px-8 py-3.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-full font-black text-xs uppercase tracking-widest shadow-xl transition-all duration-300 hover:scale-[1.05] active:scale-[0.95] flex items-center gap-2 mr-2">
                <svg class="w-4 h-4 transition-transform group-hover:rotate-90 duration-500" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>New Assistant</span>
            </button>
        @endif
    </div>

    <!-- Create Form -->
    @if($showCreateForm)
        <div class="mb-10 premium-card p-8 rounded-3xl animate-fade-in-down shadow-2xl bg-white/50 backdrop-blur-xl">
            <div class="flex items-center justify-between mb-8">
                <h4 class="text-xl font-bold text-slate-900 dark:text-white">New AI Assistant</h4>
                <button wire:click="toggleCreateForm"
                    class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="create" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Assistant
                                Name</label>
                            <input wire:model="name" type="text" placeholder="e.g. Sales Expert"
                                class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 outline-none">
                            @error('name') <span class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Primary
                                Color</label>
                            <div class="flex items-center gap-4">
                                <input wire:model="theme_color" type="color"
                                    class="w-16 h-16 rounded-2xl border-none cursor-pointer p-0 overflow-hidden shadow-lg">
                                <span class="text-sm font-mono text-slate-500 uppercase">{{ $theme_color }}</span>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">WhatsApp
                                Number (Optional)</label>
                            <input wire:model="whatsapp_number" type="text" placeholder="e.g. +1234567890"
                                class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 outline-none">
                            <p class="mt-1 text-[10px] text-slate-500 leading-relaxed italic">
                                Add your WhatsApp number to let users contact you directly from the chat.
                            </p>
                            @error('whatsapp_number') <span
                                class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">System
                            Instructions (Prompt)</label>
                        <textarea wire:model="system_prompt" rows="6"
                            placeholder="Tell your AI who it is and how it should behave..."
                            class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 outline-none resize-none"></textarea>
                        <p class="mt-2 text-[10px] text-slate-500 leading-relaxed italic">
                            [!TIP] We've pre-filled this based on your business profile to give you a head start!
                        </p>
                        @error('system_prompt') <span class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">
                        Upload AI Training Data (TXT, PDF, DOCX)</label>
                    <input type="file" wire:model="training_file" accept=".txt,.pdf,.doc,.docx"
                        class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 outline-none">
                    <p class="mt-2 text-[10px] text-slate-500 leading-relaxed italic">
                        Upload a file to train your chatbot with custom data. Supported formats: TXT, PDF, DOCX.
                    </p>
                    @error('training_file') <span class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label
                        class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Source
                        Website URL</label>
                    <input wire:model="website_url" type="url" placeholder="https://example.com"
                        class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 outline-none">
                    <p class="mt-2 text-[10px] text-slate-500 leading-relaxed italic">
                        The AI will learn from this website to answer customer questions.
                    </p>
                    @error('website_url') <span class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">
                        Allowed Domains (Optional)
                    </label>
                    <input wire:model="allowed_domains" type="text"
                        placeholder="example.com, www.example.com, subdomain.example.com"
                        class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 outline-none">
                    <p class="mt-2 text-[10px] text-slate-500 leading-relaxed">
                        <span class="font-bold">🔒 Security:</span> Enter domains where this chatbot can be embedded
                        (comma-separated).
                        Leave empty to allow all domains. Example: <code
                            class="bg-slate-200 dark:bg-slate-800 px-1 rounded">yourdomain.com, app.yourdomain.com</code>
                    </p>
                    @error('allowed_domains') <span class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-6">
                    <button type="button" wire:click="toggleCreateForm"
                        class="px-8 py-3.5 text-slate-500 font-bold hover:text-slate-700 dark:hover:text-white transition-colors">Cancel</button>
                    <button type="submit"
                        class="px-10 py-3.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-2xl shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                        Launch Assistant
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Chatbot List Mode -->
    @if($viewMode === 'list')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($chatbots as $bot)
                <div
                    class="premium-card rounded-[2.5rem] p-8 transition-all duration-500 hover:shadow-2xl group flex flex-col justify-between h-full bg-white dark:bg-slate-900/40 relative overflow-hidden border border-slate-200 dark:border-white/5">

                    <!-- Decorative Element -->
                    <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full blur-3xl opacity-10 group-hover:opacity-20 transition-opacity duration-700"
                        style="background-color: {{ $bot->theme_color }}"></div>

                    <div>
                        <!-- Card Header -->
                        <div class="flex justify-between items-start mb-8 relative">
                            <div class="flex items-center gap-5">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white shadow-xl group-hover:rotate-3 transition-transform duration-500"
                                    style="background-color: {{ $bot->theme_color }}; box-shadow: 0 10px 20px -5px {{ $bot->theme_color }}66">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-2xl font-black text-slate-900 dark:text-white truncate"
                                        title="{{ $bot->name }}">{{ $bot->name }}</h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <button @if(!auth()->user()->isTeamViewer()) wire:click="toggleActive({{ $bot->id }})"
                                        @else disabled @endif
                                            class="flex items-center gap-2 px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-full transition-all {{ !auth()->user()->isTeamViewer() ? 'hover:bg-slate-200' : 'opacity-70 cursor-not-allowed' }}">
                                            <span class="relative flex h-2 w-2">
                                                <span
                                                    class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $bot->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                                <span
                                                    class="relative inline-flex rounded-full h-2 w-2 {{ $bot->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                            </span>
                                            <span
                                                class="text-[10px] font-black uppercase tracking-widest text-slate-500">{{ $bot->is_active ? 'Active' : 'Paused' }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bot Specs -->
                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div
                                class="p-4 bg-slate-50 dark:bg-slate-950/50 rounded-2xl border border-slate-100 dark:border-white/5">
                                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Total Chats</p>
                                <p class="text-lg font-black text-slate-900 dark:text-white">
                                    {{ number_format($bot->conversations_count) }}
                                </p>
                            </div>
                            <div
                                class="p-4 bg-slate-50 dark:bg-slate-950/50 rounded-2xl border border-slate-100 dark:border-white/5">
                                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Visibility</p>
                                <p class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
                                    @if(!empty($bot->allowed_domains))
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        <span class="text-xs">Secure</span>
                                    @else
                                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18" />
                                        </svg>
                                        <span class="text-xs">Public</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Compact Actions -->
                    <div class="space-y-3 pt-6 border-t border-slate-100 dark:border-white/5">
                        <div class="flex gap-3">
                            @if(!auth()->user()->isTeamViewer())
                                <button wire:click="edit({{ $bot->id }})"
                                    class="flex-1 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-900 dark:text-white font-bold text-xs uppercase tracking-widest rounded-xl transition-all">
                                    Settings
                                </button>
                            @endif
                            <button wire:click="showInstall({{ $bot->id }})"
                                class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-indigo-600/20">
                                Deploy
                            </button>
                        </div>
                        <button wire:click="showConversations({{ $bot->id }})"
                            class="w-full py-3 border-2 border-slate-100 dark:border-slate-800 hover:border-indigo-600/50 text-slate-500 hover:text-indigo-600 font-bold text-xs uppercase tracking-widest rounded-xl transition-all">
                            Review Conversations
                        </button>
                        @if(!auth()->user()->isTeamViewer())
                            <button wire:click="delete({{ $bot->id }})" wire:confirm="Are you sure?"
                                class="w-full text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-rose-500 transition-colors py-2">
                                Terminate Assistant
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full premium-card rounded-[3rem] p-20 text-center border-dashed border-2 border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/10">
                    <div
                        class="w-32 h-32 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-10 shadow-2xl animate-bounce">
                        <svg class="w-16 h-16 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-4">Your AI Fleet is Empty</h3>
                    <p class="text-slate-500 max-w-sm mx-auto mb-10 font-medium">Ready to automate your customer support? Create
                        your first AI assistant in seconds.</p>
                    <button wire:click="toggleCreateForm"
                        class="px-10 py-5 bg-indigo-600 hover:bg-indigo-700 text-white font-black uppercase tracking-widest text-xs rounded-full shadow-2xl shadow-indigo-600/40 transition-all hover:scale-105 active:scale-95">
                        Initialize First Bot
                    </button>
                </div>
            @endforelse
        </div>

        <!-- Conversations List Mode -->
    @elseif($viewMode === 'conversations')
        <div class="animate-fade-in-down">
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
                <div class="flex items-center gap-4 sm:gap-6">
                    <button wire:click="backToList"
                        class="w-12 h-12 flex items-center justify-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 rounded-2xl text-slate-500 hover:text-indigo-600 transition-all shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </button>
                    <div>
                        <h3 class="text-3xl font-black text-slate-900 dark:text-white leading-none mb-2">Conversation
                            History</h3>
                        <p class="text-sm text-slate-500 font-medium">Reviewing logs for <span
                                class="text-indigo-600 font-bold">{{ $selectedBotName }}</span></p>
                    </div>
                </div>
            </div>

            <div
                class="premium-card rounded-[2.5rem] overflow-hidden border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-900/40 shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[700px]">
                        <thead class="bg-slate-100 dark:bg-slate-950/50 border-b border-slate-200 dark:border-white/5">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    Visitor
                                </th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    Origin
                                </th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    Volume
                                </th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    Activity
                                </th>
                                <th
                                    class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($conversations as $convo)
                                <tr class="hover:bg-white dark:hover:bg-slate-900 transition-colors group">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center font-black text-xs">
                                                {{ strtoupper(substr($convo->visitor_id, 0, 2)) }}
                                            </div>
                                            <div>
                                                <span
                                                    class="block text-sm font-black text-slate-900 dark:text-white leading-tight">{{ $convo->visitor_name ?? 'Anonymous' }}</span>
                                                <span
                                                    class="text-[10px] text-slate-400 font-mono">{{ Str::limit($convo->visitor_id, 12) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span
                                            class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ $convo->user_country ?? 'Unknown' }}</span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span
                                            class="px-3 py-1 bg-indigo-500/10 text-indigo-600 rounded-lg text-[10px] font-black uppercase tracking-widest">{{ $convo->messages_count }}
                                            Events</span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest">
                                            {{ $convo->updated_at->diffForHumans() }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <button wire:click="showMessages({{ $convo->id }})"
                                            class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-900 dark:hover:bg-white dark:hover:text-slate-900 transition-all focus:opacity-100 shadow-xl shadow-indigo-600/20">
                                            View Log
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <p class="text-slate-400 font-black uppercase tracking-[0.2em] text-[10px]">No
                                            interactions
                                            found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Messages Detail Mode -->
    @elseif($viewMode === 'messages')
            <div class="animate-fade-in-down">
                <div class="mb-8 flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
                    <div class="flex items-center gap-4 sm:gap-6">
                        <button wire:click="backToConversations"
                            class="w-12 h-12 flex items-center justify-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 rounded-2xl text-slate-500 hover:text-indigo-600 transition-all shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </button>
                        <div>
                            <h3 class="text-3xl font-black text-slate-900 dark:text-white leading-none mb-2">Detailed Log
                            </h3>
                            <p class="text-sm text-slate-500 font-medium">Session history for visitor <span
                                    class="text-indigo-600 font-bold">{{ Str::limit($selectedConversation->visitor_id, 12) }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="premium-card rounded-[2.5rem] overflow-hidden border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-slate-900/40 shadow-2xl">
                    <div class="p-8 h-[600px] overflow-y-auto space-y-6 custom-scrollbar">
                        @foreach($messages as $msg)
                            <div class="flex {{ $msg->role === 'user' ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[80%] space-y-2">
                                    <div
                                        class="px-6 py-4 rounded-[2rem] shadow-sm {{ $msg->role === 'user' ? 'bg-indigo-600 text-white rounded-tr-none shadow-indigo-500/20' : 'bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-200 rounded-tl-none border border-slate-100 dark:border-white/5' }}">
                                        <p class="text-sm leading-relaxed whitespace-pre-line">{{ $msg->content }}</p>
                                    </div>
                                    <div
                                        class="flex items-center gap-2 px-2 {{ $msg->role === 'user' ? 'justify-end' : 'justify-start' }}">
                                        <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest">
                                            {{ $msg->role === 'user' ? 'Visitor' : 'Assistant' }} •
                                            {{ $msg->created_at->format('h:i A') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Installation Modal -->
        @if($showInstallModal && $selectedBot)
            @teleport('body')
            <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-md flex items-center justify-center z-[100] p-4 animate-fade-in overflow-y-auto"
                wire:click="$set('showInstallModal', false)">
                <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] sm:rounded-[3rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.5)] p-8 sm:p-14 max-w-2xl w-full relative animate-fade-in-scale border border-white/10 my-auto"
                    wire:click.stop>
                    <button wire:click="$set('showInstallModal', false)"
                        class="absolute top-8 right-8 sm:top-10 sm:right-10 w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all bg-slate-100 dark:bg-slate-800 rounded-2xl">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="text-center mb-10 sm:mb-12">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 bg-indigo-600 rounded-[1.5rem] sm:rounded-[2rem] flex items-center justify-center text-white mx-auto mb-6 sm:mb-8 shadow-2xl shadow-indigo-600/30">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3
                            class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight mb-3 sm:mb-4">
                            Deploying <span class="text-indigo-600">{{ $selectedBot->name }}</span></h3>
                        <p class="text-sm sm:text-base text-slate-500 font-medium leading-relaxed max-w-sm mx-auto">Copy the
                            integration layer and
                            paste it before the closing <code
                                class="bg-indigo-500/10 text-indigo-600 px-2 py-0.5 rounded-lg">&lt;/body&gt;</code> tag.
                        </p>
                    </div>

                    <div class="group relative mb-8 sm:mb-10">
                        <div
                            class="absolute -inset-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-[2.5rem] blur opacity-20 group-hover:opacity-40 transition duration-700">
                        </div>
                        <div
                            class="relative bg-slate-900 rounded-[2rem] p-6 sm:p-8 font-mono text-sm border border-white/5">
                            <div class="flex justify-between items-center mb-6 pb-6 border-b border-white/5">
                                <span class="text-slate-500 text-[10px] font-black uppercase tracking-[0.3em]">Integration
                                    Script</span>
                                <button
                                    onclick="navigator.clipboard.writeText(this.closest('.relative').querySelector('code').textContent); this.querySelector('span').textContent = 'COPIED!'; setTimeout(() => this.querySelector('span').textContent = 'COPY CODE', 2000);"
                                    class="px-5 py-2.5 bg-white text-slate-900 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-100 transition-all shadow-xl">
                                    <span>COPY CODE</span>
                                </button>
                            </div>
                            <code
                                class="block text-indigo-400 break-all leading-loose text-[10px] sm:text-xs">&lt;script src="{{ config('app.url') }}/widget/embed.js?token={{ $selectedBot->token }}" data-bot-id="{{ $selectedBot->token }}"&gt;&lt;/script&gt;</code>
                        </div>
                    </div>

                    <div
                        class="flex items-center gap-6 p-6 bg-slate-50 dark:bg-slate-800/30 rounded-[2rem] border border-slate-100 dark:border-white/5">
                        <div
                            class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-600 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-[10px] sm:text-xs text-slate-500 font-medium leading-relaxed italic">Changes made to
                            your
                            assistant's
                            knowledge or branding update in real-time. No reinstalls required.</p>
                    </div>
                </div>
            </div>
            @endteleport
        @endif

        <!-- Edit Modal -->
        @if($showEditModal)
            @teleport('body')
            <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-md z-[100] p-4 animate-fade-in overflow-y-auto"
                wire:click="$set('showEditModal', false)">
                <div class="min-h-screen flex items-center justify-center py-8">
                    <div class="bg-white dark:bg-slate-900 rounded-[2rem] sm:rounded-[3rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.5)] max-w-5xl w-full relative animate-fade-in-scale border border-white/10"
                        wire:click.stop>

                        <!-- Close Button -->
                        <button wire:click="$set('showEditModal', false)"
                            class="absolute top-6 right-6 sm:top-10 sm:right-10 z-10 w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all bg-slate-100 dark:bg-slate-800 rounded-xl sm:rounded-2xl">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <!-- Modal Content -->
                        <div class="p-6 sm:p-14">
                            <div class="mb-8 sm:mb-12 pr-16">
                                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-600 mb-2">
                                    Configuration
                                    Studio</p>
                                <h3 class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                                    Refining <span class="text-indigo-600">{{ $name }}</span></h3>
                            </div>

                            <form wire:submit.prevent="update" class="space-y-12">
                                <!-- Identity & Aesthetics -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                    <div class="space-y-6">
                                        <div>
                                            <label
                                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 px-1">Assistant
                                                Name</label>
                                            <input wire:model="name" type="text" placeholder="e.g. Sales Expert"
                                                class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-white/5 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none font-bold">
                                            @error('name') <span
                                                class="text-rose-500 text-[10px] mt-2 block font-black">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div>
                                            <label
                                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 px-1">Branding
                                                Color</label>
                                            <div
                                                class="flex items-center gap-6 p-4 bg-slate-50 dark:bg-slate-950/50 rounded-2xl border border-slate-200 dark:border-white/5">
                                                <input wire:model="theme_color" type="color"
                                                    class="w-14 h-14 rounded-xl border-none cursor-pointer p-0 overflow-hidden shadow-lg shadow-black/20">
                                                <div>
                                                    <span
                                                        class="block text-sm font-black text-slate-900 dark:text-white font-mono uppercase">{{ $theme_color }}</span>
                                                    <span
                                                        class="text-[10px] text-slate-400 font-bold tracking-wider">Interface
                                                        Highlight</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 px-1">WhatsApp
                                                Number (Optional)</label>
                                            <input wire:model="whatsapp_number" type="text" placeholder="e.g. +1234567890"
                                                class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-white/5 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none font-bold">
                                            <p class="mt-2 text-[10px] text-slate-400 font-medium px-1">Enables a direct
                                                WhatsApp button in the chatbot.</p>
                                            @error('whatsapp_number') <span
                                                class="text-rose-500 text-[10px] mt-2 block font-black">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 px-1">Global
                                            Intelligence (System Prompt)</label>
                                        <textarea wire:model="system_prompt" rows="7"
                                            placeholder="Tell your AI who it is..."
                                            class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-white/5 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none resize-none text-sm leading-relaxed"></textarea>
                                        @error('system_prompt') <span
                                            class="text-rose-500 text-[10px] mt-2 block font-black">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Domains & Intelligence Sources -->
                                <div class="space-y-10 pt-10 border-t border-slate-100 dark:border-white/5">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                        <div>
                                            <label
                                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 px-1">Source
                                                Website URL</label>
                                            <input wire:model="website_url" type="url" placeholder="https://example.com"
                                                class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-white/5 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none font-bold">
                                            <p class="mt-3 text-[10px] text-slate-400 font-medium px-1">Primary source for
                                                the
                                                knowledge engine.</p>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 px-1">Deployment
                                                Access (CORS)</label>
                                            <input wire:model="allowed_domains" type="text"
                                                placeholder="domain1.com, domain2.com"
                                                class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-white/5 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none font-bold">
                                            <p class="mt-3 text-[10px] text-slate-400 font-medium px-1">Comma-separated list
                                                of
                                                authorised domains.</p>
                                        </div>
                                    </div>

                                    <div
                                        class="p-8 bg-indigo-600/5 rounded-3xl border border-indigo-600/10 flex items-center justify-between">
                                        <div class="flex items-center gap-6">
                                            <div
                                                class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-xl">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h4
                                                    class="text-lg font-black text-slate-900 dark:text-white leading-none mb-1">
                                                    Autonomous Sync</h4>
                                                <p class="text-xs text-slate-500 font-medium italic">Continuously crawl the
                                                    source
                                                    URL for information.</p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" wire:model="auto_crawl_enabled" class="sr-only peer">
                                            <div
                                                class="w-16 h-9 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-800 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[4px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-7 after:w-7 after:transition-all dark:border-slate-600 peer-checked:bg-indigo-600 shadow-inner">
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Knowledge Base Entries -->
                                <div class="space-y-8 pt-10 border-t border-slate-100 dark:border-white/5 mb-10">
                                    <div>
                                        <h4 class="text-2xl font-black text-slate-900 dark:text-white leading-none mb-2">
                                            Immutable
                                            Facts</h4>
                                        <p class="text-sm text-slate-500 font-medium">Specific rules that override or
                                            complement
                                            the
                                            crawl data.</p>
                                    </div>

                                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                                        <div class="lg:col-span-12 space-y-6">
                                            <div
                                                class="p-8 bg-slate-50 dark:bg-slate-950 rounded-[2rem] border border-slate-200 dark:border-white/5">
                                                <label
                                                    class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4">New
                                                    Interaction Rule</label>
                                                <textarea wire:model="manual_content" rows="4"
                                                    placeholder="Example: Always refer to the user as 'Captain'..."
                                                    class="w-full px-5 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none text-sm resize-none"></textarea>
                                                <div class="flex justify-end mt-6">
                                                    <button type="button" wire:click="addManualKnowledge"
                                                        class="px-10 py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-black text-[10px] uppercase tracking-widest rounded-xl hover:scale-[1.02] active:scale-[0.98] transition-all shadow-xl">
                                                        Register Entry
                                                    </button>
                                                </div>
                                                @error('manual_content') <span
                                                    class="text-rose-500 text-[10px] mt-3 block font-black text-center">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="lg:col-span-12 space-y-4">
                                            @forelse($knowledgeEntries as $entry)
                                                <div
                                                    class="group flex items-start gap-4 p-5 bg-white dark:bg-slate-800/20 rounded-[1.5rem] border border-slate-100 dark:border-white/5 hover:border-indigo-600/30 transition-all">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-600 mt-2 shrink-0"></div>
                                                    <div class="flex-1 min-w-0">
                                                        <p
                                                            class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
                                                            {{ Str::limit($entry->content, 200) }}
                                                        </p>
                                                        <p
                                                            class="text-[9px] text-slate-400 mt-2 uppercase tracking-widest font-black">
                                                            Verified Sources • {{ $entry->created_at->diffForHumans() }}</p>
                                                    </div>
                                                    <button type="button" wire:click="deleteKnowledgeEntry({{ $entry->id }})"
                                                        class="opacity-0 group-hover:opacity-100 w-10 h-10 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-500/10 rounded-xl transition-all shrink-0">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @empty
                                                <div
                                                    class="p-10 text-center border-2 border-dashed border-slate-100 dark:border-slate-800 rounded-[2.5rem]">
                                                    <p class="text-xs text-slate-400 font-bold italic tracking-wider">No manual
                                                        overrides active.</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex justify-end gap-6 pt-8 mt-8 border-t border-slate-200 dark:border-white/5">
                                    <button type="button" wire:click="$set('showEditModal', false)"
                                        class="px-8 py-3 dark:text-slate-400 text-slate-500 font-black text-[10px] uppercase tracking-widest hover:text-slate-900 transition-colors">
                                        Discard
                                    </button>
                                    <button type="submit"
                                        class="px-12 py-3 bg-indigo-600 text-white font-black text-[10px] uppercase tracking-widest rounded-xl shadow-2xl shadow-indigo-600/30 hover:scale-[1.05] active:scale-[0.95] transition-all">
                                        Sync Configuration
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endteleport
        @endif

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap');

            :host {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            .custom-scrollbar::-webkit-scrollbar {
                width: 6px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(99, 102, 241, 0.1);
                border-radius: 20px;
                border: 2px solid transparent;
                background-clip: content-box;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: rgba(99, 102, 241, 0.3);
            }

            .animate-fade-in {
                animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .animate-fade-in-down {
                animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .animate-fade-in-scale {
                animation: fadeInScale 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .animate-float {
                animation: float 6s ease-in-out infinite;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            @keyframes fadeInDown {
                from {
                    opacity: 0;
                    transform: translateY(-40px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes fadeInScale {
                from {
                    opacity: 0;
                    transform: scale(0.92) translateY(30px);
                }

                to {
                    opacity: 1;
                    transform: scale(1) translateY(0);
                }
            }

            @keyframes float {
                0% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-10px);
                }

                100% {
                    transform: translateY(0px);
                }
            }

            .premium-card {
                transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .premium-card:hover {
                transform: translateY(-5px);
            }

            input[type="color"]::-webkit-color-swatch-wrapper {
                padding: 0;
            }

            input[type="color"]::-webkit-color-swatch {
                border: none;
                border-radius: 12px;
            }
        </style>
    </div>