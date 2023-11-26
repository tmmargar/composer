"use script";
import { dataTable, display, input } from "./import.js";
document.addEventListener("click", (event) => {
  if (event.target && event.target.id == "login") {
    document.querySelector("#mode").value = event.target.innerText.trim().toLowerCase();
  }
});
document.addEventListener("input", (event) => {
  const username = document.querySelector("#username");
  const password = document.querySelector("#password");
  username.setCustomValidity(username.validity.valueMissing ? "You must enter a username" : "");
  password.setCustomValidity(password.validity.valueMissing ? "You must enter a password" : "");
});