# Adding a New Tool Guide (Developer Specification)

To extend the Utilazy toolbox, follow these database-driven plugin steps:

## Step 1: Register Tool in database
Insert a record into the `tools` table via phpMyAdmin or Admin Registry panel:
```sql
INSERT INTO tools (slug, name, description, category_id, icon, access_type, token_cost)
VALUES ('my-new-tool', 'My Custom Tool', 'Perform special conversion rules.', 1, '🎨', 'free', 0);
```
*(This automatically adds the tool to the Live Navigation Dropdown and search filters directory!)*

## Step 2: Create Visual Interface View
Create a file at `views/tools/interfaces/my-new-tool.php` containing your tool's HTML form inputs, outputs, and JavaScript processing rules:
```html
<div class="space-y-4">
    <textarea id="my-new-input" placeholder="Type text..." class="input-custom w-full h-32 p-3 text-sm"></textarea>
    <button onclick="runProcess()" class="btn-ember px-4 py-2 text-xs font-bold rounded-xl shadow-sm">Convert Now</button>
</div>
<script>
    function runProcess() {
        const input = document.getElementById('my-new-input').value;
        showToast('Converted successfully!');
    }
</script>
```

## Step 3: Test and Audit
Verify keyboard focus states, contrast ratios, and test with empty/large inputs. Your tool is fully integrated!
