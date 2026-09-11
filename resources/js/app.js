document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.getElementById('sidebar-toggle');
  const sidebar = document.getElementById('sidebar');
  if (toggle && sidebar) {
    toggle.addEventListener('click', () => sidebar.classList.toggle('open'));
  }

  const aiRoot = document.getElementById('ai-assistant');
  const fab = document.getElementById('ai-fab');
  const close = document.getElementById('ai-close');
  const form = document.getElementById('ai-chat-form');
  const input = document.getElementById('ai-chat-input');
  const messages = document.getElementById('ai-chat-messages');
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

  function openAssistant(prompt = null) {
    if (!aiRoot) return;
    aiRoot.classList.add('open');
    fab?.setAttribute('aria-expanded', 'true');
    document.getElementById('ai-popover')?.setAttribute('aria-hidden', 'false');
    if (prompt && input) {
      input.value = prompt;
      input.focus();
    } else {
      input?.focus();
    }
  }

  function closeAssistant() {
    aiRoot?.classList.remove('open');
    fab?.setAttribute('aria-expanded', 'false');
    document.getElementById('ai-popover')?.setAttribute('aria-hidden', 'true');
  }

  fab?.addEventListener('click', () => aiRoot.classList.contains('open') ? closeAssistant() : openAssistant());
  close?.addEventListener('click', closeAssistant);

  function appendMessage(text, who) {
    if (!messages) return;
    const div = document.createElement('div');
    div.className = 'msg ' + who;
    div.textContent = text;
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
  }

  async function sendMessage(text, extra = {}) {
    if (!text?.trim() || !messages) return;
    appendMessage(text.trim(), 'user');
    try {
      const res = await fetch('/ai/chat', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: JSON.stringify({ message: text.trim(), ...extra }),
      });
      const data = await res.json();
      appendMessage(data.reply || 'Sorry, something went wrong.', 'bot');
    } catch (e) {
      appendMessage('Sorry, the Library Assistant is temporarily unavailable. Please try again later.', 'bot');
    }
  }

  form?.addEventListener('submit', (e) => {
    e.preventDefault();
    const text = input?.value || '';
    if (input) input.value = '';
    sendMessage(text);
  });

  document.querySelectorAll('.ai-popover .quick-actions button').forEach((btn) => {
    btn.addEventListener('click', () => sendMessage(btn.dataset.prompt || btn.textContent));
  });

  document.querySelectorAll('[data-ai-book]').forEach((btn) => {
    btn.addEventListener('click', () => {
      openAssistant();
      sendMessage(`Tell me about this book`, { book_id: Number(btn.dataset.aiBook) });
    });
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') closeAssistant();
  });
});
