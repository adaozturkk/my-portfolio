// Form Validation

const contactForm = document.getElementById("contact-form");
const formFeedback = document.getElementById("form-feedback");

if (contactForm) {
  contactForm.addEventListener("submit", function (event) {
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
      event.preventDefault();
      formFeedback.innerHTML = errorMessages.join("<br>");
    } else {
      formFeedback.style.color = "#a78bfa";
      formFeedback.innerHTML = "Validation passed! Sending message...";
    }
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
