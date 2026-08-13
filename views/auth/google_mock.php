<?php
$pageTitle = "Google Account Sign-In Sandbox";
?>
<div class="max-w-md mx-auto my-12 px-4">
    <div class="bg-white border border-gray-200 p-8 rounded-[20px] shadow-lg text-gray-800">
        <div class="flex flex-col items-center mb-6">
            <svg class="w-10 h-10 mb-3" viewBox="0 0 24 24"><path fill="#EA4335" d="M12.24 10.285V14.4h6.887c-.275 1.565-1.88 4.604-6.887 4.604-4.33 0-7.859-3.578-7.859-8s3.53-8 7.859-8c2.46 0 4.105 1.025 5.047 1.926l3.227-3.107C18.29 1.92 15.44 1 12.24 1 6.133 1 1.144 5.914 1.144 12s4.99 11 11.096 11c6.375 0 10.608-4.414 10.608-10.78 0-.726-.077-1.282-.175-1.935H12.24z"/></svg>
            <h2 class="text-xl font-bold text-gray-900">Sign in with Google</h2>
            <p class="text-xs text-gray-500 mt-1">to continue to <strong>Utilazy.com</strong></p>
        </div>

        <div class="bg-blue-50 border border-blue-200 text-blue-800 text-xs px-3 py-2.5 rounded-lg mb-6">
            <strong>Developer Mock Mode:</strong> This page simulates Google Identity Services. Select an account or input custom mock details to login instantly.
        </div>

        <form action="/auth/google/mock-submit" method="POST" class="space-y-4">
            <?= \App\Helpers\CSRF::input() ?>

            <div class="space-y-2">
                <button type="button" onclick="selectProfile('google_dev@gmail.com', 'Alex Mercer', 'g_992837482')" class="w-full text-left p-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-600 text-sm">AM</span>
                    <div>
                        <div class="text-xs font-bold text-gray-900">Alex Mercer (Developer)</div>
                        <div class="text-[10px] text-gray-500">google_dev@gmail.com</div>
                    </div>
                </button>

                <button type="button" onclick="selectProfile('tester.utilazy@gmail.com', 'Jane Tester', 'g_002938472')" class="w-full text-left p-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center font-bold text-green-600 text-sm">JT</span>
                    <div>
                        <div class="text-xs font-bold text-gray-900">Jane Tester (Beta User)</div>
                        <div class="text-[10px] text-gray-500">tester.utilazy@gmail.com</div>
                    </div>
                </button>
            </div>

            <div class="relative flex py-2 items-center">
                <div class="flex-grow border-t border-gray-200"></div>
                <span class="flex-shrink mx-3 text-[10px] font-bold text-gray-400 uppercase">Or custom profile</span>
                <div class="flex-grow border-t border-gray-200"></div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Full Name</label>
                <input type="text" id="mock_name" name="name" value="Mock Google User" required class="w-full h-10 px-3 border border-gray-300 rounded-lg text-sm text-gray-900 focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Email Address</label>
                <input type="email" id="mock_email" name="email" value="mockuser.google@gmail.com" required class="w-full h-10 px-3 border border-gray-300 rounded-lg text-sm text-gray-900 focus:outline-none focus:border-blue-500">
            </div>

            <input type="hidden" id="mock_google_id" name="google_id" value="g_1234567890">

            <button type="submit" class="w-full h-11 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-sm tracking-wide transition shadow-sm mt-2">
                Authorize Google Account &rarr;
            </button>
        </form>
    </div>
</div>

<script>
function selectProfile(email, name, id) {
    document.getElementById('mock_email').value = email;
    document.getElementById('mock_name').value = name;
    document.getElementById('mock_google_id').value = id;
    document.querySelector('form').submit();
}
</script>