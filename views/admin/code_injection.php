<?php
$pageTitle = "Manage Code Injections — Utilazy";
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 my-10 space-y-8">
    <div class="flex items-center justify-between border-b border-[var(--color-border)] pb-6">
        <div>
            <h1 class="text-2xl font-black text-[var(--color-ink-900)] tracking-tight">System Code Injections</h1>
            <p class="text-xs text-[var(--color-ink-600)] mt-1">Manage external code injection snippets (analytics scripts, verification tags, custom stylesheets).</p>
        </div>
        <a href="/admin" class="btn-secondary px-4 py-2 text-xs font-bold rounded-xl">&larr; Back to Admin Console</a>
    </div>

    <!-- Security Warning Panel -->
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 text-xs text-red-800 space-y-1">
        <h4 class="font-black uppercase">⚠️ DANGEROUS ACTION WARNING</h4>
        <p>Injected scripts are rendered directly on all user screens. Incorrectly formatted markup or malicious scripts can break user interfaces or compromise sessions.</p>
    </div>

    <form action="/admin/code-injection/update" method="POST" class="space-y-6">
        <?= \App\Helpers\CSRF::input() ?>

        <?php foreach ($injections as $inj): ?>
            <div class="bg-[var(--color-surface)] border border-[var(--color-border)] p-6 rounded-[20px] shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-black uppercase text-[var(--color-ink-900)] block">Injection Placement: <?= htmlspecialchars($inj['location']) ?></span>
                        <span class="text-[10px] text-[var(--color-ink-400)]">Last updated: <?= $inj['updated_at'] ?></span>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-[var(--color-ink-600)]">
                        <input type="checkbox" name="injection[<?= $inj['id'] ?>][enabled]" value="1" <?= $inj['enabled'] ? 'checked' : '' ?> class="rounded text-[var(--color-ember-500)] focus:ring-[var(--color-ember-500)] border-[var(--color-border)]">
                        Enable Injection
                    </label>
                </div>

                <textarea name="injection[<?= $inj['id'] ?>][code]" placeholder="<!-- Enter <script> or <style> tags here -->" class="input-custom w-full h-44 p-4 text-xs font-mono bg-[var(--color-surface-alt)]"><?= htmlspecialchars($inj['code']) ?></textarea>
            </div>
        <?php endforeach; ?>

        <div class="text-right">
            <button type="submit" class="btn-ember px-6 py-2.5 text-xs font-bold rounded-xl shadow-sm">Save Code Injections</button>
        </div>
    </form>
</div>
