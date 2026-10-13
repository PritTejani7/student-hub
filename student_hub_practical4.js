
document.addEventListener("DOMContentLoaded", function () {

    var body = document.body;
    var pageTitle = document.title.toLowerCase();

    var style = document.createElement("style");

    style.innerHTML = `
        .js-topbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
            box-sizing: border-box;
            padding: 10px 15px;
            background: #2563eb;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: Arial, sans-serif;
        }

        .js-topbar button {
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            background: white;
            color: #2563eb;
            font-weight: bold;
        }

        .js-topbar button:hover {
            opacity: 0.8;
        }

        .js-notification {
            margin: 10px auto;
            width: 90%;
            padding: 12px;
            box-sizing: border-box;
            background: #e0f2fe;
            color: #075985;
            border: 1px solid #7dd3fc;
            border-radius: 8px;
            position: relative;
        }

        .js-notification-close {
            position: absolute;
            right: 10px;
            top: 6px;
            border: none;
            background: transparent;
            font-size: 20px;
            cursor: pointer;
        }

        /* DARK MODE */

        body.js-dark {
            background: #111827 !important;
            color: white !important;
        }

        body.js-dark table,
        body.js-dark td,
        body.js-dark th {
            background: #1f2937 !important;
            color: white !important;
            border-color: #64748b !important;
        }

        body.js-dark input,
        body.js-dark select {
            background: #111827 !important;
            color: white !important;
            border-color: #64748b !important;
        }

        body.js-dark a {
            color: #93c5fd !important;
        }

        /* MENU */

        .js-hide-menu {
            display: none !important;
        }

        /* MODAL */

        .js-modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            align-items: center;
            justify-content: center;
        }

        .js-modal-box {
            background: white;
            color: #111827;
            width: 400px;
            max-width: 90%;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            font-family: Arial, sans-serif;
        }

        .js-modal-box button {
            padding: 8px 18px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        /* FAQ */

        .js-faq {
            width: 90%;
            max-width: 850px;
            margin: 25px auto;
            font-family: Arial, sans-serif;
        }

        .js-faq-question {
            width: 100%;
            padding: 14px;
            margin-top: 8px;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            text-align: left;
            cursor: pointer;
            font-weight: bold;
        }

        .js-faq-answer {
            display: none;
            padding: 12px;
            background: white;
            border: 1px solid #cbd5e1;
        }

        .js-faq-answer.show {
            display: block;
        }

        /* SLIDER */

        .js-slider {
            width: 90%;
            max-width: 850px;
            margin: 25px auto;
            padding: 20px;
            text-align: center;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
        }

        .js-slide {
            display: none;
            min-height: 100px;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .js-slide.active {
            display: flex;
        }

        .js-slider button {
            padding: 8px 15px;
            margin: 5px;
            border: none;
            border-radius: 6px;
            background: #2563eb;
            color: white;
            cursor: pointer;
        }

        /* HOVER ANIMATION */

        .js-card {
            transition: 0.3s;
        }

        .js-card:hover {
            transform: translateY(-3px);
        }
    `;
    document.head.appendChild(style);

    var modal = document.createElement("div");

    modal.className = "js-modal";
    modal.innerHTML = `
        <div class="js-modal-box">
            <h2 id="modalTitle">Student Hub</h2>

            <p id="modalMessage"></p>

            <button id="modalClose">
                Close
            </button>
        </div>
    `;

    body.appendChild(modal);
    function showModal(title, message) {

        document.getElementById("modalTitle").textContent = title;
        document.getElementById("modalMessage").textContent = message;

        modal.style.display = "flex";
    }

    function closeModal() {
        modal.style.display = "none";
    }

    document
        .getElementById("modalClose")
        .addEventListener("click", closeModal);
    modal.addEventListener("click", function (event) {

        if (event.target === modal) {

            closeModal();
        }
    });

    var topBar = document.createElement("div");
    topBar.className = "js-topbar";

    var menuButton = document.createElement("button");
    menuButton.textContent = "☰ Menu";

    var title = document.createElement("strong");
    title.textContent = "STUDENT HUB";

    var themeButton = document.createElement("button");

    topBar.appendChild(menuButton);
    topBar.appendChild(title);
    topBar.appendChild(themeButton);
    body.insertBefore(topBar, body.firstChild);

    var savedTheme =
        localStorage.getItem("studentHubTheme");

    if (savedTheme === "dark") {

        body.classList.add("js-dark");
        themeButton.textContent = "☀️ Light Mode";

    } else {
        themeButton.textContent = "🌙 Dark Mode";
    }

    themeButton.addEventListener("click", function () {

        body.classList.toggle("js-dark");
        if (body.classList.contains("js-dark")) {

            localStorage.setItem(
                "studentHubTheme",
                "dark"
            );

            themeButton.textContent =
                "☀️ Light Mode";
        } else {

            localStorage.setItem(
                "studentHubTheme",
                "light"
            );

            themeButton.textContent =
                "🌙 Dark Mode";
        }
    });

    var notification =
        document.createElement("div");

    notification.className =
        "js-notification";

    notification.innerHTML = `
        <b>📢 Student Hub Notification:</b>
        Welcome to Student Hub!

        <button class="js-notification-close">
        </button>
    `;

    topBar.after(notification);
    notification
        .querySelector(".js-notification-close")
        .addEventListener("click", function () {

            notification.remove();

        });

    var mainTable =
        body.querySelector("table");
    var sidebar = null;


    if (mainTable) {

        var firstRow =
            mainTable.querySelector("tr");


        if (firstRow) {

            sidebar =
                firstRow.children[0];
        }
    }

    menuButton.addEventListener("click", function () {

        if (sidebar) {

            sidebar.classList.toggle(
                "js-hide-menu"
            );

        } else {

            showModal(
                "Menu",
                "No side menu was found on this page."
            );
        }

    });

    var tables =
        document.querySelectorAll("table");

    tables.forEach(function (table) {
        table.classList.add("js-card");
    });

    if (pageTitle.includes("home")) {

        var slider =
            document.createElement("div");

        slider.className =
            "js-slider";

        slider.innerHTML = `

            <h2>Student Hub Highlights</h2>
            <div class="js-slide active">

                <h3>📚 Courses</h3>

                <p>
                    View your courses and faculty information.
                </p>

            </div>


            <div class="js-slide">

                <h3>📊 Attendance</h3>

                <p>
                    Check your attendance percentage.
                </p>

            </div>


            <div class="js-slide">

                <h3>📝 Assignments</h3>

                <p>
                    Check assignment details and deadlines.
                </p>

            </div>


            <button id="previousSlide">
                Previous
            </button>

            <button id="nextSlide">
                Next
            </button>
        `;


        notification.after(slider);


        var slides =
            slider.querySelectorAll(".js-slide");


        var currentSlide = 0;


        function displaySlide(index) {

            slides.forEach(function (slide) {

                slide.classList.remove("active");

            });


            slides[index]
                .classList.add("active");
        }


        document
            .getElementById("nextSlide")
            .addEventListener("click", function () {

                currentSlide++;


                if (currentSlide >= slides.length) {

                    currentSlide = 0;
                }


                displaySlide(currentSlide);

            });


        document
            .getElementById("previousSlide")
            .addEventListener("click", function () {

                currentSlide--;


                if (currentSlide < 0) {

                    currentSlide =
                        slides.length - 1;
                }


                displaySlide(currentSlide);

            });
    }

    if (pageTitle.includes("home")) {
        var faq =
            document.createElement("div");

        faq.className = "js-faq";
        faq.innerHTML = `

            <h2>
                Frequently Asked Questions
            </h2>

            <button class="js-faq-question">
                What is Student Hub?
            </button>

            <div class="js-faq-answer">

                Student Hub is a student portal for
                courses, attendance, assignments,
                results and timetable.

            </div>

            <button class="js-faq-question">
                How can I use Dark Mode?
            </button>

            <div class="js-faq-answer">

                Click the Dark Mode button at the
                top of the page.

            </div>

            <button class="js-faq-question">
                What is JavaScript DOM?
            </button>

            <div class="js-faq-answer">

                DOM allows JavaScript to select,
                change, add and remove HTML elements.

            </div>

            <button class="js-faq-question">
                What is localStorage?
            </button>

            <div class="js-faq-answer">

                localStorage stores small data in
                the browser for future use.

            </div>
        `;

        slider.after(faq);

        var questions =
            faq.querySelectorAll(
                ".js-faq-question"
            );

        questions.forEach(function (question) {
            question.addEventListener(
                "click",
                function () {
                    var answer =
                        question.nextElementSibling;
                    answer.classList.toggle(
                        "show"
                    );
                }
            );
        });
    }

    if (pageTitle.includes("profile")) {

        var editButton =
            document.querySelector(
                "input[value='Edit']"
            );

        if (editButton) {

            editButton.addEventListener(
                "click",
                function () {

                    var inputs =
                        document.querySelectorAll(
                            "input[type='text']"
                        );

                    inputs.forEach(
                        function (input) {

                            input.disabled = false;

                            input.style.border =
                                "2px solid #2563eb";
                        }
                    );
                    showModal(
                        "Profile",
                        "Profile fields are now editable."
                    );

                }
            );
        }
    }

    if (pageTitle.includes("settings")) {

        var buttons =
            document.querySelectorAll("button");


        buttons.forEach(function (button) {

            var text =
                button.textContent
                    .trim()
                    .toLowerCase();

            if (text === "on" || text === "off") {

                button.addEventListener(
                    "click",
                    function () {
                        if (
                            button.textContent
                                .trim()
                                .toLowerCase()
                                === "on"
                        ) {

                            button.textContent =
                                "OFF";

                        } else {

                            button.textContent =
                                "ON";
                        }

                    }
                );
            }

            if (text === "change") {

                button.addEventListener(
                    "click",
                    function () {

                        showModal(
                            "Change Password",
                            "Change password option selected."
                        );

                    }
                );
            }

            if (text === "log out") {

                button.addEventListener(
                    "click",
                    function () {

                        var answer =
                            confirm(
                                "Are you sure you want to log out?"
                            );


                        if (answer) {

                            window.location.href =
                                "index.html";
                        }

                    }
                );
            }

        });

        var language =
            document.querySelector("select");


        if (language) {

            language.addEventListener(
                "change",
                function () {

                    showModal(
                        "Language",
                        "Selected language: " +
                        language.value
                    );

                }
            );
        }

    }

if (pageTitle.includes("login")) {

    var inputs = document.querySelectorAll("input");

    var emailInput = null;
    var passwordInput = null;

    inputs.forEach(function (input) {

        if (input.type === "password") {

            passwordInput = input;

        } else if (
            input.type === "email" ||
            input.placeholder.toLowerCase().includes("email")
        ) {

            emailInput = input;

        }

    });


    var signIn = document.querySelector(
        "a[href='dashboard.html']"
    );


    if (signIn) {

        signIn.addEventListener(
            "click",
            function (event) {

                if (
                    emailInput === null ||
                    emailInput.value.trim() === ""
                ) {

                    event.preventDefault();

                    showModal(
                        "Login Error",
                        "Please enter your Email."
                    );

                    return;
                }


                if (
                    passwordInput === null ||
                    passwordInput.value.trim() === ""
                ) {

                    event.preventDefault();

                    showModal(
                        "Login Error",
                        "Please enter your Password."
                    );

                    return;
                }


                localStorage.setItem(
                    "studentHubEmail",
                    emailInput.value
                );

            }
        );

    }

}

    if (pageTitle.includes("dashboard")) {

        var savedEmail =
            localStorage.getItem(
                "studentHubEmail"
            );


        if (savedEmail) {

            var welcome =
                document.createElement("p");


            welcome.innerHTML =
                "<b>Logged in as:</b> " +
                savedEmail;


            notification.after(welcome);
        }
    }

    if (pageTitle.includes("results")) {

        var semester =
            document.querySelector("select");


        if (semester) {

            semester.addEventListener(
                "change",
                function () {

                    showModal(
                        "Semester Selected",
                        "You selected: " +
                        semester.value
                    );

                }
            );
        }
    }

    if (pageTitle.includes("notification")) {

        var cells =
            document.querySelectorAll("td");


        cells.forEach(function (cell) {

            cell.addEventListener(
                "click",
                function () {

                    var text =
                        cell.textContent.trim();


                    if (text !== "") {

                        showModal(
                            "Notification",
                            text
                        );

                    }

                }
            );

        });
    }

    document.addEventListener(
        "keydown",
        function (event) {

            if (event.key === "Escape") {

                closeModal();
            }

        }
    );

});