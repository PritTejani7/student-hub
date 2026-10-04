
let events = [];
let students = [];
let faqs = [];

let filteredEvents = [];
let filteredStudents = [];
let filteredFaqs = [];

let eventPage = 1;
let studentPage = 1;
let faqPage = 1;

const recordsPerPage = 5;

function showSection(section) {
    document.getElementById("eventsSection").classList.add("hidden");
    document.getElementById("studentsSection").classList.add("hidden");
    document.getElementById("faqsSection").classList.add("hidden");

    document.getElementById(section + "Section").classList.remove("hidden");

    document.querySelectorAll(".menu-btn").forEach(button => {
        button.classList.remove("active");
    });

    if (section === "events") {
        document.querySelectorAll(".menu-btn")[0].classList.add("active");
    } else if (section === "students") {
        document.querySelectorAll(".menu-btn")[1].classList.add("active");
    } else {
        document.querySelectorAll(".menu-btn")[2].classList.add("active");
    }
}

async function loadEvents() {
    try {
        const response = await fetch("./events.json");

        if (!response.ok) {
            throw new Error("events.json not found");
        }

        events = await response.json();
        filteredEvents = [...events];

        createEventFilters();
        applyEventFilters();

    } catch (error) {
        console.error(error);
        document.getElementById("eventStatus").textContent =
            "Error loading events. Use Live Server.";
    }
}

function createEventFilters() {
    const types = [...new Set(events.map(event => event.type))];

    types.forEach(type => {
        const option = document.createElement("option");
        option.value = type;
        option.textContent = type;
        document.getElementById("eventFilter").appendChild(option);
    });
}

function applyEventFilters() {
    const search = document.getElementById("eventSearch").value.toLowerCase();
    const type = document.getElementById("eventFilter").value;
    const sort = document.getElementById("eventSort").value;

    filteredEvents = events.filter(event => {
        const matchSearch =
            event.name.toLowerCase().includes(search) ||
            event.venue.toLowerCase().includes(search) ||
            event.description.toLowerCase().includes(search);

        const matchType = type === "all" || event.type === type;

        return matchSearch && matchType;
    });

    if (sort === "nameAsc")
        filteredEvents.sort((a, b) => a.name.localeCompare(b.name));

    if (sort === "nameDesc")
        filteredEvents.sort((a, b) => b.name.localeCompare(a.name));

    if (sort === "dateAsc")
        filteredEvents.sort((a, b) => new Date(a.date) - new Date(b.date));

    if (sort === "dateDesc")
        filteredEvents.sort((a, b) => new Date(b.date) - new Date(a.date));

    eventPage = 1;
    renderEvents();
}

function renderEvents() {
    const container = document.getElementById("eventContainer");
    container.innerHTML = "";

    if (filteredEvents.length === 0) {
        document.getElementById("eventStatus").textContent = "No events found.";
        document.getElementById("eventPagination").innerHTML = "";
        return;
    }

    const totalPages = Math.ceil(filteredEvents.length / recordsPerPage);
    const start = (eventPage - 1) * recordsPerPage;
    const pageData = filteredEvents.slice(start, start + recordsPerPage);

    document.getElementById("eventStatus").textContent =
        `${filteredEvents.length} event(s) found`;

    pageData.forEach(event => {
        container.innerHTML += `
            <div class="card">
                <span class="badge">${event.type}</span>
                <h3>${event.name}</h3>
                <p><strong>Date:</strong> ${formatDate(event.date)}</p>
                <p><strong>Time:</strong> ${event.time || "10:00 AM"}</p>
                <p><strong>Venue:</strong> ${event.venue}</p>
                <p>${event.description}</p>
            </div>
        `;
    });

    createPagination("eventPagination", totalPages, eventPage, page => {
        eventPage = page;
        renderEvents();
    });
}

async function loadStudents() {
    try {
        const response = await fetch("./students.json");

        if (!response.ok) {
            throw new Error("students.json not found");
        }

        students = await response.json();
        filteredStudents = [...students];

        createStudentCourses();
        applyStudentFilters();

    } catch (error) {
        console.error(error);
        document.getElementById("studentStatus").textContent =
            "Error loading students. Check students.json.";
    }
}

function createStudentCourses() {
    const courses = [...new Set(students.map(student => student.course))];

    courses.forEach(course => {
        const option = document.createElement("option");
        option.value = course;
        option.textContent = course;
        document.getElementById("studentCourse").appendChild(option);
    });
}

