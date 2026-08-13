<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
<div class="space-y-6">
    <div class="p-4 bg-[var(--color-surface-alt)] border-l-4 border-[var(--color-teal-500)] rounded-r-xl text-xs text-[var(--color-ink-600)]">
        <strong class="text-[var(--color-teal-500)] block uppercase font-bold text-sm mb-1">🔐 ENCODING VS. ENCRYPTION NOTICE:</strong>
        <p>Encoding transforms text representations, whereas Encryption requires a secret passcode and utilizes robust AES cryptography to keep your content absolutely secure.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase text-[var(--color-ink-400)] mb-1">Raw Content</label>
                <textarea id="note-raw" placeholder="Write or paste your note or encrypted cipher block here..." class="input-custom w-full h-44 p-4 text-sm"></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-[var(--color-ink-600)] uppercase mb-1">Passcode (Optional)</label>
                <input type="password" id="note-passcode" placeholder="Leave empty for standard encoding formats" class="input-custom w-full h-10 px-3 text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-[var(--color-ink-600)] uppercase mb-1">Transform Type</label>
                <select id="note-type" onchange="togglePasscodeState()" class="input-custom w-full h-10 px-3 text-sm">
                    <option value="aes">🔒 True AES-256 Authenticated Encryption</option>
                    <option value="base64">🔗 Base64 Encoder / Decoder</option>
                    <option value="hex">🔢 Hexadecimal Conversion</option>
                    <option value="binary">💻 Binary 0s & 1s Formatting</option>
                    <option value="rot13">🧩 ROT13 Caesar Cipher</option>
                    <option value="morse">📡 Morse Code Transmitter</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button onclick="runNoteProcess('encrypt')" class="btn-ember px-4 py-2 text-xs font-bold rounded-xl flex-grow">🔒 Encrypt / Encode</button>
                <button onclick="runNoteProcess('decrypt')" class="btn-secondary px-4 py-2 text-xs font-bold rounded-xl flex-grow">🔓 Decrypt / Decode</button>
            </div>
        </div>
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase text-[var(--color-ink-400)] mb-1">Output</label>
                <textarea id="note-output" readonly class="input-custom w-full h-44 p-4 text-sm bg-[var(--color-surface-alt)] code-font"></textarea>
            </div>
            <div class="flex gap-2">
                <button onclick="copyToClipboard('note-output')" class="btn-ember px-4 py-2 text-xs font-bold rounded-xl flex items-center gap-1.5">Copy Output</button>
                <button onclick="clearNoteFields()" class="btn-secondary px-4 py-2 text-xs font-bold rounded-xl flex items-center gap-1.5">Clear Fields</button>
            </div>
        </div>
    </div>
</div>
<script>
    function togglePasscodeState() {
        const type = document.getElementById('note-type').value;
        const pcode = document.getElementById('note-passcode');
        if (type !== 'aes') {
            pcode.disabled = true; pcode.placeholder = "Not required for encoding"; pcode.classList.add('opacity-50');
        } else {
            pcode.disabled = false; pcode.placeholder = "Enter passcode..."; pcode.classList.remove('opacity-50');
        }
    }
    function clearNoteFields() {
        document.getElementById('note-raw').value = '';
        document.getElementById('note-output').value = '';
        document.getElementById('note-passcode').value = '';
        showToast('Fields cleared.');
    }
    function runNoteProcess(action) {
        const input = document.getElementById('note-raw').value;
        const pcode = document.getElementById('note-passcode').value;
        const type = document.getElementById('note-type').value;
        if (input.trim() === '') { showToast('Input cannot be empty!', false); return; }
        let result = '';
        if (type === 'aes') {
            if (pcode.trim() === '') { showToast('A passcode is required!', false); return; }
            try {
                if (action === 'encrypt') {
                    result = CryptoJS.AES.encrypt(input, pcode).toString(); showToast('Encrypted!');
                } else {
                    const bytes = CryptoJS.AES.decrypt(input, pcode);
                    result = bytes.toString(CryptoJS.enc.Utf8);
                    if (result === '') throw new Error('Wrong passcode');
                    showToast('Decrypted!');
                }
            } catch (e) { showToast('Decryption failed!', false); return; }
        } else {
            try {
                if (action === 'encrypt') {
                    if (type === 'base64') result = btoa(unescape(encodeURIComponent(input)));
                    else if (type === 'hex') result = stringToHex(input);
                    else if (type === 'binary') result = stringToBinary(input);
                    else if (type === 'rot13') result = rot13(input);
                    else if (type === 'morse') result = stringToMorse(input);
                    showToast('Encoded.');
                } else {
                    if (type === 'base64') result = decodeURIComponent(escape(atob(input)));
                    else if (type === 'hex') result = hexToString(input);
                    else if (type === 'binary') result = binaryToString(input);
                    else if (type === 'rot13') result = rot13(input);
                    else if (type === 'morse') result = morseToString(input);
                    showToast('Decoded.');
                }
            } catch (e) { showToast('Failed!', false); return; }
        }
        document.getElementById('note-output').value = result;
    }
    function stringToHex(str) {
        let hex = ''; for (let i = 0; i < str.length; i++) { hex += str.charCodeAt(i).toString(16).padStart(2, '0'); } return hex;
    }
    function hexToString(hex) {
        let str = ''; for (let i = 0; i < hex.length; i += 2) { str += String.fromCharCode(parseInt(hex.substr(i, 2), 16)); } return str;
    }
    function stringToBinary(str) {
        return str.split('').map(c => c.charCodeAt(0).toString(2).padStart(8, '0')).join(' ');
    }
    function binaryToString(bin) {
        return bin.split(/\\s+/).map(b => String.fromCharCode(parseInt(b, 2))).join('');
    }
    function rot13(str) {
        return str.replace(/[a-zA-Z]/g, function(c) { return String.fromCharCode((c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26); });
    }
    const morseMap = {
        'A': '.-', 'B': '-...', 'C': '-.-.', 'D': '-..', 'E': '.', 'F': '..-.', 'G': '--.', 'H': '....',
        'I': '..', 'J': '.---', 'K': '-.-', 'L': '.-..', 'M': '--', 'N': '-.', 'O': '---', 'P': '.--.',
        'Q': '--.-', 'R': '.-.', 'S': '...', 'T': '-', 'U': '..-', 'V': '...-', 'W': '.--', 'X': '-..-',
        'Y': '-.--', 'Z': '--..', '1': '.----', '2': '..---', '3': '...--', '4': '....-', '5': '.....',
        '6': '-....', '7': '--...', '8': '---..', '9': '----.', '0': '-----', ' ': '/'
    };
    function stringToMorse(str) { return str.toUpperCase().split('').map(c => morseMap[c] || '').filter(Boolean).join(' '); }
    function morseToString(morse) {
        const revMap = Object.fromEntries(Object.entries(morseMap).map(([k, v]) => [v, k]));
        return morse.split(/\\s+/).map(m => revMap[m] || '?').join('');
    }
</script>