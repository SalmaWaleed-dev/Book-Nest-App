@auth
<div class="ai-floating" id="ai-assistant">
    <button type="button" class="ai-fab" id="ai-fab" aria-label="Open Library Assistant" aria-expanded="false">
        <span class="ai-fab-icon">🤖</span><span class="ai-fab-dot"></span>
    </button>

    <section class="ai-popover" id="ai-popover" aria-hidden="true">
        <div class="ai-popover-head">
            <div>
                <div class="status"><span class="dot"></span> Library Assistant · Online</div>
                <div class="ai-title">Hello! 👋</div>
            </div>
            <button type="button" class="ai-close" id="ai-close" aria-label="Close assistant">×</button>
        </div>
        <div class="ai-intro">I can help you find books, authors, categories, availability, recommendations, and explain how to use BookNest.</div>
        <div class="quick-actions">
            <button type="button" data-prompt="Recommend books for me">Recommend books</button>
            <button type="button" data-prompt="Show me available books">Available books</button>
            <button type="button" data-prompt="What categories do you have?">Categories</button>
            @if (auth()->user()->isAdmin())
                <button type="button" data-prompt="Library statistics">Statistics</button>
            @endif
        </div>
        <div class="messages" id="ai-chat-messages"></div>
        <form id="ai-chat-form">
            <input type="text" id="ai-chat-input" placeholder="Ask about the library..." autocomplete="off">
            <button type="submit" class="btn btn-green btn-sm">Send</button>
        </form>
    </section>
</div>
@endauth
