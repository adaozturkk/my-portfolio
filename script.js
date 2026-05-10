// Form Validation

const contactForm = document.getElementById("contact-form");
const formFeedback = document.getElementById("form-feedback");

if (contactForm) {
  contactForm.addEventListener("submit", function (event) {
    event.preventDefault();

    let isValid = true;
    let errorMessages = [];

    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const message = document.getElementById("message").value.trim();

    formFeedback.innerHTML = "";
    formFeedback.style.color = "#ef4444";

    if (name.length < 2) {
      isValid = false;
      errorMessages.push("Name must be at least 2 characters.");
    }

    if (!email.includes("@") || !email.includes(".")) {
      isValid = false;
      errorMessages.push("Please enter a valid email address.");
    }

    if (message.length < 10) {
      isValid = false;
      errorMessages.push("Message must be at least 10 characters long.");
    }

    if (!isValid) {
      formFeedback.innerHTML = errorMessages.join("<br>");
      return;
    }

    formFeedback.style.color = "#a78bfa";
    formFeedback.innerHTML = "Sending message...";

    const formData = new FormData(contactForm);

    fetch("submit_form.php", {
      method: "POST",
      body: formData,
    })
      .then(function (response) {
        return response.json();
      })
      .then(function (data) {
        if (data.success) {
          formFeedback.style.color = "#a78bfa";
          formFeedback.innerHTML = "Message sent successfully!";
          contactForm.reset();
        } else {
          formFeedback.style.color = "#ef4444";
          formFeedback.innerHTML = "Error: " + data.error;
        }
      })
      .catch(function (error) {
        formFeedback.style.color = "#ef4444";
        formFeedback.innerHTML = "Something went wrong. Please try again.";
        console.error("Fetch error:", error);
      });
  });
}

// Back to top button

const topButton = document.createElement("button");
topButton.innerText = "↑ Top";
topButton.className = "submit-btn";
topButton.style.position = "fixed";
topButton.style.bottom = "30px";
topButton.style.right = "30px";
topButton.style.display = "none";
topButton.style.width = "auto";
topButton.style.padding = "0.5rem 1rem";
topButton.style.zIndex = "1000";

document.body.appendChild(topButton);

window.addEventListener("scroll", function () {
  if (window.scrollY > 300) {
    topButton.style.display = "block";
  } else {
    topButton.style.display = "none";
  }
});

topButton.addEventListener("click", function () {
  window.scrollTo({ top: 0, behavior: "smooth" });
});

// Theme toggle

const themeBtn = document.getElementById("theme-toggle");
const body = document.body;

if (localStorage.getItem("theme") === "light") {
  body.classList.add("light-mode");
}

if (themeBtn) {
  themeBtn.addEventListener("click", function () {
    body.classList.toggle("light-mode");

    if (body.classList.contains("light-mode")) {
      localStorage.setItem("theme", "light");
    } else {
      localStorage.setItem("theme", "dark");
    }
  });
}

// Typing animation

const heroTitle = document.querySelector("#about h1");

if (heroTitle) {
  const fullText = "Hi, I'm Ada.";
  let index = 0;

  heroTitle.textContent = "";

  const typingInterval = setInterval(function () {
    heroTitle.textContent += fullText[index];
    index++;

    if (index === fullText.length) {
      clearInterval(typingInterval);
    }
  }, 80);
}
