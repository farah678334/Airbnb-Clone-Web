document.addEventListener("DOMContentLoaded", () => {
  // Utility: setup open/close for a modal
  function setupModal(triggerId, modalId) {
    const trigger = document.getElementById(triggerId);
    const modal = document.getElementById(modalId);
    if (!modal || !trigger) return;

    const closeBtn = modal.querySelector(".close");

    trigger.onclick = e => {
      e.preventDefault();
      modal.style.display = "block";
    };

    if (closeBtn) {
      closeBtn.onclick = () => modal.style.display = "none";
    }

    window.addEventListener("click", e => {
      if (e.target === modal) modal.style.display = "none";
    });
  }

  // Attach action modals
  setupModal("open-login", "login-modal");
  setupModal("open-signup", "signup-modal");
  setupModal("open-reserve", "reserve-modal");

  // Generic close logic for all modals (success/error boxes)
  const modals = document.querySelectorAll(".modal");
  modals.forEach(modal => {
    const closeBtn = modal.querySelector(".close");
    if (closeBtn) {
      closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
      });
    }
  });
  window.addEventListener("click", e => {
    modals.forEach(modal => {
      if (modal.style.display === "block" && e.target === modal) {
        modal.style.display = "none";
      }
    });
  });

  // Signup form handler
  const signupForm = document.getElementById("signup-form");
  if (signupForm) {
    signupForm.addEventListener("submit", async function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      const msgBox = document.getElementById("signup-message");

      try {
        const res = await fetch("register.php", { method: "POST", body: formData });
        const text = (await res.text()).trim();

        if (text.includes("success")) {
          this.reset();
          msgBox.textContent = "";
          document.getElementById("signup-modal").style.display = "none";
          document.getElementById("signup-success-box").style.display = "block";
        } else {
          msgBox.textContent = "❌ Registration failed. Please try again.";
          msgBox.style.color = "red";
        }
      } catch {
        msgBox.textContent = "⚠️ Network error. Please try again.";
        msgBox.style.color = "orange";
      }
    });
  }

  // Login form handler
  const loginForm = document.getElementById("login-form");
  if (loginForm) {
    loginForm.addEventListener("submit", async function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      const msgBox = document.getElementById("login-message");

      try {
        const res = await fetch("login.php", { method: "POST", body: formData });
        const text = (await res.text()).trim();

        if (text === "success") {
          this.reset();
          msgBox.textContent = "";
          document.getElementById("login-modal").style.display = "none";
          document.getElementById("login-success-box").style.display = "block";
        } else {
          msgBox.textContent = "❌ Invalid email or password.";
          msgBox.style.color = "red";
        }
      } catch {
        msgBox.textContent = "⚠️ Network error. Please try again.";
        msgBox.style.color = "orange";
      }
    });
  }

  // Reserve form handler
  const reserveForm = document.querySelector("#reserve-modal form");
  if (reserveForm) {
    reserveForm.addEventListener("submit", async function(e) {
      e.preventDefault();
      const formData = new FormData(this);

      try {
        const res = await fetch("reserve.php", { method: "POST", body: formData });
        const text = (await res.text()).trim();

        if (text === "success") {
          this.reset();
          document.getElementById("reserve-modal").style.display = "none";
          document.getElementById("reserve-success-box").style.display = "block";
        } else if (text === "error") {
          document.getElementById("reserve-modal").style.display = "none";
          document.getElementById("reserve-error-box").style.display = "block";
        } else if (text === "login_required") {
          alert("⚠️ You must log in to reserve.");
        } else {
          alert("⚠️ Something went wrong. Please try again.");
        }
      } catch {
        alert("⚠️ Network error. Please try again.");
      }
    });
  }
});