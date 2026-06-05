document.addEventListener("DOMContentLoaded", function () {
  /* ================================
     Mobile Navigation Menu
  ================================ */

  const menuToggle = document.getElementById("menu-toggle");
  const navMenu = document.getElementById("nav-menu");
  const navLinks = document.querySelectorAll(".nav-link");

  if (menuToggle && navMenu) {
    menuToggle.addEventListener("click", () => {
      menuToggle.classList.toggle("active");
      navMenu.classList.toggle("active");
    });
  }

  navLinks.forEach((link) => {
    link.addEventListener("click", () => {
      if (menuToggle && navMenu) {
        menuToggle.classList.remove("active");
        navMenu.classList.remove("active");
      }
    });
  });

  /* ================================
     Smooth Scroll Offset
  ================================ */

  navLinks.forEach((link) => {
    link.addEventListener("click", function (event) {
      const targetId = this.getAttribute("href");

      if (targetId && targetId.startsWith("#")) {
        event.preventDefault();

        const targetSection = document.querySelector(targetId);
        const header = document.querySelector(".header");

        if (targetSection && header) {
          const headerHeight = header.offsetHeight;
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
     Scroll Reveal Animation
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
  revealOnScroll();

  /* ================================
     URL Parameters
  ================================ */

  const urlParams = new URLSearchParams(window.location.search);

  const confirmationMessage = document.getElementById("confirmation-message");

  if (urlParams.get("success") === "appointment" && confirmationMessage) {
    confirmationMessage.style.display = "block";
    confirmationMessage.textContent = "Your appointment request has been submitted successfully. Please wait for confirmation.";

    confirmationMessage.scrollIntoView({
      behavior: "smooth",
      block: "center"
    });
  }

  if (urlParams.get("success") === "contact") {
    alert("Thank you! Your message has been sent successfully.");
  }

  /* ================================
     Product Order Form Behavior
  ================================ */

  const orderButtons = document.querySelectorAll(".order-btn");
  const productOrderForm = document.getElementById("product-order-form");

  const productNameInput = document.getElementById("product-name");
  const productTypeInput = document.getElementById("product-type");
  const productPriceInput = document.getElementById("product-price");
  const productQuantityInput = document.getElementById("product-quantity");
  const totalAmountInput = document.getElementById("total-amount");

  const productConfirmationMessage = document.getElementById("product-confirmation-message");

  function updateTotalAmount() {
    if (productPriceInput && productQuantityInput && totalAmountInput) {
      const price = parseFloat(productPriceInput.value) || 0;
      let quantity = parseInt(productQuantityInput.value) || 1;

      if (quantity < 1) {
        quantity = 1;
        productQuantityInput.value = 1;
      }

      totalAmountInput.value = (price * quantity).toFixed(2);
    }
  }

  orderButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const productName = this.getAttribute("data-product");
      const productType = this.getAttribute("data-type");
      const productPrice = this.getAttribute("data-price");

      if (
        productOrderForm &&
        productNameInput &&
        productTypeInput &&
        productPriceInput &&
        productQuantityInput &&
        totalAmountInput
      ) {
        productNameInput.value = productName;
        productTypeInput.value = productType;
        productPriceInput.value = productPrice;
        productQuantityInput.value = 1;

        updateTotalAmount();

        productOrderForm.scrollIntoView({
          behavior: "smooth",
          block: "center"
        });
      } else {
        alert("Product order form is missing. Please check the product form IDs in your HTML.");
      }
    });
  });

  if (productQuantityInput) {
    productQuantityInput.addEventListener("input", updateTotalAmount);
    productQuantityInput.addEventListener("change", updateTotalAmount);
  }

  if (productOrderForm) {
    productOrderForm.addEventListener("submit", function (event) {
      if (
        !productNameInput.value ||
        !productTypeInput.value ||
        !productPriceInput.value ||
        !totalAmountInput.value
      ) {
        event.preventDefault();
        alert("Please click Order Now or Request Now on a product first before submitting.");
      }
    });
  }

  if (urlParams.get("success") === "product" && productConfirmationMessage) {
    productConfirmationMessage.style.display = "block";
    productConfirmationMessage.textContent = "Your product order has been submitted successfully. Please wait for confirmation.";

    productConfirmationMessage.scrollIntoView({
      behavior: "smooth",
      block: "center"
    });
  }

  /* ================================
     General Error Messages
  ================================ */

  if (urlParams.get("error")) {
    const errorType = urlParams.get("error");

    if (errorType === "missing_fields") {
      alert("Please complete all required appointment fields.");
    } else if (errorType === "appointment_failed") {
      alert("Appointment submission failed. Please try again.");
    } else if (errorType === "contact_missing") {
      alert("Please complete all contact form fields.");
    } else if (errorType === "invalid_email") {
      alert("Please enter a valid email address.");
    } else if (errorType === "contact_failed") {
      alert("Contact message submission failed. Please try again.");
    } else if (errorType === "product_missing") {
      alert("Please complete all required product order fields.");
    } else if (errorType === "product_failed") {
      alert("Product order submission failed. Please try again.");
    } else {
      alert("Something went wrong. Please try again.");
    }
  }
});