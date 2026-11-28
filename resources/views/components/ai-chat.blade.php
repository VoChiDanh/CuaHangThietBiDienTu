<!-- FLOATING SPEED DIAL & AI CHATBOT WIDGET -->
<div id="dnd-ai-widget" class="fixed bottom-6 right-6 z-50 flex flex-col items-end">

    <!-- ==================== 1. SPEED DIAL 2 VÒNG TRÒN (TƯ VẤN AI & CSKH) ==================== -->
    <div id="ai-speed-dial-menu" class="hidden flex flex-col items-end gap-3 mb-3 transition-all duration-300">
        
        <!-- VÒNG TRÒN 1: CSKH (CHĂM SÓC KHÁCH HÀNG) -->
        <div class="flex items-center gap-2 group cursor-pointer" onclick="window.DndAI.openCSKH()">
            <span class="px-3 py-1.5 rounded-xl bg-white/95 backdrop-blur-xs text-slate-800 text-xs font-bold shadow-lg border border-slate-200/80 whitespace-nowrap transition-transform group-hover:scale-105">
                🎧 CSKH (1900 8888)
            </span>
            <button 
                type="button"
                class="w-12 h-12 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-600 text-white shadow-lg shadow-emerald-500/30 flex items-center justify-center hover:scale-110 active:scale-95 transition-all text-base focus:outline-hidden"
                title="Chăm Sóc Khách Hàng"
            >
                <i class="fa-solid fa-headset"></i>
            </button>
        </div>

        <!-- VÒNG TRÒN 2: TƯ VẤN AI (GEMINI) -->
        <div class="flex items-center gap-2 group cursor-pointer" onclick="window.DndAI.openAIChatFromMenu()">
            <span class="px-3 py-1.5 rounded-xl bg-white/95 backdrop-blur-xs text-slate-800 text-xs font-bold shadow-lg border border-slate-200/80 whitespace-nowrap transition-transform group-hover:scale-105">
                ✨ Tư vấn AI (Gemini)
            </span>
            <button 
                type="button"
                class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30 flex items-center justify-center hover:scale-110 active:scale-95 transition-all text-base focus:outline-hidden"
                title="Tư vấn AI"
            >
                <i class="fa-solid fa-sparkles"></i>
            </button>
        </div>

    </div>

    <!-- ==================== 2. NÚT CHÍNH FLOATING ACTION BUTTON (FAB) ==================== -->
    <button 
        id="ai-chat-toggle-btn" 
        type="button"
        onclick="window.DndAI.toggleSpeedDial()"
        class="relative flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 text-white shadow-xl shadow-indigo-500/30 hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-hidden"
        title="Trung tâm hỗ trợ khách hàng"
    >
        <!-- Online Indicator Badge -->
        <span class="absolute -top-1 -right-1 flex h-4 w-4">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 border-2 border-white"></span>
        </span>
        <i id="ai-toggle-icon-open" class="fa-solid fa-comments text-xl transition-transform duration-300"></i>
        <i id="ai-toggle-icon-close" class="fa-solid fa-xmark text-xl hidden transition-transform duration-300"></i>
    </button>

    <!-- ==================== 3. CỬA SỔ CSKH (CUSTOMER SUPPORT POPUP) ==================== -->
    <div 
        id="cskh-window" 
        class="hidden fixed sm:absolute bottom-0 sm:bottom-20 right-0 w-full sm:w-80 bg-white sm:rounded-3xl shadow-2xl border border-slate-200/80 overflow-hidden z-50 transition-all duration-300"
    >
        <!-- CSKH Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 text-white p-4 flex items-center justify-between shrink-0 shadow-sm">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white border border-white/20">
                    <i class="fa-solid fa-headset text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm leading-none">Chăm Sóc Khách Hàng</h3>
                    <p class="text-[11px] text-emerald-100 font-normal mt-1 leading-none">D&D Support 24/7</p>
                </div>
            </div>
            <button type="button" onclick="window.DndAI.closeCSKH()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white text-xs transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- CSKH Body -->
        <div class="p-5 space-y-3 text-xs text-slate-700">
            <!-- Hotline -->
            <a href="tel:19008888" class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 hover:bg-emerald-50 border border-slate-200/70 hover:border-emerald-300 transition-all group">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-phone-volume"></i>
                    </div>
                    <div>
                        <div class="font-extrabold text-slate-900 text-xs">Hotline miễn phí</div>
                        <div class="text-[11px] text-emerald-600 font-bold">1900 8888</div>
                    </div>
                </div>
                <span class="text-[10px] font-bold text-slate-400 group-hover:text-emerald-600">Gọi ngay &rarr;</span>
            </a>

            <!-- Zalo -->
            <a href="https://zalo.me" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 hover:bg-blue-50 border border-slate-200/70 hover:border-blue-300 transition-all group">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-sm group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-comment-dots"></i>
                    </div>
                    <div>
                        <div class="font-extrabold text-slate-900 text-xs">Zalo Hỗ Trợ Kỹ Thuật</div>
                        <div class="text-[11px] text-slate-500">Phản hồi trong 5 phút</div>
                    </div>
                </div>
                <span class="text-[10px] font-bold text-slate-400 group-hover:text-blue-600">Nhắn tin &rarr;</span>
            </a>

            <!-- Support Details -->
            <div class="p-3 rounded-2xl bg-slate-50 text-[11px] text-slate-500 space-y-1.5 border border-slate-200/50">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-clock text-slate-400"></i>
                    <span>Giờ làm việc: <strong>8:00 - 21:30</strong> hàng ngày</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-slate-400"></i>
                    <span>Bảo hành chính hãng 12-36 tháng</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-envelope text-slate-400"></i>
                    <span>Email: <strong>cskh@dnd.vn</strong></span>
                </div>
            </div>

            <!-- Switch to AI button -->
            <button 
                type="button" 
                onclick="window.DndAI.closeCSKH(); window.DndAI.openChat();"
                class="w-full py-2.5 rounded-full bg-[#7C3AED] hover:bg-[#6D28D9] text-white font-bold text-xs shadow-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer"
            >
                <i class="fa-solid fa-sparkles text-[11px]"></i>
                <span>Cần tư vấn thông số? Hỏi AI ngay</span>
            </button>
        </div>
    </div>

    <!-- ==================== 4. CỬA SỔ CHAT TRỢ LÝ AI (AI CHAT WINDOW) ==================== -->
    <div 
        id="ai-chat-window" 
        class="hidden fixed sm:absolute bottom-0 sm:bottom-20 right-0 w-full sm:w-96 sm:max-w-md h-[85vh] sm:h-[550px] bg-white sm:rounded-3xl shadow-2xl border border-slate-200/80 flex flex-col overflow-hidden z-50 transition-all duration-300"
    >
        <!-- Chat Header -->
        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white p-4 flex items-center justify-between shrink-0 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white border border-white/30 shadow-inner">
                    <i class="fa-solid fa-robot text-lg"></i>
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <h3 class="font-bold text-sm tracking-tight leading-none">D&D AI</h3>
                        <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded-full bg-emerald-400/20 text-emerald-200 border border-emerald-300/30">Online</span>
                    </div>
                    <p class="text-[11px] text-white/80 font-normal mt-1 leading-none">Tư vấn thông số kỹ thuật 24/7</p>
                </div>
            </div>
            <button 
                type="button" 
                onclick="window.DndAI.closeChat()" 
                class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors text-sm"
            >
                <i class="fa-solid fa-chevron-down"></i>
            </button>
        </div>

        <!-- Chat Messages Container -->
        <div id="ai-chat-messages" class="flex-1 p-4 overflow-y-auto space-y-3.5 text-xs bg-slate-50/50">
            <!-- Bot Greeting Message -->
            <div class="flex gap-2.5 items-start">
                <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center shrink-0 shadow-xs text-xs">
                    <i class="fa-solid fa-sparkles text-[10px]"></i>
                </div>
                <div class="bg-white border border-slate-200/70 rounded-2xl rounded-tl-xs p-3 text-slate-800 shadow-xs max-w-[85%] leading-relaxed">
                    👋 Chào bạn! Tôi là <strong>D&D AI</strong>. Bạn đang tìm Laptop văn phòng mỏng nhẹ, máy Gaming hay muốn so sánh chi tiết pin/cân nặng của sản phẩm nào không?
                </div>
            </div>

            <!-- Quick Suggestion Chips -->
            <div id="ai-quick-prompts" class="space-y-1.5 pt-1">
                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Gợi ý câu hỏi nhanh:</p>
                <div class="flex flex-wrap gap-1.5">
                    <button type="button" onclick="window.DndAI.sendPrompt('Máy nào pin trâu dùng cả ngày tốt nhất?')" class="px-2.5 py-1 bg-white hover:bg-blue-50 text-slate-700 hover:text-blue-600 rounded-full border border-slate-200 text-[11px] transition-colors">
                        🔋 Máy nào pin trâu nhất?
                    </button>
                    <button type="button" onclick="window.DndAI.sendPrompt('Laptop siêu nhẹ dưới 1.3kg mang đi làm?')" class="px-2.5 py-1 bg-white hover:bg-blue-50 text-slate-700 hover:text-blue-600 rounded-full border border-slate-200 text-[11px] transition-colors">
                        🪶 Laptop siêu nhẹ &lt; 1.3kg
                    </button>
                    <button type="button" onclick="window.DndAI.sendPrompt('Tư vấn laptop gaming mượt mà dưới 25 triệu?')" class="px-2.5 py-1 bg-white hover:bg-blue-50 text-slate-700 hover:text-blue-600 rounded-full border border-slate-200 text-[11px] transition-colors">
                        🎮 Gaming dưới 25 triệu
                    </button>
                    <button type="button" onclick="window.DndAI.sendPrompt('So sánh MacBook Air M4 và Dell XPS 13?')" class="px-2.5 py-1 bg-white hover:bg-blue-50 text-slate-700 hover:text-blue-600 rounded-full border border-slate-200 text-[11px] transition-colors">
                        ⚖️ So sánh MacBook vs XPS
                    </button>
                </div>
            </div>
        </div>

        <!-- Chat Input Footer -->
        <div class="p-3 bg-white border-t border-slate-100 shrink-0">
            <form id="ai-chat-form" onsubmit="window.DndAI.handleSubmit(event)" class="flex items-center gap-2">
                <input 
                    type="text" 
                    id="ai-chat-input"
                    placeholder="Hỏi về cấu hình, pin, cân nặng..." 
                    class="flex-1 min-h-0 h-10 py-2 px-4 text-xs bg-slate-100 focus:bg-white text-slate-900 rounded-full border border-transparent focus:border-[#7C3AED] focus:outline-hidden focus:ring-2 focus:ring-purple-500/20 transition-all placeholder:text-slate-400"
                    autocomplete="off"
                >
                <button 
                    type="submit" 
                    id="ai-chat-send-btn"
                    class="w-10 h-10 rounded-full bg-[#7C3AED] hover:bg-[#6D28D9] text-white flex items-center justify-center shrink-0 hover:shadow-md hover:scale-105 active:scale-95 transition-all text-xs cursor-pointer"
                >
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
            <div class="flex items-center justify-between text-[10px] text-slate-400 mt-2 px-1">
                <span>Trí tuệ nhân tạo Gemini &bull; Phản hồi tự động</span>
                <span class="hover:underline cursor-pointer" onclick="window.DndAI.clearChat()">Xóa chat</span>
            </div>
        </div>
    </div>
