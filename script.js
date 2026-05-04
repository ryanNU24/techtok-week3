/* ================================
   Mobile Navigation Menu
================================ */

const menuToggle = document.getElementById("menu-toggle");
const navMenu = document.getElementById("nav-menu");
const navLinks = document.querySelectorAll(".nav-link");

menuToggle.addEventListener("click", () => {
  menuToggle.classList.toggle("active");
  navMenu.classList.toggle("active");
});

// Close mobile menu when a nav link is clicked
navLinks.forEach((link) => {
  link.addEventListener("click", () => {
    menuToggle.classList.remove("active");
    navMenu.classList.remove("active");
  });
});


/* ================================
   Smooth Scroll Offset for Fixed Header
================================ */

navLinks.forEach((link) => {
  link.addEventListener("click", function (event) {
    const targetId = this.getAttribute("href");

    if (targetId.startsWith("#")) {
      event.preventDefault();

      const targetSection = document.querySelector(targetId);

      if (targetSection) {
        const headerHeight = document.querySelector(".header").offsetHeight;
        const targetPosition = targetSection.offsetTop - headerHeight;

        window.scrollTo({
          top: targetPosition,
          behavior: "smooth"
        });
      }
    }
  });
});


/* ================================
   Simple Scroll Reveal Animation
================================ */

const revealElements = document.querySelectorAll(".reveal");

function revealOnScroll() {
  const windowHeight = window.innerHeight;

  revealElements.forEach((element) => {
    const elementTop = element.getBoundingClientRect().top;
    const revealPoint = 120;

    if (elementTop < windowHeight - revealPoint) {
      element.classList.add("active");
    }
  });
}

window.addEventListener("scroll", revealOnScroll);
window.addEventListener("load", revealOnScroll);


/* ================================
   Appointment Form Demo Behavior
   No backend connected
================================ */

const appointmentForm = document.getElementById("appointment-form");
const confirmationMessage = document.getElementById("confirmation-message");

appointmentForm.addEventListener("submit", function (event) {
  event.preventDefault();

  confirmationMessage.style.display = "block";

  // Scroll the confirmation message into view on smaller screens
  confirmationMessage.scrollIntoView({
    behavior: "smooth",
    block: "center"
  });
});


/* ================================
   Contact Form Demo Behavior
   No backend connected
================================ */

const contactForm = document.getElementById("contact-form");

contactForm.addEventListener("submit", function (event) {
  event.preventDefault();

  alert("Thank you! Your message has been received. This contact form is for front-end demo only.");

  contactForm.reset();
});