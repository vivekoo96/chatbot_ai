<x-frontend-layout>
    <x-slot name="title">Documentation - Hemnix Assist Chatbot</x-slot>

    <div class="bg-slate-50 dark:bg-slate-900/50 min-h-screen pt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col lg:flex-row gap-12">
                <!-- Sidebar Navigation -->
                <aside class="lg:w-64 flex-shrink-0">
                    <div class="sticky top-32 space-y-8">
                        <div>
                            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Getting
                                Started</h3>
                            <nav class="space-y-1">
                                <a href="#overview"
                                    class="block py-2 px-3 text-sm font-medium text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">Overview</a>
                                <a href="#core-concept"
                                    class="block py-2 px-3 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">Core
                                    Concept</a>
                                <a href="#supported-platforms"
                                    class="block py-2 px-3 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">Supported
                                    Platforms</a>
                            </nav>
                        </div>
                        <div>
                            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Integrations
                            </h3>
                            <nav class="space-y-1">
                                <a href="#website-integration"
                                    class="block py-2 px-3 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">HTML
                                    / Static Site</a>
                                <a href="#wordpress-integration"
                                    class="block py-2 px-3 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">WordPress</a>
                                <a href="#shopify-integration"
                                    class="block py-2 px-3 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">Shopify</a>
                                <a href="#gtm-integration"
                                    class="block py-2 px-3 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">Google
                                    Tag Manager</a>
                                <a href="#mobile-integration"
                                    class="block py-2 px-3 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">Mobile
                                    App (WebView)</a>
                            </nav>
                        </div>
                        <div>
                            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Advanced</h3>
                            <nav class="space-y-1">
                                <a href="#configuration"
                                    class="block py-2 px-3 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">Chatbot
                                    Configuration</a>
                                <a href="#whatsapp-alerts"
                                    class="block py-2 px-3 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">WhatsApp
                                    Alerts</a>
                                <a href="#security"
                                    class="block py-2 px-3 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">Security
                                    & Domains</a>
                            </nav>
                        </div>
                    </div>
                </aside>

                <!-- Content Area -->
                <main class="flex-1 lg:max-w-4xl">
                    <div class="prose prose-slate dark:prose-invert max-w-none">
                        <section id="overview" class="mb-16 scroll-mt-32">
                            <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white mb-6">📘 Hemnix Assist
                                Chatbot</h1>
                            <p class="text-xl text-slate-600 dark:text-slate-400 leading-relaxed mb-8">
                                Integration & Usage Documentation
                            </p>

                            <div
                                class="bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-200 dark:border-slate-700 shadow-sm mb-12">
                                <h2 class="text-2xl font-bold mb-6">1. Overview</h2>
                                <p class="mb-6">Hemnix Assist is an AI-powered chatbot that can be integrated into
                                    websites, web applications, and mobile apps to provide:</p>
                                <ul class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <li class="flex items-center gap-3"><span
                                            class="w-2 h-2 bg-indigo-500 rounded-full"></span> Instant AI responses</li>
                                    <li class="flex items-center gap-3"><span
                                            class="w-2 h-2 bg-indigo-500 rounded-full"></span> Lead capture</li>
                                    <li class="flex items-center gap-3"><span
                                            class="w-2 h-2 bg-indigo-500 rounded-full"></span> Image & voice
                                        interactions</li>
                                    <li class="flex items-center gap-3"><span
                                            class="w-2 h-2 bg-indigo-500 rounded-full"></span> Human escalation</li>
                                    <li class="flex items-center gap-3"><span
                                            class="w-2 h-2 bg-indigo-500 rounded-full"></span> Analytics & conversation
                                        tracking</li>
                                </ul>
                                <p class="mt-8 text-slate-500 italic">The chatbot is deployed using a lightweight embed
                                    mechanism and connects securely to Hemnix servers using a Public Chatbot Key.</p>
                            </div>
                        </section>

                        <section id="core-concept" class="mb-16 scroll-mt-32">
                            <h2 class="text-3xl font-bold mb-6">2. Core Integration Concept</h2>
                            <p class="mb-6">All integrations ultimately load the same chatbot script. Simply add this
                                script tag to your site:</p>
                            <div class="relative group">
                                <div
                                    class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl blur opacity-20 transition duration-1000 group-hover:duration-200">
                                </div>
                                <pre
                                    class="relative bg-slate-900 text-slate-300 p-6 rounded-xl overflow-x-auto text-sm border border-slate-800"><code>&lt;script
  src="https://chatbot.hemnix.com/widget.js"
  data-key="YOUR_PUBLIC_CHATBOT_KEY"
  async&gt;
