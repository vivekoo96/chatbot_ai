(function() {
'use strict';

// Get the current script tag to extract data attributes
const currentScript = document.currentScript || document.querySelector('script[src*="embed.js"]');
const botId = currentScript?.getAttribute('data-bot-id');

if (!botId) {
console.error('ChatBot Error: data-bot-id attribute is required');
return;
}

// Configuration
const APP_URL = '{{ config("app.url") }}';
const IFRAME_URL = `${APP_URL}/widget/${botId}`;
const THEME_COLOR = '{{ $chatbot->theme_color ?? "#4F46E5" }}';
const THEME_COLOR_DARK = '{{ $chatbot ? $chatbot->theme_color . "cc" : "#7C3AED" }}';

// Create chat button
const chatButton = document.createElement('button');
chatButton.id = 'chatbot-toggle';
chatButton.innerHTML = `
<div class="chatbot-icon-wrapper"
    style="position: relative; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="chat-icon">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
    </svg>
</div>
`;
chatButton.style.cssText = `
position: fixed;
bottom: 25px;
right: 25px;
width: 65px;
height: 65px;
border-radius: 22px;
background: linear-gradient(135deg, ${THEME_COLOR} 0%, ${THEME_COLOR_DARK} 100%);
border: none;
cursor: pointer;
box-shadow: 0 10px 25px -5px ${THEME_COLOR}66;
z-index: 9998;
display: flex;
align-items: center;
justify-content: center;
color: white;
transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
transform: scale(0);
opacity: 0;
`;

// Create iframe container
const iframeContainer = document.createElement('div');
iframeContainer.id = 'chatbot-iframe-container';
iframeContainer.style.cssText = `
position: fixed;
bottom: 95px;
right: 25px;
width: 400px;
height: 650px;
max-width: calc(100vw - 40px);
max-height: calc(100vh - 120px);
border-radius: 24px;
box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.25);
z-index: 9999;
display: none;
overflow: hidden;
transform: translateY(20px) scale(0.95);
opacity: 0;
transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
transform-origin: bottom right;
border: 1px solid rgba(255, 255, 255, 0.1);
`;

const iframe = document.createElement('iframe');
iframe.src = IFRAME_URL;
iframe.style.cssText = `
width: 100%;
height: 100%;
border: none;
background: transparent;
`;
iframe.setAttribute('allow', 'clipboard-write; microphone');

iframeContainer.appendChild(iframe);

// Toggle chat
let isOpen = false;
chatButton.addEventListener('click', () => {
isOpen = !isOpen;
if (isOpen) {
iframeContainer.style.display = 'block';
setTimeout(() => {
iframeContainer.style.opacity = '1';
iframeContainer.style.transform = 'translateY(0) scale(1)';
}, 10);
chatButton.style.transform = 'rotate(90deg) scale(0.9)';
chatButton.innerHTML = `
<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
    <line x1="18" y1="6" x2="6" y2="18"></line>
    <line x1="6" y1="6" x2="18" y2="18"></line>
</svg>`;
} else {
iframeContainer.style.opacity = '0';
iframeContainer.style.transform = 'translateY(20px) scale(0.95)';
setTimeout(() => {
iframeContainer.style.display = 'none';
}, 400);
chatButton.style.transform = 'rotate(0deg) scale(1)';
chatButton.innerHTML = `
<div class="chatbot-icon-wrapper">
    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
    </svg>
</div>`;
}
});

// Listen for close message from iframe
window.addEventListener('message', (event) => {
if (event.data === 'closeChatbot') {
if (isOpen) {
chatButton.click(); // Trigger the toggle to close
}
}

// Support dynamic theme updates from iframe (fallback for old embed codes)
if (event.data?.type === 'chatbotConfig') {
const primary = event.data.themeColor;
const dark = primary + 'cc';
chatButton.style.background = `linear-gradient(135deg, ${primary} 0%, ${dark} 100%)`;
chatButton.style.boxShadow = `0 10px 25px -5px ${primary}66`;
}
});

// Append to body when ready
const init = () => {
document.body.appendChild(chatButton);
document.body.appendChild(iframeContainer);

// Animate button in
setTimeout(() => {
chatButton.style.transform = 'scale(1)';
chatButton.style.opacity = '1';
}, 500);
};

if (document.readyState === 'loading') {
document.addEventListener('DOMContentLoaded', init);
} else {
init();
}
})();