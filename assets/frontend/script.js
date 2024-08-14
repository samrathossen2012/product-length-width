
function addRow() {
    var container = document.getElementById('measurements-container');
    var row = document.createElement('div');
    row.className = 'measurement-row';
    row.innerHTML = `
        <input type="text" name="length[]" placeholder="Length">
        <input type="text" name="width[]" placeholder="Width">
        <button type="button" class="remove-row" onclick="removeRow(this)">❌</button>
    `;
    container.appendChild(row);
}

function removeRow(button) {
    var row = button.parentNode;
    row.parentNode.removeChild(row);
}
