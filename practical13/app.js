const apiUrl = 'api.php';
const form = document.getElementById('eventForm');
const rows = document.getElementById('eventRows');
const message = document.getElementById('message');
const search = document.getElementById('search');
let events = [];

function showMessage(text, error = false) {
    message.textContent = text;
    message.className = error ? 'error' : 'message';
}
async function request(url, options = {}) {
    const response = await fetch(url, options);
    const data = await response.json();
    if (!response.ok || !data.ok) throw new Error(data.message || 'Request failed.');
    return data;
}
async function loadEvents() {
    try {
        const data = await request(apiUrl);
        events = data.events;
        renderEvents();
        showMessage('Events loaded.');
    } catch (error) {
        showMessage(error.message, true);
    }
}
function renderEvents() {
    const term = search.value.toLowerCase().trim();
    const filtered = events.filter(event =>
        event.title.toLowerCase().includes(term) ||
        event.category.toLowerCase().includes(term)
    );
    rows.replaceChildren();
    filtered.forEach(event => {
        const tr = document.createElement('tr');
        [event.title, event.event_date, event.category, event.status].forEach(value => {
            const td = document.createElement('td');
            td.textContent = value;
            tr.appendChild(td);
        });
        const actions = document.createElement('td');
        const edit = document.createElement('button');
        edit.type = 'button';
        edit.textContent = 'Edit';
        edit.addEventListener('click', () => startEdit(event));
        const remove = document.createElement('button');
        remove.type = 'button';
        remove.className = 'danger';
        remove.textContent = 'Delete';
        remove.addEventListener('click', () => deleteEvent(event.id));
        actions.append(edit, document.createTextNode(' '), remove);
        tr.appendChild(actions);
        rows.appendChild(tr);
    });
    if (!filtered.length) {
        const tr = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = 5;
        td.textContent = 'No events found.';
        tr.appendChild(td);
        rows.appendChild(tr);
    }
}
function startEdit(event) {
    document.getElementById('eventId').value = event.id;
    document.getElementById('title').value = event.title;
    document.getElementById('eventDate').value = event.event_date;
    document.getElementById('category').value = event.category;
    document.getElementById('saveButton').textContent = 'Update Event';
    document.getElementById('cancelButton').hidden = false;
}
function resetForm() {
    form.reset();
    document.getElementById('eventId').value = '';
    document.getElementById('category').value = 'General';
    document.getElementById('saveButton').textContent = 'Add Event';
    document.getElementById('cancelButton').hidden = true;
}
form.addEventListener('submit', async event => {
    event.preventDefault();
    const id = document.getElementById('eventId').value;
    const payload = {
        action: id ? 'update' : 'add',
        id: id ? Number(id) : undefined,
        title: document.getElementById('title').value.trim(),
        event_date: document.getElementById('eventDate').value,
        category: document.getElementById('category').value.trim() || 'General'
    };
    try {
        const data = await request(apiUrl, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
        });
        showMessage(data.message);
        resetForm();
        await loadEvents();
    } catch (error) {
        showMessage(error.message, true);
    }
});
async function deleteEvent(id) {
    if (!confirm('Delete this event?')) return;
    try {
        const data = await request(apiUrl, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({action: 'delete', id})
        });
        showMessage(data.message);
        await loadEvents();
    } catch (error) {
        showMessage(error.message, true);
    }
}
search.addEventListener('input', renderEvents);
document.getElementById('cancelButton').addEventListener('click', resetForm);
loadEvents();
