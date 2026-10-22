<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Practical 13 - Fetch CRUD</title><link rel="stylesheet" href="../style.css"></head><body><main class="container">
<nav><a href="../practical11/index.php">Practical 11</a><a href="../practical12/index.php">Practical 12</a><a href="../practical13/index.php">Practical 13</a><a href="../practical14/index.php">Practical 14</a></nav>
<h1>Practical 13: PHP JSON API + Fetch CRUD</h1><p class="muted">Add, list, edit and delete events without reloading the page.</p><p id="message" class="message" aria-live="polite">Loading events...</p>
<form id="eventForm"><input type="hidden" id="eventId"><label>Event title<input id="title" required></label><label>Event date<input id="eventDate" type="date" required></label><label>Category<input id="category" value="General"></label><button id="saveButton">Add Event</button><button type="button" id="cancelButton" hidden>Cancel edit</button></form>
<h2>Event list</h2><label>Search<input id="search" placeholder="Search title or category"></label><table><thead><tr><th>Title</th><th>Date</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead><tbody id="eventRows"></tbody></table>
</main><script src="app.js"></script></body></html>
