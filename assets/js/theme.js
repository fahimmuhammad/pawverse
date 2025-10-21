// ===============================
// PawVerse Global Theme System
// ===============================
// Handles site-wide light/dark mode toggle with localStorage persistence.
// Author: Fahim & PawVerse Dev Team 💙

(function () {
  const THEME_KEY = "pawverse_theme";
  const DARK_CLASS = "theme-dark";
  const TRANSITION_CLASS = "theme-transition";

  const body = document.body;
  const switchSelector = "#themeSwitch";

  // ---------- Helpers ----------
  function applyTheme(theme) {
    if (theme === "dark") {
      body.classList.add(DARK_CLASS);
      const switchEl = document.querySelector(switchSelector);
      if (switchEl) {
        switchEl.classList.add("on");
        switchEl.setAttribute("aria-checked", "true");
      }
    } else {
      body.classList.remove(DARK_CLASS);
      const switchEl = document.querySelector(switchSelector);
      if (switchEl) {
        switchEl.classList.remove("on");
        switchEl.setAttribute("aria-checked", "false");
      }
    }

    // Smooth transition effect
    body.classList.add(TRANSITION_CLASS);
    setTimeout(() => body.classList.remove(TRANSITION_CLASS), 400);
  }

  // ---------- Initialization ----------
  function initTheme() {
    const saved = localStorage.getItem(THEME_KEY);
    const initial = saved === "dark" ? "dark" : "light";
    applyTheme(initial);
  }

  // ---------- Toggle ----------
  function toggleTheme() {
    const isDark = body.classList.contains(DARK_CLASS);
    const next = isDark ? "light" : "dark";
    applyTheme(next);
    localStorage.setItem(THEME_KEY, next);
  }

  // ---------- Event Binding ----------
  function bindToggle() {
    const switchEl = document.querySelector(switchSelector);
    if (!switchEl) return;

    switchEl.addEventListener("click", toggleTheme);
    switchEl.addEventListener("keydown", (e) => {
      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        toggleTheme();
      }
    });
  }

  // ---------- Global Init ----------
  document.addEventListener("DOMContentLoaded", () => {
    initTheme();
    bindToggle();
  });
})();