</div>

<script>
window.DndAI = (function() {
    let isSpeedDialOpen = false;
    let isChatOpen = false;
    let isCSKHOpen = false;

    let sessionId = localStorage.getItem('dnd_ai_session') || localStorage.getItem('smarttech_ai_session') || ('sess_' + Math.random().toString(36).substring(2, 12));
    localStorage.setItem('dnd_ai_session', sessionId);

    let currentProductId = null;

    function setProductContext(productId) {
        currentProductId = productId;
    }

    // Toggle Speed Dial Menu
    function toggleSpeedDial() {
        if (isChatOpen) {
            closeChat();
            return;
        }
        if (isCSKHOpen) {
            closeCSKH();
            return;
        }

        isSpeedDialOpen ? closeSpeedDial() : openSpeedDial();
    }

    function openSpeedDial() {
        isSpeedDialOpen = true;
        document.getElementById('ai-speed-dial-menu').classList.remove('hidden');
        document.getElementById('ai-toggle-icon-open').classList.add('hidden');
        document.getElementById('ai-toggle-icon-close').classList.remove('hidden');
    }

    function closeSpeedDial() {
        isSpeedDialOpen = false;
        document.getElementById('ai-speed-dial-menu').classList.add('hidden');
        if (!isChatOpen && !isCSKHOpen) {
            document.getElementById('ai-toggle-icon-open').classList.remove('hidden');
            document.getElementById('ai-toggle-icon-close').classList.add('hidden');
        }
    }

    // Mở Chat AI từ menu Speed Dial
    function openAIChatFromMenu() {
        closeSpeedDial();
        openChat();
    }

    // Mở Chat AI trực tiếp (ví dụ từ nút Hỏi AI trên sản phẩm)
    function openChat() {
        isChatOpen = true;
        isCSKHOpen = false;
        closeSpeedDial();

        document.getElementById('cskh-window').classList.add('hidden');
        document.getElementById('ai-chat-window').classList.remove('hidden');

        document.getElementById('ai-toggle-icon-open').classList.add('hidden');
        document.getElementById('ai-toggle-icon-close').classList.remove('hidden');

        const input = document.getElementById('ai-chat-input');
        if (input) input.focus();
        scrollToBottom();
    }

    function closeChat() {
        isChatOpen = false;
        document.getElementById('ai-chat-window').classList.add('hidden');
        document.getElementById('ai-toggle-icon-open').classList.remove('hidden');
        document.getElementById('ai-toggle-icon-close').classList.add('hidden');
    }

    // Mở popup CSKH
    function openCSKH() {
        isCSKHOpen = true;
        isChatOpen = false;
        closeSpeedDial();

        document.getElementById('ai-chat-window').classList.add('hidden');
        document.getElementById('cskh-window').classList.remove('hidden');

        document.getElementById('ai-toggle-icon-open').classList.add('hidden');
        document.getElementById('ai-toggle-icon-close').classList.remove('hidden');
    }

    function closeCSKH() {
        isCSKHOpen = false;
        document.getElementById('cskh-window').classList.add('hidden');
        document.getElementById('ai-toggle-icon-open').classList.remove('hidden');
        document.getElementById('ai-toggle-icon-close').classList.add('hidden');
    }

    function toggleChat() {
        isChatOpen ? closeChat() : openChat();
    }

    function sendPrompt(text) {
        const input = document.getElementById('ai-chat-input');
        input.value = text;
        handleSubmit(new Event('submit'));
    }

    async function handleSubmit(e) {
        if (e) e.preventDefault();
        const input = document.getElementById('ai-chat-input');
        const message = input.value.trim();
        if (!message) return;

        input.value = '';

        // Hiển thị tin nhắn người dùng
        appendUserMessage(message);

        // Ẩn các nút gợi ý sau lần chat đầu
        const quickPrompts = document.getElementById('ai-quick-prompts');
        if (quickPrompts) quickPrompts.classList.add('hidden');

        // Hiển thị typing indicator
        const typingId = appendTypingIndicator();
        scrollToBottom();

        try {
            const res = await fetch('/api/ai/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    message: message,
                    session_id: sessionId,
                    product_id: currentProductId
                })
            });

            const data = await res.json();
            removeTypingIndicator(typingId);

            if (data.success && data.reply) {
                appendBotMessage(data.reply);
            } else {
                appendBotMessage('Dạ hiện tại em đang gặp chút gián đoạn kết nối, bạn thử hỏi lại câu hỏi khác nhé!');
            }
        } catch (err) {
            removeTypingIndicator(typingId);
            appendBotMessage('Không thể kết nối đến máy chủ AI. Vui lòng kiểm tra lại đường truyền!');
        }

        scrollToBottom();
    }

    function appendUserMessage(text) {
        const container = document.getElementById('ai-chat-messages');
        const div = document.createElement('div');
        div.className = 'flex justify-end';
        div.innerHTML = `
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl rounded-tr-xs p-3 shadow-xs max-w-[85%] leading-relaxed text-xs">
                ${escapeHtml(text)}
            </div>
        `;
        container.appendChild(div);
    }

    function appendBotMessage(text) {
        const container = document.getElementById('ai-chat-messages');
        const div = document.createElement('div');
        div.className = 'flex gap-2.5 items-start';
        div.innerHTML = `
            <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center shrink-0 shadow-xs text-xs">
                <i class="fa-solid fa-sparkles text-[10px]"></i>
            </div>
            <div class="bg-white border border-slate-200/70 rounded-2xl rounded-tl-xs p-3 text-slate-800 shadow-xs max-w-[85%] leading-relaxed text-xs whitespace-pre-line">
                ${formatBotMessage(text)}
            </div>
        `;
        container.appendChild(div);
    }

    function appendTypingIndicator() {
        const container = document.getElementById('ai-chat-messages');
        const id = 'typing-' + Date.now();
        const div = document.createElement('div');
        div.id = id;
        div.className = 'flex gap-2.5 items-start';
        div.innerHTML = `
            <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center shrink-0 text-xs">
                <i class="fa-solid fa-sparkles text-[10px]"></i>
            </div>
            <div class="bg-white border border-slate-200/70 rounded-2xl rounded-tl-xs p-3 shadow-xs">
                <div class="flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-bounce"></span>
                    <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-bounce [animation-delay:0.2s]"></span>
                    <span class="w-1.5 h-1.5 bg-purple-500 rounded-full animate-bounce [animation-delay:0.4s]"></span>
                </div>
            </div>
        `;
        container.appendChild(div);
        return id;
    }

    function removeTypingIndicator(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function clearChat() {
        const container = document.getElementById('ai-chat-messages');
        container.innerHTML = `
            <div class="flex gap-2.5 items-start">
                <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center shrink-0 shadow-xs text-xs">
                    <i class="fa-solid fa-sparkles text-[10px]"></i>
                </div>
                <div class="bg-white border border-slate-200/70 rounded-2xl rounded-tl-xs p-3 text-slate-800 shadow-xs max-w-[85%] leading-relaxed">
                    👋 Cuộc trò chuyện đã được làm mới. Em có thể hỗ trợ gì thêm cho bạn về các dòng máy công nghệ ạ?
                </div>
            </div>
        `;
    }

    function scrollToBottom() {
        const container = document.getElementById('ai-chat-messages');
        if (container) container.scrollTop = container.scrollHeight;
    }

    function formatBotMessage(str) {
        return escapeHtml(str)
            .replace(/\*\*(.+?)\*\*/g, '<strong class="font-semibold text-slate-900">$1</strong>')
            .replace(/(^|\n)\*\s+/g, '$1• ')
            .replace(/\*([^*\n]+)\*/g, '<span class="font-medium text-slate-800">$1</span>');
    }

    function escapeHtml(str) {
        return (str || '').replace(/[&<>"']/g, function(m) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
        });
    }

    return {
        toggleSpeedDial,
        openSpeedDial,
        closeSpeedDial,
        openAIChatFromMenu,
        openCSKH,
        closeCSKH,
        toggleChat,
        openChat,
        closeChat,
        sendPrompt,
        handleSubmit,
        clearChat,
        setProductContext
    };
})();
window.SmartTechAI = window.DndAI;
</script>