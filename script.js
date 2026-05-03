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
