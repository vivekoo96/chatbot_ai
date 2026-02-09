<div class="flex flex-col h-full bg-white text-slate-900 overflow-hidden font-sans"
    style="background-color: white !important;" wire:poll.5s="loadMessages">
    <style>
        :root {
            --bot-primary:
                {{ $chatbot->theme_color ?? '#9333ea' }}
            ;
            --bot-primary-dark:
                {{ $chatbot->theme_color ? $chatbot->theme_color . 'cc' : '#7e22ce' }}
            ;
            --bot-primary-light:
                {{ $chatbot->theme_color ? $chatbot->theme_color . '20' : '#f3e8ff' }}
            ;

            @keyframes wave {
                0% {
                    height: 20%;
                }

                50% {
                    height: 100%;
                }

                100% {
                    height: 20%;
                }
            }

            .recording-wave-bar {
                animation: wave 1s ease-in-out infinite;
                background-color: var(--bot-primary);
                width: 4px;
                border-radius: 9999px;
            }
        }
    </style>

    <!-- Clean Purple Header -->
    <div x-data="{ 
        menuOpen: false, 
        clearAutoPlayBlock() {
            if (window.speechSynthesis) {
                const dummy = new SpeechSynthesisUtterance('');
                dummy.volume = 0;
                window.speechSynthesis.speak(dummy);
            }
        }
    }" @click="clearAutoPlayBlock()" class="relative flex-none z-30"
        style="background: linear-gradient(to right, var(--bot-primary), var(--bot-primary-dark)); height: 72px;">


        <!-- Notification Toast (Alpine JS) -->
        <div x-data="{ show: false, message: '', type: 'success' }"
            x-on:notify.window="show = true; message = $event.detail[0].message; type = $event.detail[0].type; setTimeout(() => show = false, 3000)"
            x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="absolute top-16 left-1/2 -translate-x-1/2 z-50 w-[90%] pointer-events-none">
            <div :class="type === 'success' ? 'bg-green-500' : 'bg-rose-500'"
                class="px-4 py-2 rounded-xl text-white text-xs font-bold text-center shadow-xl backdrop-blur-md bg-opacity-90">
                <span x-text="message"></span>
            </div>
        </div>


        <!-- Header Content -->
        <div class="relative z-20 px-5 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <!-- Back Arrow Icon -->
                <button onclick="window.parent.postMessage('closeChatbot', '*')"
                    class="text-white hover:opacity-70 transition-opacity">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Title -->
                <h3 class="font-bold text-white text-base sm:text-lg tracking-tight">Customer Support</h3>
            </div>

            <!-- Menu & WhatsApp Buttons -->
            <div class="flex items-center gap-1.5 sm:gap-2">


                <div class="relative">
                    <button @click="menuOpen = !menuOpen"
                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all shrink-0">
                        <svg x-show="!menuOpen" class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg x-show="menuOpen" class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="menuOpen" @click.away="menuOpen = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        class="absolute right-0 mt-3 w-64 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-slate-700 py-2 z-50">



                        <button wire:click="emailTranscript" @click="menuOpen = false"
                            class="w-full px-4 py-3 flex items-center gap-3 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                            <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Email transcript
                        </button>



                        <!-- Language Selector -->
                        <div x-data="{ langOpen: false }" class="relative">
                            <button
                                @click="langOpen = !langOpen; if(langOpen) $nextTick(() => $refs.langSearch.focus())"
                                type="button"
                                class="w-full px-4 py-3 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                <div
                                    class="flex items-center gap-3 text-sm font-medium text-slate-700 dark:text-slate-200">
                                    <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Language
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span
                                        class="text-[10px] font-bold text-slate-400 uppercase">{{ $languages[$selectedLanguage]['name'] }}</span>
                                    <svg class="w-3 h-3 text-slate-400 transition-transform"
                                        :class="langOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </button>

                            <div x-show="langOpen" x-transition @click.away="langOpen = false"
                                class="bg-slate-50 dark:bg-slate-900 border-y border-slate-100 dark:border-slate-800 flex flex-col">

                                <!-- Search Input -->
                                <div
                                    class="p-2 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-800 sticky top-0 z-10">
                                    <div class="relative">
                                        <input type="text" wire:model.live="languageSearch" x-ref="langSearch"
                                            placeholder="Search language..."
                                            class="w-full pl-8 pr-3 py-1.5 text-xs bg-slate-50 dark:bg-slate-900 border-none rounded-lg focus:ring-1 focus:ring-purple-500 outline-none text-slate-600 dark:text-slate-300">
                                        <svg class="w-3.5 h-3.5 absolute left-2.5 top-2 text-slate-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="max-h-56 overflow-y-auto">
                                    @forelse(collect($languages)->filter(fn($l) => empty($languageSearch) || str_contains(strtolower($l['name']), strtolower($languageSearch))) as $code => $lang)
                                        <button type="button" wire:click="setLanguage('{{ $code }}')"
                                            @click="langOpen = false"
                                            class="w-full px-6 py-2.5 flex items-center justify-between text-xs font-medium hover:bg-white dark:hover:bg-slate-800 transition-colors {{ $selectedLanguage === $code ? 'text-[var(--bot-primary)] bg-white dark:bg-slate-800' : 'text-slate-600 dark:text-slate-400' }}">
                                            <span class="flex items-center gap-2">
                                                <x-icon name="globe" class="w-3.5 h-3.5 opacity-50" />
                                                {{ $lang['name'] }}
                                            </span>
                                            @if($selectedLanguage === $code)
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </button>
                                    @empty
                                        <div class="px-6 py-4 text-center text-xs text-slate-400 italic">No languages found
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100 dark:bg-slate-700 my-1 mx-4"></div>

                        <button wire:click="toggleSound"
                            class="w-full px-4 py-3 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                            <div class="flex items-center gap-3 text-sm font-medium text-slate-700 dark:text-slate-200">
                                <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                </svg>
                                Voice Chat
                            </div>
                            <span
                                class="text-[10px] uppercase font-black px-2 py-0.5 rounded-full {{ $isSoundOn ? 'bg-green-100 text-green-600' : 'bg-slate-100 text-slate-500' }}">
                                {{ $isSoundOn ? 'ON' : 'OFF' }}
                            </span>
                        </button>

                        <button wire:click="toggleAlertSound"
                            class="w-full px-4 py-3 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                            <div class="flex items-center gap-3 text-sm font-medium text-slate-700 dark:text-slate-200">
                                <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                Alert Sounds
                            </div>
                            <span
                                class="text-[10px] uppercase font-black px-2 py-0.5 rounded-full {{ $isAlertSoundOn ? 'bg-green-100 text-green-600' : 'bg-slate-100 text-slate-500' }}">
                                {{ $isAlertSoundOn ? 'ON' : 'OFF' }}
                            </span>
                        </button>

                        <div class="h-px bg-slate-100 dark:bg-slate-700 my-1 mx-4"></div>

                        <button onclick="window.open(window.location.href, '_blank')" @click="menuOpen = false"
                            class="w-full px-4 py-3 flex items-center gap-3 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                            <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Pop out widget
                        </button>

                        @if($chatbot->whatsapp_number)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $chatbot->whatsapp_number) }}"
                                target="_blank" @click="menuOpen = false"
                                class="w-full px-4 py-3 flex items-center gap-3 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                <svg class="w-4 h-4 text-green-500 opacity-80" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                </svg>
                                Chat on WhatsApp
                            </a>
                        @endif

                        <button @click="menuOpen = false; window.open('{{ config('app.url') }}', '_blank')"
                            class="w-full px-4 py-3 flex items-center gap-3 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                            <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Add Chat to your website
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Close Header Container -->

    <!-- Messages Area -->
    <div id="messages-container" class="flex-1 overflow-y-auto bg-white p-4 space-y-4"
        style="background-color: white !important;">
        @forelse($messages as $msg)
            <div
                class="flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }} animate-in fade-in duration-300">
                <div class="max-w-[80%]">
                    @if($msg['role'] === 'assistant')
                        <!-- Bot Message -->
                        <div class="flex items-start gap-2">
                            <div class="w-7 sm:w-8 h-7 sm:h-8 rounded-full flex items-center justify-center flex-shrink-0"
                                style="background-color: var(--bot-primary)">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <div class="bg-white rounded-2xl rounded-tl-sm px-3 sm:px-4 py-2 sm:py-3 shadow-sm">
                                    <p class="text-xs sm:text-sm text-gray-800 leading-relaxed">
                                        {{ $msg['content'] }}
                                    </p>
                                    @if(isset($msg['image_url']) && $msg['image_url'])
                                        <div
                                            class="mt-2.5 rounded-xl overflow-hidden border border-gray-100 dark:border-slate-700 shadow-sm group relative">
                                            <img src="{{ $msg['image_url'] }}" alt="Assistant image"
                                                class="max-w-full h-auto max-h-72 object-cover cursor-zoom-in transition-transform duration-500 group-hover:scale-105"
                                                onclick="window.open('{{ $msg['image_url'] }}', '_blank')">
                                            <div
                                                class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors pointer-events-none">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 mt-1 ml-1">
                                    <p class="text-[10px] text-gray-400 font-medium uppercase tracking-tight">
                                        {{ $msg['time'] }}
                                    </p>
                                    <button
                                        @click="Livewire.dispatch('speak', { text: '{{ addslashes($msg['content']) }}', lang: '{{ $languages[$selectedLanguage]['voice'] }}' })"
                                        class="p-1 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-gray-400 hover:text-[var(--bot-primary)] transition-all"
                                        title="Play message">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col items-end">
                            <div class="rounded-2xl rounded-tr-sm px-3 sm:px-4 py-2 sm:py-3"
                                style="background-color: var(--bot-primary-light)">
                                <p class="text-xs sm:text-sm text-gray-800 dark:text-gray-200 leading-relaxed">
                                    {{ $msg['content'] }}
                                </p>
                                @if(isset($msg['image_url']) && $msg['image_url'])
                                    <div
                                        class="mt-2.5 rounded-xl overflow-hidden border border-black/5 dark:border-white/5 shadow-sm group relative">
                                        <img src="{{ $msg['image_url'] }}" alt="User uploaded image"
                                            class="max-w-full h-auto max-h-72 object-cover cursor-zoom-in transition-transform duration-500 group-hover:scale-105"
                                            onclick="window.open('{{ $msg['image_url'] }}', '_blank')">
                                        <div
                                            class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors pointer-events-none">
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 mt-1 mr-1">{{ $msg['time'] }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <!-- Welcome Message (Centered) -->
            <div class="flex-1 flex flex-col items-center justify-end px-6 pt-4 pb-2 space-y-4">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg transform transition-transform hover:scale-105"
                    style="background: linear-gradient(to bottom right, var(--bot-primary), var(--bot-primary-dark))">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                </div>
                <div class="text-center space-y-2">
                    <h4 class="font-bold text-lg text-gray-800">Hello! How can we help you today?</h4>
                    Ask any question or start a conversation with our AI.
                </div>


            </div>
        @endforelse

        <!-- Typing Indicator (Enhanced) -->
        @if($loading)
            <div class="flex justify-start animate-in fade-in slide-in-from-bottom-2 duration-300">
                <div class="flex items-end gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mb-1"
                        style="background-color: var(--bot-primary)">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                            </path>
                        </svg>
                    </div>

                    <!-- Typing Animation -->
                    <div
                        class="bg-gray-100 dark:bg-slate-800 rounded-2xl rounded-bl-none px-4 py-3 shadow-sm border border-gray-100 dark:border-slate-700">
                        <div class="flex items-center gap-1.5 h-4">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"
                                style="animation-delay: 0ms; animation-duration: 1s;">
                            </div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"
                                style="animation-delay: 150ms; animation-duration: 1s;">
                            </div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"
                                style="animation-delay: 300ms; animation-duration: 1s;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif


    </div>

    <!-- Premium Integrated Input Area -->
    <div class="bg-white border-t border-gray-100 dark:border-slate-800 flex-none px-4 pt-2 pb-1">

        <form wire:submit.prevent="sendMessage">
            @if($uploadedFile)
                <div
                    class="mt-3 flex items-center justify-between p-2.5 rounded-2xl border bg-gray-50 dark:bg-slate-800 border-gray-100 dark:border-slate-700 animate-in slide-in-from-bottom-2 duration-300">
                    <div class="flex items-center gap-3 min-w-0">
                        @if(Str::startsWith($uploadedFile->getMimeType(), 'image/'))
                            <div
                                class="w-12 h-12 rounded-lg overflow-hidden border border-gray-200 dark:border-slate-600 shrink-0 shadow-sm">
                                <img src="{{ $uploadedFile->temporaryUrl() }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div
                                class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-slate-700 flex items-center justify-center shrink-0 border border-gray-200 dark:border-slate-600">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-gray-800 dark:text-white truncate">{{ $uploadedFileName }}
                            </p>
                            <p class="text-[10px] text-gray-400 uppercase font-medium">Ready to send</p>
                        </div>
                    </div>
                    <button type="button" wire:click="removeFile"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-white dark:bg-slate-700 text-gray-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            <div class="flex items-end gap-2 mt-3">
                <!-- Main Input Container -->
                <div
                    class="flex-1 relative bg-gray-50 dark:bg-slate-800/50 rounded-2xl border border-gray-200 dark:border-slate-700 transition-all focus-within:border-purple-500 focus-within:ring-2 focus-within:ring-purple-500/10">
                    <!-- Textarea for multi-line support or Input for single line -->
                    <input type="text" wire:model.live="message" id="chat-input" placeholder="Type a message..."
                        class="w-full px-5 py-3.5 bg-transparent border-none focus:ring-0 text-sm text-gray-800 dark:text-white placeholder-gray-400"
                        autocomplete="off">

                    <div wire:ignore x-data="{
                        isRecording: false,
                        isVoiceNote: false,
                        recognition: null,
                        mediaRecorder: null,
                        audioChunks: [],
                        startTime: null,
                        timer: null,
                        duration: '0:00',
                        longPress: null,
                        supported: !!(window.SpeechRecognition || window.webkitSpeechRecognition),
                        currentVoiceCode: '{{ $languages[$selectedLanguage]['voice'] }}',
                        cancelled: false,
                        
                        init() {
                            try {
                                if (this.supported) {
                                    const Recognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                                    this.recognition = new Recognition();
                                    this.recognition.continuous = false;
                                    this.recognition.lang = this.currentVoiceCode;
                                    this.recognition.interimResults = false;
 
                                    this.recognition.onstart = () => { this.isRecording = true; this.isVoiceNote = false; };
                                    this.recognition.onend = () => { this.isRecording = false; };
                                    this.recognition.onresult = (event) => {
                                        const transcript = event.results[0][0].transcript;
                                        @this.set('message', transcript);
                                        document.getElementById('chat-input').value = transcript;
                                    };
                                    this.recognition.onerror = (e) => {
                                        console.error('STT Error:', e.error);
                                        this.isRecording = false;
                                    };

                                    // Listen for language changes
                                    document.addEventListener('language-changed', (event) => {
                                        const data = event.detail[0];
                                        this.currentVoiceCode = data.voice;
                                        if (this.recognition) {
                                            this.recognition.lang = data.voice;
                                        }
                                    });
                                }
                            } catch (e) {
                                console.error('Voice Init Error:', e);
                                this.supported = false;
                            }
                        },

                        startSTT() {
                            if (!this.recognition) {
                                if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost') {
                                    alert('Voice features usually require an HTTPS connection in this browser.');
                                } else {
                                    alert('Voice-to-text is not available in your current browser.');
                                }
                                return;
                            }
                            if (this.isRecording) {
                                this.recognition.stop();
                            } else {
                                this.recognition.start();
                            }
                        },

                        async startVoiceNote() {
                            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                                alert('Microphone not supported on this browser.');
                                return;
                            }

                            try {
                                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                                this.mediaRecorder = new MediaRecorder(stream);
                                this.audioChunks = [];
                                this.cancelled = false;
                                
                                this.mediaRecorder.ondataavailable = (e) => {
                                    this.audioChunks.push(e.data);
                                };

                                this.mediaRecorder.onstop = async () => {
                                    if (!this.cancelled) {
                                        const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
                                        if (audioBlob.size > 0) {
                                            @this.upload('audioUpload', audioBlob, (uploadedFilename) => {
                                                @this.call('sendVoiceNote');
                                            });
                                        }
                                    }
                                    stream.getTracks().forEach(track => track.stop());
                                    this.isRecording = false;
                                    this.isVoiceNote = false;
                                    clearInterval(this.timer);
                                };

                                this.mediaRecorder.start();
                                this.isRecording = true;
                                this.isVoiceNote = true;
                                this.startTime = Date.now();
                                this.timer = setInterval(() => {
                                    const elapsed = Math.floor((Date.now() - this.startTime) / 1000);
                                    const mins = Math.floor(elapsed / 60);
                                    const secs = elapsed % 60;
                                    this.duration = `${mins}:${secs.toString().padStart(2, '0')}`;
                                }, 1000);

                            } catch (err) {
                                console.error('Error recording:', err);
                                alert('Microphone access denied.');
                            }
                        },

                        stopVoiceNote() {
                            if (this.mediaRecorder && this.isRecording && this.isVoiceNote) {
                                this.mediaRecorder.stop();
                            }
                        },
                        
                        cancelVoiceNote() {
                            this.cancelled = true;
                            this.stopVoiceNote();
                        }
                    }" class="absolute right-2 bottom-2 flex items-center gap-1">
                        <!-- Recording Overlay -->
                        <div x-show="isRecording && isVoiceNote" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-full"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-full"
                            class="absolute inset-0 z-20 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-between px-4 border border-[var(--bot-primary)]">

                            <!-- Delete/Cancel Button -->
                            <button type="button" @click="cancelVoiceNote();"
                                class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-slate-700 rounded-full transition-colors"
                                title="Cancel Recording">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>

                            <!-- Waveform Visualizer -->
                            <div class="flex items-center gap-1 h-8 mx-4">
                                <span
                                    class="text-xs font-mono tabular-nums text-[var(--bot-primary)] font-bold min-w-[40px]"
                                    x-text="duration"></span>
                                <div class="flex items-center gap-0.5 h-full">
                                    <template x-for="i in 12">
                                        <div class="recording-wave-bar h-full"
                                            :style="`animation-delay: -${Math.random()}s`"></div>
                                    </template>
                                </div>
                            </div>

                            <!-- Send Button -->
                            <button type="button" @click="stopVoiceNote();"
                                class="p-2 text-white rounded-full transition-transform active:scale-95 shadow-md"
                                style="background-color: var(--bot-primary)" title="Send Voice Note">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path
                                        d="M3.478 2.404a.75.75 0 00-.926.941l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.404z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Voice Mic Button (Dual Mode) - Only show if plan allows -->
                        @if($chatbot->user->plan->allows_voice_messages)
                            <div x-show="!isRecording" class="relative flex items-center">
                                <button type="button" @click="startSTT()"
                                    @mousedown="longPress = setTimeout(() => startVoiceNote(), 500)"
                                    @mouseup="clearTimeout(longPress);" @mouseleave="clearTimeout(longPress);"
                                    @touchstart="longPress = setTimeout(() => startVoiceNote(), 500)"
                                    @touchend="clearTimeout(longPress);"
                                    class="p-2.5 rounded-xl hover:text-[var(--bot-primary)] transition-all relative group touch-none text-gray-400">
                                    <span
                                        class="absolute -top-12 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Hold
                                        to Record</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                    </svg>
                                </button>
                            </div>
                        @endif

                        <!-- Image Upload Button - Only show if plan allows -->
                        @if($chatbot->user->plan->allows_image_upload)
                            <label
                                class="p-2 text-gray-400 hover:text-[var(--bot-primary)] cursor-pointer transition-colors">
                                <input type="file" wire:model="fileUpload" accept="image/*" class="hidden">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                            </label>
                        @endif
                    </div>
                </div>

                <!-- Send Button -->
                <button type="submit"
                    class="p-3.5 text-white rounded-2xl shadow-lg transition-all duration-300 transform active:scale-95 shrink-0 disabled:opacity-50 disabled:cursor-not-allowed group relative"
                    style="background-color: var(--bot-primary); shadow-color: var(--bot-primary)"
                    wire:loading.attr="disabled" wire:target="sendMessage, generateResponse, fileUpload">

                    <!-- Icon for Sending Message -->
                    <svg wire:loading.remove wire:target="generateResponse, fileUpload"
                        class="w-5 h-5 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path>
                    </svg>

                    <!-- Loader for AI Thinking -->
                    <svg wire:loading wire:target="generateResponse" class="animate-spin h-5 w-5 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>

                    <!-- Loader for File Uploading -->
                    <svg wire:loading wire:target="fileUpload" class="animate-pulse h-5 w-5 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                </button>
            </div>
            <!-- File Upload Error -->
            @error('fileUpload')
                <p class="text-xs text-red-500 mt-2 ml-1">{{ $message }}</p>
            @enderror

            <!-- Footer -->
            <p class="text-[10px] text-center text-gray-300 pt-1">
                Powered by <a href="https://wisdomweb.co.in/" target="_blank"
                    class="font-semibold hover:opacity-80 transition-colors" style="color: var(--bot-primary)">WisdomWeb</a>
            </p>
        </form>
    </div>

    <script>
        // Auto-scroll function
        function scrollToBottom() {
            const container = document.getElementById('messages-container');
            if (container) {
                setTimeout(() => {
                    container.scrollTo({
                        top: container.scrollHeight,
                        behavior: 'smooth'
                    });
                }, 100);
            }
        }

        // Livewire hooks for auto-scroll
        document.addEventListener('livewire:init', () => {
            // Send config to parent for toggle color
            window.parent.postMessage({
                type: 'chatbotConfig',
                themeColor: '{{ $chatbot->theme_color ?? "#9333ea" }}'
            }, '*');

            // Listen for emoji selection
            document.addEventListener('emoji-click', event => {
                const emoji = event.detail.unicode;
                @this.call('addEmoji', emoji);
            });

            // Text-to-Speech (TTS)
            Livewire.on('speak', (data) => {
                const event = Array.isArray(data) ? data[0] : data;
                const text = event.text;
                const langCode = event.lang || '{{ $languages[$selectedLanguage]['voice'] }}';
                if (!window.speechSynthesis) return;

                window.speechSynthesis.cancel();

                const speakText = () => {
                    const utterance = new SpeechSynthesisUtterance(text);
                    const langBase = langCode.split('-')[0];
                    const voices = window.speechSynthesis.getV
                    oices();

                    let preferredVoice = voices.find(v => v.lang === langCode)
                        || voices.find(v => v.lang.startsWith(langBase));

                    if (preferredVoice) {
                        utterance.voice = preferredVoice;
                    }

                    utterance.lang = langCode;
                    utterance.rate = 1.0;
                    utterance.pitch = 1.0;

                    window.speechSynthesis.speak(utterance);
                };

                if (window.speechSynthesis.getVoices().length === 0) {
                    window.speechSynthesis.onvoiceschanged = () => {
                        window.speechSynthesis.onvoiceschanged = null;
                        speakText();
                    };
                } else {
                    speakText();
                }
            });

            // Stop speech if sound is disabled
            Livewire.on('sound-toggled', (event) => {
                if (event.status === 'disabled') {
                    window.speechSynthesis.cancel();
                }
            });

            // Scroll after any Livewire update
            Livewire.hook('message.processed', (message, component) => {
                scrollToBottom();
            });

            // Scroll after commit (when DOM is updated)
            Livewire.hook('commit', ({ component, commit, respond }) => {
                scrollToBottom();
            });
        });

        // Initial scroll on page load
        window.addEventListener('load', () => {
            scrollToBottom();
        });

        // Scroll when new content is added (MutationObserver)
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('messages-container');
            if (container) {
                const observer = new MutationObserver(() => {
                    scrollToBottom();
                });

                observer.observe(container, {
                    childList: true,
                    subtree: true
                });
            }
        });
    </script>

    <audio id="alert-sound" src="https://assets.mixkit.co/active_storage/sfx/2354/2354-preview.mp3"
        preload="auto"></audio>
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('play-ding', () => {
                const audio = document.getElementById('alert-sound');
                if (audio) {
                    audio.currentTime = 0;
                    audio.play().catch(e => console.log('Audio play blocked:', e));
                }
            });

            // Session Keep-Alive Polling
            // Pings the server every 10 minutes to keep the session active
            setInterval(() => {
                fetch('{{ route('session.keep-alive') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                }).catch(err => console.log('Keep-alive ping failed'));
            }, 10 * 60 * 1000);

            // Improved 419 Page Expired handling for Livewire 3
            // This hook intercepts requests and allows us to handle failures before the default alert triggers
            Livewire.hook('request', ({ fail }) => {
                fail(({ status, response }) => {
                    if (status === 419) {
                        // Prevent the default Livewire "Page Expired" alert
                        // Returning false from this callback suppresses the default failure behavior
                        
                        // Show a nice fallback in the chat messages
                        const container = document.getElementById('messages-container');
                        if (container && !document.getElementById('session-expired-notification')) {
                            const errorDiv = document.createElement('div');
                            errorDiv.id = 'session-expired-notification';
                            errorDiv.className = 'flex justify-center my-4 animate-in fade-in duration-500';
                            errorDiv.innerHTML = `
                                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-2xl p-4 text-center max-w-xs shadow-lg">
                                    <p class="text-sm text-amber-800 dark:text-amber-300 font-bold mb-3">Session Expired</p>
                                    <p class="text-xs text-amber-600 dark:text-amber-400 mb-4">Your connection timed out. Please refresh the chat to continue.</p>
                                    <button onclick="window.location.reload()" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-black transition-all shadow-md active:scale-95">
                                        Refresh Chat
                                    </button>
                                </div>
                            `;
                            container.appendChild(errorDiv);
                            scrollToBottom();
                        }

                        return false; 
                    }
                });
            });
        });
    </script>

    <!-- Lead Capture Modal -->
    @if($showLeadCaptureModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div
                class="relative w-full max-w-sm bg-white dark:bg-slate-800 rounded-3xl shadow-2xl overflow-hidden transform transition-all ring-1 ring-white/10">
                <div class="p-8">
                    <div class="text-center mb-6">
                        <div
                            class="w-12 h-12 bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">Let's get started!
                        </h3>
                        <p class="text-sm text-slate-500 mt-2">Please enter your details to start the chat.</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5 ml-1">Your
                                Name</label>
                            <input type="text" wire:model="contactName" placeholder="John Doe"
                                class="w-full px-5 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-[var(--bot-primary)] focus:border-transparent outline-none transition-all dark:text-white font-bold placeholder:font-normal"
                                autocomplete="name">
                            @error('contactName')
                                <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5 ml-1">Phone
                                Number</label>
                            <input type="tel" wire:model="contactPhone" placeholder="+1 234 567 8900"
                                class="w-full px-5 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-[var(--bot-primary)] focus:border-transparent outline-none transition-all dark:text-white font-bold placeholder:font-normal"
                                autocomplete="tel">
                            @error('contactPhone')
                                <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="pt-4">
                            <button wire:click="submitLeadCapture"
                                class="w-full px-6 py-4 text-sm font-black text-white rounded-2xl shadow-xl hover:shadow-2xl transition-all transform active:scale-95 flex items-center justify-center gap-2"
                                style="background-color: var(--bot-primary)">
                                Start Chatting
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 5l7 7-7 7M5 12h14" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Email Modal -->
    @if($showEmailModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity"
                wire:click="$set('showEmailModal', false)"></div>
            <div
                class="relative w-full max-w-sm bg-white dark:bg-slate-800 rounded-2xl shadow-xl overflow-hidden transform transition-all">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white">Email Transcript</h3>
                        <button wire:click="$set('showEmailModal', false)" class="text-slate-400 hover:text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            Enter your email address to receive a copy of this conversation.
                        </p>

                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Email
                                Address</label>
                            <input type="email" wire:model="transcriptEmail" placeholder="you@example.com"
                                class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-[var(--bot-primary)] focus:border-transparent outline-none transition-all dark:text-white"
                                autocomplete="email">
                            @error('transcriptEmail')
                                <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex gap-2 justify-end pt-2">
                            <button wire:click="$set('showEmailModal', false)"
                                class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors">
                                Cancel
                            </button>
                            <button wire:click="sendTranscript" wire:loading.attr="disabled"
                                class="px-4 py-2 text-sm font-bold text-white rounded-xl shadow-lg hover:shadow-xl transition-all transform active:scale-95 disabled:opacity-75 disabled:cursor-wait flex items-center gap-2"
                                style="background-color: var(--bot-primary)">
                                <span wire:loading.remove wire:target="sendTranscript">Send Email</span>
                                <span wire:loading wire:target="sendTranscript" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Sending...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>