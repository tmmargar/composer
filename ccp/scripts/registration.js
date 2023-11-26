"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  validate : function() {
    const food = document.querySelector("#food");
    food.setCustomValidity(food.validity.valueMissing ? "You must enter a dish to pass" : "");
  }
};
document.addEventListener("click", (event) => {
  inputLocal.validate();
  if (event.target && event.target.id == "register") {
     document.querySelector("#mode").value = "savecreate";
  } else if (event.target && event.target.id == "unregister") {
     document.querySelector("#mode").value = "savemodify";
  }
});
document.addEventListener("input", (event) => {
  inputLocal.validate();
});