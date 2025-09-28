"use script";
import { dataTable, display, input } from "./import.js";
document.addEventListener("click", (event) => {
  if (event.target && event.target.id == "reminder") {
    document.querySelector("#mode").value = event.target.innerText.trim().toLowerCase();
  }
});
document.addEventListener("input", (event) => {
  const days = document.querySelector("#daysIn");
  days.setCustomValidity(days.validity.valueMissing ? "You must enter a # of days" : "");
});