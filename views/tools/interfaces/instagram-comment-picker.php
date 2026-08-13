<div class="space-y-6">
    <div id="ig-connector-block" class="border p-6 rounded-[20px] bg-[var(--color-surface-alt)] flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <span class="text-4xl">📸</span>
            <div>
                <h3 class="text-sm font-black">Meta OAuth Integration Portal</h3>
                <p class="text-xs text-[var(--color-ink-600)] mt-0.5">Link your Instagram Business or Creator Account securely via Meta Graph API.</p>
            </div>
        </div>
        <button onclick="connectMockInstagram()" class="btn-ember px-4 py-2.5 rounded-xl font-bold text-xs shadow-sm" id="btn-ig-connect">Connect Instagram Account</button>
    </div>

    <div class="bg-[var(--color-surface-alt)] border p-5 rounded-[20px] text-xs text-[var(--color-ink-600)] space-y-2">
        <h4 class="font-bold text-[var(--color-ink-900)] uppercase tracking-wider flex items-center gap-1">📋 How This Picker Works (Section 19.4):</h4>
        <ol class="list-decimal list-inside space-y-1 font-medium">
            <li>Only Business/Creator Instagram accounts can be connected, per Meta's API requirements.</li>
            <li>Comments are fetched live from Meta's Graph API each time you refresh — nothing is stored longer than needed.</li>
            <li>Keyword and mention rules are applied automatically using secure server-side validation.</li>
            <li>Last 5 draws are archived securely in your User Dashboard logs.</li>
        </ol>
    </div>

    <!-- Posts selection pane -->
    <div id="ig-posts-pane" class="hidden space-y-6">
        <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-400 border-b pb-2">Select Eligible Post or Reel</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="ig-posts-grid">
            <!-- Loaded dynamically from Controller -->
        </div>
    </div>

    <!-- Configuration Pane -->
    <div id="ig-setup-pane" class="hidden grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="col-span-1 bg-[var(--color-surface-alt)] p-5 rounded-[20px] border border-[var(--color-border)] space-y-4">
            <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-400 border-b pb-2 mb-3">Drawing Criteria (Section 19.2)</h4>

            <div>
                <label class="block text-[10px] font-bold uppercase mb-1">Number of Winners</label>
                <input type="number" id="ig-winners-count" value="1" min="1" max="50" class="input-custom w-full h-8 px-2 text-xs">
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase mb-1">Required Keyword</label>
                <input type="text" id="ig-keyword" value="DONE" class="input-custom w-full h-8 px-2 text-xs">
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase mb-1">Min Mentions Required</label>
                <input type="number" id="ig-mentions-count" value="2" class="input-custom w-full h-8 px-2 text-xs">
            </div>

            <label class="flex items-center gap-2 cursor-pointer py-1 text-xs font-semibold">
                <input type="checkbox" id="ig-one-entry" checked class="rounded text-[var(--color-ember-500)] focus:ring-[var(--color-ember-500)] border-[var(--color-border)]">
                One entry per user
            </label>

            <label class="flex items-center gap-2 cursor-pointer py-1 text-xs font-semibold">
                <input type="checkbox" id="ig-case-sensitive" class="rounded text-[var(--color-ember-500)] focus:ring-[var(--color-ember-500)] border-[var(--color-border)]">
                Case sensitivity
            </label>

            <button onclick="runEligibilityCheck()" class="btn-ember w-full py-2.5 rounded-xl font-bold text-xs">🔍 Run Eligibility Check</button>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div id="ig-stats-pane" class="hidden grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-3 bg-white border rounded-xl text-center">
                    <span class="block text-[9px] uppercase font-bold text-zinc-400">Total Fetch</span>
                    <span id="ig-stat-total" class="text-lg font-black">0</span>
                </div>
                <div class="p-3 bg-red-50 border rounded-xl text-center">
                    <span class="block text-[9px] uppercase font-bold text-zinc-400">Disqualified</span>
                    <span id="ig-stat-disq" class="text-lg font-black text-red-600">0</span>
                </div>
                <div class="p-3 bg-[var(--color-teal-100)] border rounded-xl text-center">
                    <span class="block text-[9px] uppercase font-bold text-zinc-400">Eligible Pool</span>
                    <span id="ig-stat-elig" class="text-lg font-black text-[var(--color-teal-500)]">0</span>
                </div>
            </div>

            <div id="ig-draw-pane" class="hidden text-center py-6">
                <button onclick="pickWinner()" class="btn-ember px-8 py-3 rounded-xl font-bold text-xs shadow-md">🏆 CHOOSE RANDOM WINNERS 🏆</button>
            </div>

            <div id="ig-winner-box" class="hidden p-6 bg-[var(--color-amber-100)] border-2 border-[var(--color-amber-400)] rounded-[20px] text-center space-y-4 animate-bounce">
                <span class="text-xl block font-black text-[var(--color-ink-900)]">👑 GIVEAWAY WINNER CHOSEN 👑</span>
                <strong class="text-base text-zinc-900 block" id="ig-win-username">@instagram_user</strong>
                <p id="ig-win-comment" class="text-sm font-bold bg-white/50 p-4 rounded-xl italic"></p>
                <div class="flex justify-center gap-2">
                    <button onclick="pickWinner()" class="btn-secondary px-3 py-1.5 text-xs bg-white text-zinc-800 rounded-lg">Redraw</button>
                    <button onclick="exportGiveawayResults()" class="btn-ember px-3.5 py-1.5 text-xs bg-zinc-900 text-white rounded-lg">Export CSV</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedPostId = null;
    let drawWinners = [];
    let eligibleCount = 0;

    async function connectMockInstagram() {
        const btn = document.getElementById('btn-ig-connect');
        btn.textContent = 'Connecting...';
        btn.disabled = true;

        const res = await fetch('/api/instagram/connect');
        const data = await res.json();

        if (data.success) {
            btn.textContent = `Connected as @${data.username} ✅`;
            loadInstagramPosts();
        } else {
            btn.textContent = 'Connect Account';
            btn.disabled = false;
            showToast('Connection failed.', false);
        }
    }

    async function loadInstagramPosts() {
        const res = await fetch('/api/instagram/posts');
        const data = await res.json();

        if (data.success) {
            let html = '';
            data.posts.forEach(post => {
                html += `
                    <button onclick="selectPost('${post.id}')" class="post-card text-left border rounded-xl p-3 bg-white flex gap-3 group hover:border-[var(--color-ember-500)] transition-colors">
                        <img src="${post.media_url}" class="w-16 h-16 object-cover rounded-lg border">
                        <div>
                            <span class="text-[10px] font-bold text-[var(--color-ember-500)] block">${post.media_type}</span>
                            <p class="text-xs font-semibold line-clamp-2 mt-0.5">${post.caption}</p>
                            <span class="text-[10px] text-zinc-400 block mt-1">💬 ${post.comments_count} comments</span>
                        </div>
                    </button>
                `;
            });
            document.getElementById('ig-posts-grid').innerHTML = html;
            document.getElementById('ig-posts-pane').classList.remove('hidden');
            showToast('Instagram accounts and posts loaded.');
        }
    }

    function selectPost(id) {
        selectedPostId = id;
        document.getElementById('ig-setup-pane').classList.remove('hidden');
        showToast('Selected Post #' + id);
    }

    async function runEligibilityCheck() {
        if (!selectedPostId) {
            showToast('Please select an eligible post first.', false);
            return;
        }

        const keyword = document.getElementById('ig-keyword').value;
        const minMentions = document.getElementById('ig-mentions-count').value;
        const winnerCount = document.getElementById('ig-winners-count').value;
        const oneEntry = document.getElementById('ig-one-entry').checked;
        const caseSensitive = document.getElementById('ig-case-sensitive').checked;

        const res = await fetch('/api/instagram/draw', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                instagram_post_id: selectedPostId,
                winner_count: winnerCount,
                keyword: keyword,
                min_mentions: minMentions,
                one_entry_per_user: oneEntry,
                case_sensitive: caseSensitive
            })
        });

        const data = await res.json();
        if (data.success) {
            document.getElementById('ig-stat-total').textContent = data.eligible_count + data.disqualified_count;
            document.getElementById('ig-stat-disq').textContent = data.disqualified_count;
            document.getElementById('ig-stat-elig').textContent = data.eligible_count;
            eligibleCount = data.eligible_count;
            drawWinners = data.winners;

            document.getElementById('ig-stats-pane').classList.remove('hidden');
            document.getElementById('ig-draw-pane').classList.remove('hidden');
            document.getElementById('ig-winner-box').classList.add('hidden');
            showToast('Eligibility checks processed!');
        } else {
            showToast('Eligibility check failed.', false);
        }
    }

    function pickWinner() {
        if (drawWinners.length === 0) {
            showToast('No eligible entries qualify for the drawing.', false);
            return;
        }

        // Choose a random winner from the server-validated winner subset
        const winner = drawWinners[Math.floor(Math.random() * drawWinners.length)];
        document.getElementById('ig-win-username').textContent = '@' + winner.username;
        document.getElementById('ig-win-comment').textContent = `"${winner.text}"`;
        document.getElementById('ig-winner-box').classList.remove('hidden');
        showToast('Winner Drawn Successfully! 🏆');
    }

    function exportGiveawayResults() {
        if (drawWinners.length === 0) return;
        let csv = 'Winner Username,Comment\n';
        drawWinners.forEach(w => {
            csv += `"${w.username}","${w.text.replace(/"/g, '""')}"\n`;
        });
        const blob = new Blob([csv], {type: 'text/csv'});
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'utilazy_giveaway_winners.csv';
        a.click();
    }
</script>