&lt;/script&gt;</code></pre>
                            </div>
                            <div class="mt-8 space-y-4 text-slate-600 dark:text-slate-400">
                                <p class="flex gap-4"><strong class="text-slate-900 dark:text-white flex-shrink-0">Key
                                        ID:</strong> The Public Chatbot Key identifies your specific chatbot
                                    configuration.</p>
                                <p class="flex gap-4"><strong
                                        class="text-slate-900 dark:text-white flex-shrink-0">Rendering:</strong> The
                                    script automatically renders the UI and handles all communication.</p>
                                <p class="flex gap-4"><strong
                                        class="text-slate-900 dark:text-white flex-shrink-0">Security:</strong> No
                                    backend code is required on the client's side for standard integration.</p>
                            </div>
                        </section>

                        <section id="supported-platforms" class="mb-16 scroll-mt-32">
                            <h2 class="text-3xl font-bold mb-6">3. Supported Platforms</h2>
                            <p class="mb-8">Hemnix Assist works seamlessly across all modern digital platforms:</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
                                @foreach(['Static HTML', 'WordPress', 'Shopify', 'Laravel / PHP', 'React / Next.js', 'Android/iOS', 'GTM'] as $platform)
                                    <div
                                        class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-center text-center font-semibold text-slate-700 dark:text-slate-300 shadow-sm transition-transform hover:scale-105">
                                        {{ $platform }}
                                    </div>
                                @endforeach
                            </div>
                        </section>

                        <section id="website-integration" class="mb-16 scroll-mt-32">
                            <h2 class="text-3xl font-bold mb-6">4. Website Integration (HTML / Frameworks)</h2>
                            <div
                                class="bg-white dark:bg-slate-800 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm">
                                <div class="p-8">
                                    <ol class="space-y-4 mb-8">
                                        <li>1. Open your website's main layout file.</li>
                                        <li>2. Paste the script before the closing <code
                                                class="bg-slate-100 dark:bg-slate-700 px-1 rounded">&lt;/body&gt;</code>
                                            tag.</li>
                                        <li>3. Replace <code
                                                class="bg-slate-100 dark:bg-slate-700 px-1 rounded">YOUR_PUBLIC_CHATBOT_KEY</code>
                                            with your key.</li>
                                        <li>4. Save and deploy.</li>
                                    </ol>
                                    <div
                                        class="bg-indigo-50 dark:bg-indigo-900/10 border-l-4 border-indigo-500 p-4 mb-8">
                                        <p class="text-indigo-700 dark:text-indigo-300 text-sm">
                                            <strong>Note:</strong> Works with any frontend framework and loads
                                            asynchronously without blocking page rendering.
                                        </p>
                                    </div>
                                    <pre
                                        class="bg-slate-900 text-slate-300 p-6 rounded-xl overflow-x-auto text-sm border border-slate-800"><code>&lt;script
  src="https://chatbot.hemnix.com/widget.js"
  data-key="PUBLIC_KEY"
  async&gt;