function applyStudentFilters() {
    const search = document.getElementById("studentSearch").value.toLowerCase();
    const course = document.getElementById("studentCourse").value;
    const sort = document.getElementById("studentSort").value;

    filteredStudents = students.filter(student => {
        const matchSearch =
            student.name.toLowerCase().includes(search) ||
            student.email.toLowerCase().includes(search) ||
            student.city.toLowerCase().includes(search);

        const matchCourse =
            course === "all" || student.course === course;

        return matchSearch && matchCourse;
    });

    if (sort === "nameAsc")
        filteredStudents.sort((a, b) => a.name.localeCompare(b.name));

    if (sort === "nameDesc")
        filteredStudents.sort((a, b) => b.name.localeCompare(a.name));

    studentPage = 1;
    renderStudents();
}

function renderStudents() {
    const container = document.getElementById("studentContainer");
    container.innerHTML = "";

    if (filteredStudents.length === 0) {
        document.getElementById("studentStatus").textContent = "No students found.";
        return;
    }

    const totalPages = Math.ceil(filteredStudents.length / recordsPerPage);
    const start = (studentPage - 1) * recordsPerPage;
    const pageData = filteredStudents.slice(start, start + recordsPerPage);

    document.getElementById("studentStatus").textContent =
        `${filteredStudents.length} student(s) found`;

    pageData.forEach(student => {
        container.innerHTML += `
            <div class="card">
                <span class="badge">${student.course}</span>
                <h3>${student.name}</h3>
                <p><strong>Email:</strong> ${student.email}</p>
                <p><strong>City:</strong> ${student.city}</p>
            </div>
        `;
    });

    createPagination("studentPagination", totalPages, studentPage, page => {
        studentPage = page;
        renderStudents();
    });
}

async function loadFaqs() {
    try {
        const response = await fetch("./faqs.json");

        if (!response.ok) {
            throw new Error("faqs.json not found");
        }

        faqs = await response.json();
        filteredFaqs = [...faqs];

        applyFaqSearch();

    } catch (error) {
        console.error(error);
        document.getElementById("faqStatus").textContent =
            "Error loading FAQs. Check faqs.json.";
    }
}

function applyFaqSearch() {
    const search = document.getElementById("faqSearch").value.toLowerCase();

    filteredFaqs = faqs.filter(faq =>
        faq.question.toLowerCase().includes(search) ||
        faq.answer.toLowerCase().includes(search)
    );

    faqPage = 1;
    renderFaqs();
}

function renderFaqs() {
    const container = document.getElementById("faqContainer");
    container.innerHTML = "";

    if (filteredFaqs.length === 0) {
        document.getElementById("faqStatus").textContent = "No FAQs found.";
        return;
    }

    const totalPages = Math.ceil(filteredFaqs.length / recordsPerPage);
    const start = (faqPage - 1) * recordsPerPage;
    const pageData = filteredFaqs.slice(start, start + recordsPerPage);

    document.getElementById("faqStatus").textContent =
        `${filteredFaqs.length} FAQ(s) found`;

    pageData.forEach(faq => {
        container.innerHTML += `
            <div class="faq">
                <div class="faq-question" onclick="toggleFaq(this)">
                    ${faq.question} ▼
                </div>
                <div class="faq-answer">
                    ${faq.answer}
                </div>
            </div>
        `;
    });

    createPagination("faqPagination", totalPages, faqPage, page => {
        faqPage = page;
        renderFaqs();
    });
}

function toggleFaq(questionElement) {
    const answer = questionElement.nextElementSibling;
    answer.classList.toggle("show");
}

function createPagination(elementId, totalPages, currentPage, changePage) {
    const container = document.getElementById(elementId);
    container.innerHTML = "";

    if (totalPages <= 1) return;

    const previous = document.createElement("button");
    previous.textContent = "Previous";
    previous.disabled = currentPage === 1;
    previous.onclick = () => changePage(currentPage - 1);
    container.appendChild(previous);

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement("button");
        button.textContent = i;

        if (i === currentPage) {
            button.classList.add("active");
        }

        button.onclick = () => changePage(i);
        container.appendChild(button);
    }

    const next = document.createElement("button");
    next.textContent = "Next";
    next.disabled = currentPage === totalPages;
    next.onclick = () => changePage(currentPage + 1);
    container.appendChild(next);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString("en-IN", {
        day: "2-digit",
        month: "long",
        year: "numeric"
    });
}

document.getElementById("eventSearch").addEventListener("input", applyEventFilters);
document.getElementById("eventFilter").addEventListener("change", applyEventFilters);
document.getElementById("eventSort").addEventListener("change", applyEventFilters);

document.getElementById("studentSearch").addEventListener("input", applyStudentFilters);
document.getElementById("studentCourse").addEventListener("change", applyStudentFilters);
document.getElementById("studentSort").addEventListener("change", applyStudentFilters);

document.getElementById("faqSearch").addEventListener("input", applyFaqSearch);

loadEvents();
loadStudents();
loadFaqs();
