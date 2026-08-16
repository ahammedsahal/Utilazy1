<div class="space-y-6" id="app-xml-formatter">
    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-4">
        <h3 class="text-base font-bold text-[var(--color-ink-900)] flex items-center gap-2">
            <span>🛠️</span> Xml Formatter Studio
        </h3>
        <p class="text-xs text-[var(--color-ink-600)]">Configure parameters or input payload to process client-side instantly.</p>

        <textarea id="input-xml-formatter" class="w-full h-36 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs font-mono focus:outline-none focus:border-[var(--color-ember-500)]" placeholder="Paste or type content here..."></textarea>

        <div class="flex flex-wrap items-center gap-3">
            <button id="btn-process-xml-formatter" class="btn-ember px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm">Process Xml Formatter →</button>
            <button id="btn-clear-xml-formatter" class="px-4 py-2.5 bg-[var(--color-surface)] border border-[var(--color-border)] text-[var(--color-ink-600)] rounded-xl text-xs font-semibold hover:bg-[var(--color-border)]">Clear</button>
        </div>
    </div>

    <div class="bg-[var(--color-surface-alt)] p-6 rounded-2xl border border-[var(--color-border)] space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-[var(--color-ink-600)] uppercase tracking-wider">Processed Output</span>
            <button id="btn-copy-xml-formatter" class="text-xs font-bold text-[var(--color-ember-500)] hover:underline">⧉ Copy Result</button>
        </div>
        <textarea id="output-xml-formatter" readonly class="w-full h-36 bg-[var(--color-surface)] border border-[var(--color-border)] rounded-xl p-3 text-xs font-mono text-[var(--color-teal-500)] focus:outline-none" placeholder="Results will appear here..."></textarea>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("input-xml-formatter");
    const output = document.getElementById("output-xml-formatter");
    const processBtn = document.getElementById("btn-process-xml-formatter");
    const clearBtn = document.getElementById("btn-clear-xml-formatter");
    const copyBtn = document.getElementById("btn-copy-xml-formatter");

    if (processBtn) {
        processBtn.addEventListener("click", () => {
            const val = input.value.trim();
            if (!val) {
                output.value = "Please enter valid XML markup to format.";
                return;
            }
            try {
                const parser = new DOMParser();
                const xmlDoc = parser.parseFromString(val, "text/xml");
                const errorNode = xmlDoc.querySelector("parsererror");
                if (errorNode) {
                    output.value = "XML Parsing Error: " + errorNode.textContent;
                    return;
                }
                function formatXml(xml) {
                    let formatted = '';
                    const reg = /(>)(<)(\/*)/g;
                    xml = xml.replace(reg, '$1\r\n$2$3');
                    let pad = 0;
                    xml.split('\r\n').forEach(node => {
                        let indent = 0;
                        if (node.match(/.+<\/\w[^>]*>$/)) {
                            indent = 0;
                        } else if (node.match(/^<\/\w/)) {
                            if (pad !== 0) pad -= 1;
                        } else if (node.match(/^<\w[^>]*[^\/]>.*$/)) {
                            indent = 1;
                        } else {
                            indent = 0;
                        }
                        let padding = '';
                        for (let i = 0; i < pad; i++) padding += '  ';
                        formatted += padding + node + '\r\n';
                        pad += indent;
                    });
                    return formatted.trim();
                }
                output.value = formatXml(val);
            } catch (err) {
                output.value = "Error processing XML: " + err.message;
            }
        });
    }

    if (clearBtn) {
        clearBtn.addEventListener("click", () => {
            input.value = "";
            output.value = "";
        });
    }

    if (copyBtn) {
        copyBtn.addEventListener("click", () => {
            navigator.clipboard.writeText(output.value);
            alert("Copied to clipboard!");
        });
    }
});
</script>