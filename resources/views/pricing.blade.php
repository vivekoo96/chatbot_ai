<x-frontend-layout>
    <x-slot name="title">Pricing Plans</x-slot>

    {{-- Hero Section --}}
    <div
        class="relative bg-gradient-to-br from-slate-50 via-indigo-50/30 to-purple-50/20 dark:from-slate-900 dark:via-indigo-950/30 dark:to-purple-950/20 overflow-hidden">
        {{-- Animated Background --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-indigo-400/20 to-purple-400/20 rounded-full blur-3xl animate-pulse">
            </div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-br from-purple-400/20 to-pink-400/20 rounded-full blur-3xl animate-pulse"
                style="animation-delay: 1s;"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
            {{-- Header --}}
            <div class="text-center max-w-4xl mx-auto mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-full mb-6">
                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400 mr-2" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">PRICING</span>
                </div>

                <h1
                    class="text-5xl md:text-6xl lg:text-7xl font-black text-slate-900 dark:text-white mb-6 leading-tight">
                    Choose Your
                    <span
                        class="block bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Perfect
                        Plan</span>
                </h1>

                <p class="text-xl md:text-2xl text-slate-600 dark:text-slate-300 mb-10 leading-relaxed">
                    Simple, transparent pricing that grows with you.<br class="hidden sm:block">
                    No hidden fees. Cancel anytime.
                </p>

                {{-- Currency Switcher --}}
                <div class="flex justify-center mb-6">
                    <div
                        class="inline-flex items-center p-1.5 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700">
                        <a href="{{ route('pricing', ['currency' => 'INR']) }}"
                            class="group px-8 py-3 rounded-xl text-sm font-bold transition-all duration-300 {{ $currency === 'INR' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/50 scale-105' : 'text-slate-600 dark:text-slate-400 hover:text-indigo-600 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                            INR (₹)
                        </a>
                        <a href="{{ route('pricing', ['currency' => 'USD']) }}"
                            class="group px-8 py-3 rounded-xl text-sm font-bold transition-all duration-300 {{ $currency === 'USD' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/50 scale-105' : 'text-slate-600 dark:text-slate-400 hover:text-indigo-600 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                            USD ($)
                        </a>
                    </div>
                </div>

                @if($currency === 'INR')
                    <div class="inline-flex items-center px-4 py-2 bg-green-100 dark:bg-green-900/30 rounded-full">
                        <svg class="w-4 h-4 text-green-600 dark:text-green-400 mr-2" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-bold text-green-700 dark:text-green-300">Special India Pricing
                            Active</span>
                    </div>
                @endif
            </div>

            {{-- Pricing Cards Grid --}}
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
                @foreach ($plans as $plan)
                            <div class="group relative flex flex-col">
                                {{-- Popular Badge --}}
                                @if($plan->slug === 'pro')
                                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-20">
                                        <div
                                            class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-xs font-black px-5 py-2 rounded-full shadow-lg shadow-indigo-500/50 uppercase tracking-wider">
                                            Most Popular
                                        </div>
                                    </div>
                                @endif

                                {{-- Card --}}
                                <div
                                    class="relative h-full flex flex-col bg-white dark:bg-slate-800 rounded-3xl border-2 transition-all duration-500 overflow-hidden
                                                                            {{ $plan->slug === 'pro'
                    ? 'border-indigo-500 shadow-2xl shadow-indigo-500/20 lg:scale-105'
                    : 'border-slate-200 dark:border-slate-700 hover:border-indigo-300 dark:hover:border-indigo-600 hover:shadow-xl hover:-translate-y-1' }}">

                                    {{-- Gradient Overlay for Pro --}}
                                    @if($plan->slug === 'pro')
                                        <div
                                            class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 via-purple-500/5 to-pink-500/5 pointer-events-none">
                                        </div>
                                    @endif

                                    <div class="relative p-6 flex-1 flex flex-col">
                                        {{-- Plan Icon --}}
                                        <div
                                            class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $plan->slug === 'free' ? 'from-gray-400 to-gray-600' : ($plan->slug === 'starter' ? 'from-blue-400 to-blue-600' : ($plan->slug === 'pro' ? 'from-indigo-500 to-purple-600' : ($plan->slug === 'enterprise' ? 'from-indigo-400 to-indigo-600' : ($plan->slug === 'business' ? 'from-purple-500 to-pink-600' : 'from-orange-500 to-red-600')))) }} flex items-center justify-center mb-4 shadow-lg">
                                            @if($plan->svg_icon)
                                                <div class="w-7 h-7 flex items-center justify-center">
                                                    {!! $plan->svg_icon !!}
                                                </div>
                                            @else
                                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                </svg>
                                            @endif
                                        </div>

                                        {{-- Plan Name --}}
                                        <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2">{{ $plan->name }}</h3>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mb-4 line-clamp-2">
                                            {{ $plan->description }}
                                        </p>

                                        {{-- Price --}}
                                        <div class="mb-4">
                                            <div class="flex items-baseline gap-1">
                                                <span
                                                    class="text-4xl font-black bg-gradient-to-r {{ $plan->slug === 'pro' ? 'from-indigo-600 to-purple-600' : 'from-slate-900 to-slate-700 dark:from-white dark:to-slate-300' }} bg-clip-text text-transparent">
                                                    {{ $plan->getFormattedPrice($currency) }}
                                                </span>
                                                <span class="text-slate-500 dark:text-slate-400 text-sm font-medium">/mo</span>
                                            </div>
                                        </div>

                                        {{-- Dynamic Features --}}
                                        <div class="space-y-3 mb-6 flex-1">
                                            <div class="flex items-center gap-2 text-sm">
                                                <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span class="text-slate-700 dark:text-slate-300 font-medium">
                                                    {{ $plan->max_chatbots == -1 ? 'Unlimited' : $plan->max_chatbots }}
                                                    Chatbot{{ $plan->max_chatbots != 1 ? 's' : '' }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-2 text-sm">
                                                <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span class="text-slate-700 dark:text-slate-300 font-medium">
                                                    {{ $plan->max_messages_per_month == -1 ? 'Unlimited' : number_format($plan->max_messages_per_month) }}
                                                    Messages
                                                </span>
                                            </div>

                                            @if($plan->allows_image_upload)
                                                <div class="flex items-center gap-2 text-sm">
                                                    <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-slate-700 dark:text-slate-300 font-medium">Image Analysis
                                                        Support</span>
                                                </div>
                                            @endif

                                            @if($plan->allows_voice_messages)
                                                <div class="flex items-center gap-2 text-sm">
                                                    <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-slate-700 dark:text-slate-300 font-medium">Voice
                                                        Interaction</span>
                                                </div>
                                            @endif

                                            @if(is_array($plan->features))
                                                @foreach(array_slice($plan->features, 0, 4) as $feature)
                                                    <div class="flex items-center gap-2 text-sm">
                                                        <svg class="w-4 h-4 text-indigo-500/50 flex-shrink-0" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        <span class="text-slate-600 dark:text-slate-400 font-medium">{{ $feature }}</span>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>

                                        {{-- CTA Buttons --}}
                                        <div class="space-y-2">
                                            @auth
                                                @if(auth()->user()->plan_id === $plan->id)
                                                    <button disabled
                                                        class="w-full py-3 px-4 rounded-xl font-bold text-sm bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500 cursor-not-allowed">
                                                        Current Plan
                                                    </button>
                                                @else
                                                                    <a href="{{ route('checkout', $plan) }}"
                                                                        class="block w-full py-3 px-4 rounded-xl font-bold text-sm text-center transition-all duration-300 shadow-lg
                                                                                                                                                                                                   {{ $plan->slug === 'pro'
                                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 shadow-indigo-500/50 hover:shadow-xl hover:scale-105'
                                                    : 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100 hover:scale-105' }}">
                                                                        {{ $plan->price_inr > 0 ? 'Get Started' : 'Start Free' }}
                                                                    </a>
                                                @endif
                                            @else
                                                                    <a href="{{ route('register') }}"
                                                                        class="block w-full py-3 px-4 rounded-xl font-bold text-sm text-center transition-all duration-300 shadow-lg
                                                                                                                                                                                               {{ $plan->slug === 'pro'
                                                ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 shadow-indigo-500/50 hover:shadow-xl hover:scale-105'
                                                : 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100 hover:scale-105' }}">
                                                                        {{ $plan->price_inr > 0 ? 'Get Started' : 'Start Free' }}
                                                                    </a>
                                            @endauth

                                            <button onclick="openPlanModal({{ $plan->id }})"
                                                class="w-full py-2 px-4 rounded-xl font-semibold text-sm text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all border border-indigo-200 dark:border-indigo-800">
                                                See All Features
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Plan Details Modal --}}
    <div id="planModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4"
        onclick="if(event.target === this) closePlanModal()">
        <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl"
            onclick="event.stopPropagation()">
            <div
                class="sticky top-0 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 p-6 flex justify-between items-center">
                <h3 id="modalPlanName" class="text-2xl font-black text-slate-900 dark:text-white"></h3>
                <button onclick="closePlanModal()"
                    class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors">
                    <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="modalContent" class="p-6">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
    </div>

    <script>
        const plans = @json($plans);
        const currency = '{{ $currency }}';

        function openPlanModal(planId) {
            const plan = plans.find(p => p.id === planId);
            if (!plan) return;

            document.getElementById('modalPlanName').textContent = plan.name + ' Plan';

            const features = plan.features ? (typeof plan.features === 'string' ? JSON.parse(plan.features) : plan.features) : [];

            const content = `
                <div class="space-y-6">
                    <div>
                        <p class="text-slate-600 dark:text-slate-400 mb-4">${plan.description}</p>
                        <div class="text-5xl font-black bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
                            ${currency === 'INR' ? '₹' + plan.price_inr : '$' + plan.price_usd}
                            <span class="text-xl text-slate-500">/month</span>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Core Features</h4>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3 p-3 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <div class="font-semibold text-slate-900 dark:text-white">Chatbots</div>
                                    <div class="text-sm text-slate-600 dark:text-slate-400">${plan.max_chatbots == -1 ? 'Unlimited chatbots' : plan.max_chatbots + ' chatbot' + (plan.max_chatbots != 1 ? 's' : '')}</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-3 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <div class="font-semibold text-slate-900 dark:text-white">Messages</div>
                                    <div class="text-sm text-slate-600 dark:text-slate-400">${plan.max_messages_per_month == -1 ? 'Unlimited messages' : plan.max_messages_per_month.toLocaleString() + ' messages per month'}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    ${features && features.length > 0 ? `
                    <div>
                        <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Additional Features</h4>
                        <div class="space-y-2">
                            ${features.map(feature => `
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-slate-700 dark:text-slate-300">${feature}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    ` : ''}
                </div>
            `;

            document.getElementById('modalContent').innerHTML = content;
            document.getElementById('planModal').classList.remove('hidden');
            document.getElementById('planModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closePlanModal() {
            document.getElementById('planModal').classList.add('hidden');
            document.getElementById('planModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closePlanModal();
        });
    </script>

    {{-- ADD-ONS SECTION (keeping existing code but removing emojis) --}}
    @if(isset($addons) && $addons->count() > 0)
        <div class="bg-white dark:bg-slate-900 py-20 lg:py-28">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <div class="inline-flex items-center px-4 py-2 bg-purple-100 dark:bg-purple-900/30 rounded-full mb-6">
                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400 mr-2" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                        </svg>
                        <span class="text-sm font-bold text-purple-600 dark:text-purple-400">ADD-ONS</span>
                    </div>

                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white mb-6">
                        Need More <span
                            class="bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Capacity?</span>
                    </h2>
                    <p class="text-xl text-slate-600 dark:text-slate-400 mb-4">
                        Purchase add-ons anytime to boost your limits instantly
                    </p>
                    <p class="text-sm text-slate-500 dark:text-slate-500 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd" />
                        </svg>
                        Add-ons expire at the end of your billing cycle
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
                    @foreach($addons->flatten() as $addon)
                        <div
                            class="group relative bg-white dark:bg-slate-800 rounded-2xl border-2 border-slate-200 dark:border-slate-700 p-6 hover:border-indigo-400 dark:hover:border-indigo-500 hover:shadow-2xl hover:shadow-indigo-500/20 transition-all duration-300 hover:-translate-y-1">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="text-xl font-bold text-slate-900 dark:text-white mb-1">{{ $addon->name }}</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $addon->description }}</p>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="flex items-baseline gap-2 mb-1">
                                    <span
                                        class="text-3xl font-extrabold text-indigo-600">{{ $addon->getFormattedPrice($currency) }}</span>
                                    <span class="text-slate-500 text-sm">one-time</span>
                                </div>
                                <div class="text-xs text-slate-500 font-medium">{{ $addon->getPerUnitCost($currency) }}</div>
                            </div>
                            <a href="{{ Auth::check() ? route('addons.checkout', $addon) : route('register') }}"
                                class="block w-full py-3 px-6 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-bold text-center hover:from-indigo-700 hover:to-purple-700 shadow-lg shadow-indigo-500/30 hover:shadow-xl hover:shadow-indigo-500/40 transition-all">
                                Buy Now
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- FAQ & CTA sections remain the same --}}
    <div class="bg-slate-50 dark:bg-slate-900 py-20 lg:py-28">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white mb-4">
                    Frequently Asked <span
                        class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Questions</span>
                </h2>
                <p class="text-xl text-slate-600 dark:text-slate-400">Everything you need to know about our pricing</p>
            </div>

            <div class="space-y-4">
                <details
                    class="group bg-white dark:bg-slate-800 rounded-2xl border-2 border-slate-200 dark:border-slate-700 overflow-hidden">
                    <summary
                        class="flex items-center justify-between p-6 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <span class="font-bold text-slate-900 dark:text-white">Can I change plans anytime?</span>
                        <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="px-6 pb-6 text-slate-600 dark:text-slate-400">
                        Yes! You can upgrade or downgrade your plan at any time. Changes take effect immediately.
                    </div>
                </details>

                <details
                    class="group bg-white dark:bg-slate-800 rounded-2xl border-2 border-slate-200 dark:border-slate-700 overflow-hidden">
                    <summary
                        class="flex items-center justify-between p-6 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <span class="font-bold text-slate-900 dark:text-white">What happens when I reach my message
                            limit?</span>
                        <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="px-6 pb-6 text-slate-600 dark:text-slate-400">
                        You can either upgrade your plan or purchase add-ons to continue using the service without
                        interruption.
                    </div>
                </details>

                <details
                    class="group bg-white dark:bg-slate-800 rounded-2xl border-2 border-slate-200 dark:border-slate-700 overflow-hidden">
                    <summary
                        class="flex items-center justify-between p-6 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <span class="font-bold text-slate-900 dark:text-white">Do add-ons roll over to the next
                            month?</span>
                        <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="px-6 pb-6 text-slate-600 dark:text-slate-400">
                        No, add-ons expire at the end of your billing cycle. Any unused capacity from add-ons will not
                        carry over.
                    </div>
                </details>

                <details
                    class="group bg-white dark:bg-slate-800 rounded-2xl border-2 border-slate-200 dark:border-slate-700 overflow-hidden">
                    <summary
                        class="flex items-center justify-between p-6 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <span class="font-bold text-slate-900 dark:text-white">Is there a refund policy?</span>
                        <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="px-6 pb-6 text-slate-600 dark:text-slate-400">
                        We offer a 7-day money-back guarantee. If you're not satisfied, contact our support team for a
                        full refund.
                    </div>
                </details>
            </div>
        </div>
    </div>

    {{-- CTA Section --}}
    <div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 py-20 lg:py-28 overflow-hidden">
        <div
            class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4xIj48cGF0aCBkPSJNMzYgMzRjMC0yLjIxLTEuNzktNC00LTRzLTQgMS43OS00IDQgMS43OSA0IDQgNCA0LTEuNzkgNC00em0wLTEwYzAtMi4yMS0xLjc5LTQtNC00cy00IDEuNzktNCA0IDEuNzkgNCA0IDQgNC0xLjc5IDQtNHptMC0xMGMwLTIuMjEtMS43OS00LTQtNHMtNCAxLjc5LTQgNCAxLjc5IDQgNCA0IDQtMS43OSA0LTR6Ii8+PC9nPjwvZz48L3N2Zz4=')] opacity-10">
        </div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6">
                Ready to Get Started?
            </h2>
            <p class="text-xl md:text-2xl text-indigo-100 mb-10 max-w-2xl mx-auto">
                Join thousands of businesses already using our platform to engage with their customers
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}"
                    class="inline-flex items-center justify-center px-8 py-4 bg-white text-indigo-600 rounded-xl font-bold text-lg hover:bg-slate-50 transition-all shadow-2xl hover:scale-105">
                    Start Free Trial
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
                <a href="{{ route('contact') }}"
                    class="inline-flex items-center justify-center px-8 py-4 bg-transparent text-white border-2 border-white rounded-xl font-bold text-lg hover:bg-white/10 transition-all">
                    Contact Sales
                </a>
            </div>
        </div>
    </div>
</x-frontend-layout>