&lt;/script&gt;</code></pre>
                                </div>
                            </div>
                        </section>

                        <section id="wordpress-integration" class="mb-16 scroll-mt-32">
                            <h2 class="text-3xl font-bold mb-6">5. WordPress Integration</h2>
                            <div class="space-y-8">
                                <div
                                    class="bg-white dark:bg-slate-800 p-8 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                                        <span
                                            class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 rounded-lg flex items-center justify-center text-sm font-bold">A</span>
                                        Plugin (Recommended)
                                    </h3>
                                    <ul class="space-y-2 text-slate-600 dark:text-slate-400 mb-6">
                                        <li>• Download the plugin file below.</li>
                                        <li>• Upload <strong>hemnix-assist.php</strong> to your
                                            <code>wp-content/plugins/</code> directory.
                                        </li>
                                        <li>• Go to Settings → Hemnix Assist.</li>
                                        <li>• Enable chatbot and paste your Public Key.</li>
                                        <li>• Save. The script is injected automatically.</li>
                                    </ul>
                                    <a href="{{ asset('plugins/hemnix-assist.zip') }}" download
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-indigo-600/20 active:scale-95">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download Plugin (.zip)
                                    </a>
                                    <p class="mt-3 text-xs text-slate-400">Version 1.0.0 | Recommended for WordPress</p>
                                </div>
                                <div
                                    class="bg-white dark:bg-slate-800 p-8 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                                        <span
                                            class="w-8 h-8 bg-slate-100 dark:bg-slate-700 text-slate-600 rounded-lg flex items-center justify-center text-sm font-bold">B</span>
                                        Manual Theme Integration
                                    </h3>
                                    <p class="text-slate-600 dark:text-slate-400">Go to Appearance → Theme Editor, open
                                        <code class="bg-slate-100 dark:bg-slate-700 px-1 rounded">footer.php</code>, and
                                        paste the script before <code
                                            class="bg-slate-100 dark:bg-slate-700 px-1 rounded">&lt;/body&gt;</code>.
                                    </p>
                                </div>
                            </div>
                        </section>

                        <section id="shopify-integration" class="mb-16 scroll-mt-32">
                            <h2 class="text-3xl font-bold mb-6">6. Shopify Integration</h2>
                            <div
                                class="bg-white dark:bg-slate-800 p-8 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                                <ol class="space-y-3 mb-6">
                                    <li>1. Shopify Admin → Online Store → Themes</li>
                                    <li>2. Click ... → Edit code</li>
                                    <li>3. Open <code
                                            class="font-mono bg-slate-100 dark:bg-slate-700 px-1 rounded">theme.liquid</code>
                                    </li>
                                    <li>4. Paste chatbot script before <code
                                            class="font-mono bg-slate-100 dark:bg-slate-700 px-1 rounded">&lt;/body&gt;</code>
                                    </li>
                                </ol>
                                <p class="text-sm text-slate-500 italic">No Shopify app required. Works with all themes.
                                </p>
                            </div>
                        </section>

                        <section id="gtm-integration" class="mb-16 scroll-mt-32">
                            <h2 class="text-3xl font-bold mb-6">7. Google Tag Manager (GTM)</h2>
                            <div
                                class="bg-gradient-to-br from-white to-slate-50 dark:from-slate-800 dark:to-slate-900 p-8 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
                                <div
                                    class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 rounded-full -mr-16 -mt-16 blur-2xl group-hover:bg-indigo-500/20 transition-colors">
                                </div>
                                <h3 class="text-xl font-bold mb-4 text-indigo-600">Marketing & Agencies</h3>
                                <p class="mb-6">Ideal for restricted code access environments.</p>
                                <ul class="space-y-4">
                                    <li class="flex gap-4"><span
                                            class="w-6 h-6 bg-white dark:bg-slate-700 rounded-full flex items-center justify-center text-xs font-bold border border-indigo-100 dark:border-slate-600">1</span>
                                        Create a New Tag</li>
                                    <li class="flex gap-4"><span
                                            class="w-6 h-6 bg-white dark:bg-slate-700 rounded-full flex items-center justify-center text-xs font-bold border border-indigo-100 dark:border-slate-600">2</span>
                                        Tag Type: Custom HTML</li>
                                    <li class="flex gap-4"><span
                                            class="w-6 h-6 bg-white dark:bg-slate-700 rounded-full flex items-center justify-center text-xs font-bold border border-indigo-100 dark:border-slate-600">3</span>
                                        Paste chatbot script</li>
                                    <li class="flex gap-4"><span
                                            class="w-6 h-6 bg-white dark:bg-slate-700 rounded-full flex items-center justify-center text-xs font-bold border border-indigo-100 dark:border-slate-600">4</span>
                                        Trigger: All Pages</li>
                                </ul>
                            </div>
                        </section>

                        <section id="mobile-integration" class="mb-16 scroll-mt-32">
                            <h2 class="text-3xl font-bold mb-6">8. Mobile App Integration (WebView)</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div
                                    class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700">
                                    <h4 class="font-bold mb-4">How it works</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Load the website containing
                                        the chatbot inside a WebView. The script auto-loads, requiring no native SDK for
                                        basic functionality.</p>
                                </div>
                                <div
                                    class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700">
                                    <h4 class="font-bold mb-4">Supported</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(['Android', 'iOS', 'Flutter', 'React Native'] as $tech)
                                            <span
                                                class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 text-xs font-bold rounded-full uppercase">{{ $tech }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section id="configuration" class="mb-16 scroll-mt-32">
                            <h2 class="text-3xl font-bold mb-6">9. Chatbot Configuration</h2>
                            <p class="mb-8 font-medium">Configure everything from the Hemnix Assist Dashboard. Changes
                                apply instantly.</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach(['AI guidance & prompts', 'Business information', 'Language support', 'Lead capture fields', 'Escalation rules', 'Image & voice permissions', 'Analytics & usage limits'] as $config)
                                    <div
                                        class="flex items-center gap-3 p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-sm font-medium">{{ $config }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </section>

                        <section id="whatsapp-alerts" class="mb-16 scroll-mt-32">
                            <h2 class="text-3xl font-bold mb-6">10. WhatsApp Alerts (Admin)</h2>
                            <div
                                class="bg-green-50 dark:bg-green-900/10 border border-green-200 dark:border-green-800 p-8 rounded-2xl">
                                <h3
                                    class="text-xl font-bold mb-4 text-green-700 dark:text-green-400 flex items-center gap-2">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                    </svg>
                                    Real-time Notifications
                                </h3>
                                <p class="mb-4">Get notified instantly when a new conversation starts.</p>
                                <ul class="space-y-2 text-sm text-green-800 dark:text-green-300 opacity-80">
                                    <li>• Triggered on first user message only.</li>
                                    <li>• Sent once per conversation to avoid spam.</li>
                                </ul>
                            </div>
                        </section>

                        <section id="security" class="mb-16 scroll-mt-32">
                            <h2 class="text-3xl font-bold mb-6">11. Security & Domains</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div
                                    class="bg-white dark:bg-slate-800 p-8 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm transition-shadow hover:shadow-md">
                                    <h4 class="font-bold text-indigo-600 mb-4 uppercase tracking-tighter">Public Keys
                                    </h4>
                                    <p class="text-slate-600 dark:text-slate-400">Public keys are safe to expose in
                                        client-side script. They identify your chatbot but don't grant administrative
                                        access.</p>
                                </div>
                                <div
                                    class="bg-white dark:bg-slate-800 p-8 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm transition-shadow hover:shadow-md">
                                    <h4 class="font-bold text-indigo-600 mb-4 uppercase tracking-tighter">Domain
                                        Protection</h4>
                                    <p class="text-slate-600 dark:text-slate-400">Keys are restricted to allowed
                                        domains. Requests from unknown domains are rejected automatically.</p>
                                </div>
                            </div>
                        </section>

                        <hr class="border-slate-200 dark:border-slate-800 my-16">

                        <section id="footer-support" class="text-center">
                            <p class="text-lg text-slate-600 dark:text-slate-400 mb-8">Need more help? Our team is ready
                                to assist.</p>
                            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                                <a href="mailto:support@hemnix.com"
                                    class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-0.5 transition-all">Email
                                    Support</a>
                                <a href="https://www.hemnix.com" target="_blank"
                                    class="px-8 py-3 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-bold rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">Visit
                                    Website</a>
                            </div>
                        </section>
                    </div>
                </main>
            </div>
        </div>
    </div>
</x-frontend-layout>