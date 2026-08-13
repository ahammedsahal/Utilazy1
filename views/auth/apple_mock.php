<?php
$pageTitle = "Sign In with Apple Sandbox";
?>
<div class="max-w-md mx-auto my-12 px-4">
    <div class="bg-black text-white p-8 rounded-[20px] shadow-lg">
        <div class="flex flex-col items-center mb-6">
            <svg class="w-10 h-10 fill-white mb-3" viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 4.17c.66-.81 1.11-1.93.99-3.06-1 .04-2.13.67-2.85 1.51-.62.73-1.16 1.87-1.01 2.98 1.1.09 2.15-.55 2.87-1.43z"/></svg>
            <h2 class="text-xl font-bold text-white">Sign in with Apple ID</h2>
            <p class="text-xs text-gray-400 mt-1">Use your Apple account to log in to <strong>Utilazy.com</strong></p>
        </div>

        <div class="bg-zinc-900 border border-zinc-800 text-zinc-300 text-xs px-3 py-2.5 rounded-lg mb-6">
            <strong>Developer Mock Mode:</strong> This page simulates Sign in with Apple. Apple's "Hide My Email" random relay address addresses are fully supported end-to-end!
        </div>

        <form action="/auth/apple/mock-submit" method="POST" class="space-y-4">
            <?= \App\Helpers\CSRF::input() ?>

            <div class="space-y-2">
                <button type="button" onclick="selectProfile('hide-my-email-relay-8837@privaterelay.appleid.com', 'Apple User (Relayed)', 'a_772837492')" class="w-full text-left p-3 border border-zinc-800 rounded-xl hover:bg-zinc-900 transition flex items-center gap-3 bg-zinc-950">
                    <span class="w-8 h-8 rounded-full bg-zinc-800 flex items-center justify-center font-bold text-white text-sm">🍎</span>
                    <div>
                        <div class="text-xs font-bold text-white">Apple User (Relayed Email)</div>
                        <div class="text-[10px] text-gray-400">hide-my-email-relay-8837@privaterelay.appleid.com</div>
                    </div>
                </button>

                <button type="button" onclick="selectProfile('steve.jobs@icloud.com', 'Steve Apple', 'a_002938472')" class="w-full text-left p-3 border border-zinc-800 rounded-xl hover:bg-zinc-900 transition flex items-center gap-3 bg-zinc-950">
                    <span class="w-8 h-8 rounded-full bg-zinc-800 flex items-center justify-center font-bold text-white text-sm">SJ</span>
                    <div>
                        <div class="text-xs font-bold text-white">Steve Apple (Standard iCloud)</div>
                        <div class="text-[10px] text-gray-400">steve.jobs@icloud.com</div>
                    </div>
                </button>
            </div>

            <div class="relative flex py-2 items-center">
                <div class="flex-grow border-t border-zinc-800"></div>
                <span class="flex-shrink mx-3 text-[10px] font-bold text-gray-500 uppercase">Or custom profile</span>
                <div class="flex-grow border-t border-zinc-800"></div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Full Name</label>
                <input type="text" id="mock_name" name="name" value="Mock Apple User" required class="w-full h-10 px-3 bg-zinc-950 border border-zinc-800 rounded-lg text-sm text-white focus:outline-none focus:border-white">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Apple Email ID</label>
                <input type="email" id="mock_email" name="email" value="mockuser.apple@icloud.com" required class="w-full h-10 px-3 bg-zinc-950 border border-zinc-800 rounded-lg text-sm text-white focus:outline-none focus:border-white">
            </div>

            <input type="hidden" id="mock_apple_id" name="apple_id" value="a_1234567890">

            <button type="submit" class="w-full h-11 bg-white hover:bg-gray-100 text-black rounded-lg font-bold text-sm tracking-wide transition shadow-sm mt-2">
                Continue with Apple ID &rarr;
            </button>
        </form>
    </div>
</div>

<script>
function selectProfile(email, name, id) {
    document.getElementById('mock_email').value = email;
    document.getElementById('mock_name').value = name;
    document.getElementById('mock_apple_id').value = id;
    document.querySelector('form').submit();
}
</script>