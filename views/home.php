<!-- Hero Block -->
<section class="py-20 text-center bg-gradient-to-b from-[var(--color-surface)] to-[var(--color-bg)]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-[var(--color-ink-900)] tracking-tight leading-none mb-6">
            Smart Tools for <span class="text-[var(--color-ember-500)]">Everyday Tasks.</span>
        </h1>
        <p class="text-base sm:text-lg text-[var(--color-ink-600)] max-w-2xl mx-auto mb-8 font-medium">
            Convert, create, compress, calculate, generate and simplify your digital tasks with our secure, lightning-fast client-side utilities.
        </p>

        <!-- CTAs -->
        <div class="flex justify-center gap-4 mb-10">
            <a href="/tools" class="btn-ember px-6 py-3.5 rounded-xl font-bold text-sm tracking-wide shadow-md">
                Explore Tools &rarr;
            </a>
            <a href="/register" class="btn-secondary px-6 py-3.5 rounded-xl font-bold text-sm">
                Get Started Free
            </a>
        </div>

        <!-- Live Instant Search Bar -->
        <div class="max-w-lg mx-auto relative mb-6">
            <input type="text" id="home-search-input" onkeyup="instantSearch()" placeholder="Search tools (e.g. compress, case, picker)..." class="input-custom w-full h-[54px] px-5 text-sm font-semibold pl-12 shadow-sm">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>

            <!-- Floating Dropdown Result Pane -->
            <div id="instant-search-results" class="hidden absolute left-0 right-0 mt-2 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-2xl shadow-xl z-30 p-4 max-h-[300px] overflow-y-auto text-left space-y-2">
                <!-- Javascript will draw items -->
            </div>
        </div>

        <!-- Category Chips -->
        <div class="flex flex-wrap justify-center gap-2 max-w-2xl mx-auto">
            <?php foreach ($categories as $cat): ?>
                <a href="/tools?category=<?= $cat['slug'] ?>" class="px-3.5 py-2 bg-[var(--color-surface)] hover:bg-[var(--color-border)] border border-[var(--color-border)] rounded-full text-xs font-bold text-[var(--color-ink-600)] hover:text-[var(--color-ember-500)] transition-all">
                    <?= $cat['icon'] ?> <?= htmlspecialchars($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Tools Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 border-t border-[var(--color-border)]">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-[var(--color-ink-900)]">⭐ Featured Utilities</h2>
            <p class="text-xs text-[var(--color-ink-600)] mt-1">Our most advanced and frequently run digital utilities.</p>
        </div>
        <a href="/tools" class="text-xs font-bold text-[var(--color-ember-500)] hover:underline">View All &rarr;</a>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($featured as $tl): ?>
            <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-6 rounded-[20px] shadow-sm flex flex-col justify-between hover:translate-y-[-2px] transition-transform duration-150">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-3xl p-2.5 bg-[var(--color-surface-alt)] border border-[var(--color-border)] rounded-xl"><?= $tl['icon'] ?></span>

                        <?php if ($tl['access_type'] === 'free'): ?>
                            <span class="text-[10px] bg-[var(--color-amber-100)] text-[var(--color-ink-900)] font-black px-2.5 py-1 rounded-full uppercase tracking-wider">Free</span>
                        <?php else: ?>
                            <span class="text-[10px] bg-[var(--color-teal-100)] text-[var(--color-teal-500)] font-black px-2.5 py-1 rounded-full uppercase tracking-wider"><?= $tl['token_cost'] ?> Tokens</span>
                        <?php endif; ?>
                    </div>
                    <h3 class="text-base font-bold text-[var(--color-ink-900)] mb-1"><?= htmlspecialchars($tl['name']) ?></h3>
                    <p class="text-xs text-[var(--color-ink-600)] leading-relaxed mb-6"><?= htmlspecialchars($tl['description']) ?></p>
                </div>
                <a href="/tools/<?= $tl['slug'] ?>" class="text-xs font-bold text-[var(--color-ember-500)] hover:text-[var(--color-ember-600)] flex items-center gap-1 group-hover:underline">
                    Open Tool <span class="transition-transform group-hover:translate-x-1">&rarr;</span>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Search and filters logic -->
<script>
    const toolsRegistry = [
        { name: 'Instagram Comment Picker', slug: 'instagram-comment-picker', desc: 'Run giveaways, filter comments, and pick winners.', icon: '📸' },
        { name: 'Invoice Generator', slug: 'invoice-generator', desc: 'Create elegant invoices and export PDF.', icon: '🧾' },
        { name: 'URL Shortener', slug: 'url-shortener', desc: 'Shorten links and track click statistics.', icon: '🔗' },
        { name: 'Custom URL Shortener', slug: 'custom-url-shortener', desc: 'Create editable custom redirected path links.', icon: '🎯' },
        { name: 'Case Converter', slug: 'case-converter', desc: 'Convert text casings between snake, camel, title, etc.', icon: '🔠' },
        { name: 'PDF Editor', slug: 'pdf-editor', desc: 'Highlight, write, draw and delete PDF pages.', icon: '🖊️' },
        { name: 'PDF Compressor', slug: 'pdf-compressor', desc: 'Compress PDF file size instantly in browser.', icon: '🗜️' },
        { name: 'Image Compressor', slug: 'image-compressor', desc: 'Compress JPG, PNG, and WebP file sizes.', icon: '📉' },
        { name: 'Encrypted Note', slug: 'encrypted-note', desc: 'Secure notes using AES passcode encryption.', icon: '🔐' },
        { name: 'Random Spin Wheel', slug: 'spin-wheel', desc: 'Spin random items or choices on a wheel.', icon: '🎡' },
        { name: 'Random UUID Generator', slug: 'uuid-generator', desc: 'Batch generate UUID v4 random tokens.', icon: '🔑' },
        { name: 'Notepad', slug: 'notepad', desc: 'Auto saving browser notepad text draft organizer.', icon: '🗒️' }
    ];

    function instantSearch() {
        const query = document.getElementById('home-search-input').value.trim().toLowerCase();
        const resultsBox = document.getElementById('instant-search-results');

        if (query === '') {
            resultsBox.classList.add('hidden');
            return;
        }

        const matches = toolsRegistry.filter(t => t.name.toLowerCase().includes(query) || t.desc.toLowerCase().includes(query));

        if (matches.length === 0) {
            resultsBox.innerHTML = '<div class="text-xs text-gray-400 p-2 text-center">No tools found matching your query.</div>';
        } else {
            resultsBox.innerHTML = matches.map(t => `
                <a href="/tools/${t.slug}" class="flex items-center gap-3 p-2 bg-[var(--color-surface-alt)] hover:bg-[var(--color-border)] rounded-xl transition border border-[var(--color-border)] group">
                    <span class="text-xl">${t.icon}</span>
                    <div>
                        <span class="text-xs font-bold text-[var(--color-ink-900)] block">${t.name}</span>
                        <span class="text-[10px] text-gray-500 block">${t.desc}</span>
                    </div>
                </a>
            `).join('');
        }

        resultsBox.classList.remove('hidden');
    }

    document.addEventListener('click', (e) => {
        if (!e.target.closest('#home-search-input')) {
            document.getElementById('instant-search-results').classList.add('hidden');
        }
    });
</script>
