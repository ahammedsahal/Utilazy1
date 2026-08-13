<?php
$pageTitle = "My Saved Notes — Utilazy";
?>

<div class="max-w-4xl mx-auto my-10 px-6 py-8 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-[20px] shadow-sm">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-[var(--color-ink-900)] tracking-tight">My Saved Notes Log</h1>
            <p class="text-xs text-[var(--color-ink-600)] mt-1">Review saved Encrypted & Decrypted notes logs associated with your account.</p>
        </div>
        <a href="/tools/encrypted-note" class="btn-ember px-4 py-2 text-xs font-bold rounded-xl shadow-sm">Create Encrypted Note</a>
    </div>

    <div class="space-y-4">
        <?php if (empty($savedNotes)): ?>
            <div class="p-8 text-center text-xs text-[var(--color-ink-600)] border border-[var(--color-border)] rounded-2xl bg-[var(--color-surface-alt)]">
                No saved encrypted or decrypted notes records found. Run the Encrypted Note tool to log saved records.
            </div>
        <?php else: ?>
            <?php foreach ($savedNotes as $note): ?>
                <?php
                    $meta = json_decode($note['metadata'], true);
                    $preview = $meta['note_preview'] ?? 'Encrypted plain text note';
                    $encoding = $meta['encoding'] ?? 'N/A';
                    $encryptionType = $meta['type'] ?? 'encoded';
                ?>
                <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-5 rounded-2xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-black text-[var(--color-ink-900)]"><?= htmlspecialchars($preview) ?></span>
                            <span class="text-[10px] uppercase font-bold text-[var(--color-ink-400)]">| Mode: <?= htmlspecialchars($encryptionType) ?></span>
                        </div>
                        <div class="text-[11px] text-[var(--color-ink-600)] space-x-3">
                            <span>Encoding scheme: <span class="font-bold text-[var(--color-ink-900)]"><?= htmlspecialchars($encoding) ?></span></span>
                            <span>Recorded At: <span class="text-[var(--color-ink-400)]"><?= $note['created_at'] ?></span></span>
                        </div>
                    </div>
                    <a href="/tools/encrypted-note" class="btn-secondary px-4 py-2 text-xs font-bold rounded-xl">Access Tool &rarr;</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
