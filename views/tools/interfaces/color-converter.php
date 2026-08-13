<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div><label class="block text-xs font-bold uppercase mb-1">Pick Color</label><input type="color" id="color-picker" value="#FF5A36" oninput="fromPicker(this.value)" class="w-full h-12 rounded-lg p-1 border"></div>
            <div><label class="block text-xs font-bold uppercase mb-1">HEX Code</label><input type="text" id="color-hex" value="#FF5A36" oninput="fromHex(this.value)" class="input-custom w-full h-10 px-3 text-sm code-font"></div>
            <div><label class="block text-xs font-bold uppercase mb-1">RGB Format</label><input type="text" id="color-rgb" value="rgb(255, 90, 54)" class="input-custom w-full h-10 px-3 text-sm code-font"></div>
        </div>
        <div class="flex flex-col items-center justify-center bg-[var(--color-surface-alt)] rounded-2xl p-6">
            <span class="text-xs font-bold uppercase mb-3">Preview</span>
            <div id="color-preview-box" class="w-32 h-32 rounded-[20px] shadow-md border-4 border-white" style="background-color: #FF5A36;"></div>
        </div>
    </div>
</div>
<script>
    function updateColors(hex, rgb) {
        document.getElementById('color-preview-box').style.backgroundColor = hex;
        document.getElementById('color-picker').value = hex;
        document.getElementById('color-hex').value = hex;
        document.getElementById('color-rgb').value = rgb;
    }
    function fromPicker(val) { const rgb = hexToRgb(val); updateColors(val, `rgb(${rgb.r}, ${rgb.g}, ${rgb.b})`); }
    function fromHex(val) { if (/^#[0-9A-F]{6}$/i.test(val)) { const rgb = hexToRgb(val); updateColors(val, `rgb(${rgb.r}, ${rgb.g}, ${rgb.b})`); } }
    function hexToRgb(hex) {
        const result = /^#?([a-f\\d]{2})([a-f\\d]{2})([a-f\\d]{2})$/i.exec(hex);
        return result ? { r: parseInt(result[1], 16), g: parseInt(result[2], 16), b: parseInt(result[3], 16) } : null;
    }
</script>