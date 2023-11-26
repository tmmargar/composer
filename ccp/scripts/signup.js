"use script";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  enableSignUp : function() {
    if ((document.querySelector("#name").value.length == 0)) {
      document.querySelector("#signUp").disabled = true;
      input.invalid({selector: "#name"});
      display.showErrors({errors: ["Name is blank"]});
    } else if (!(/.+\s.+$/.test(document.querySelector("#name").value))) {
      document.querySelector("#signUp").disabled = true;
      input.invalid({selector: "#name"});
      display.showErrors({errors: ["Your name is not a valid format (must include first and last separate by space)"]});
    } else if ((document.querySelector("#email").value.length == 0)) {
      document.querySelector("#signUp").disabled = true;
      input.invalid({selector: "#email"});
      display.showErrors({errors: ["Email is blank"]});
  } else if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.querySelector("#email").value))) {
      document.querySelector("#signUp").disabled = true;
      input.invalid({selector: "#email"});
      display.showErrors({errors: ["Your email is not a valid format (a@b.cd)"]});
    } else if ((document.querySelector("#username").value.length == 0)) {
      document.querySelector("#signUp").disabled = true;
      input.invalid({selector: "#username"});
      display.showErrors({errors: ["Username is blank"]});
    } else if ((document.querySelector("#password").value.length == 0) || (document.querySelector("#confirmPassword").value.length == 0) || document.querySelector("#password").value != document.querySelector("#confirmPassword").value) {
      document.querySelector("#signUp").disabled = true;
      input.invalid({selector: "#password"});
      input.invalid({selector: "#confirmPassword"});
      display.showErrors({errors: ["Your passwords are blank or do not match"]});
    } else {
      display.clearErrorsAndMessages();
      document.querySelector("#signUp").disabled = false;
    }
  },
  validate : function() {
    input.validateLength({obj: document.querySelector("#name"), length: 1, focus: false});
    input.validateLength({obj: document.querySelector("#username"), length: 1, focus: false});
    input.validateLength({obj: document.querySelector("#email"), length: 1, focus: false});
    input.validateLength({obj: document.querySelector("#password"), length: 1, focus: false});
    input.validateLength({obj: document.querySelector("#confirmPassword"), length: 1, focus: false});
    inputLocal.enableSignUp();
  }
};
document.addEventListener("click", (event) => {
  if (event.target && event.target.id.includes("signUp")) {
    document.querySelector("#mode").value = event.target.value.toLowerCase().replace(" ", "");
  }
});
document.addEventListener("keyup", (event) => {
  if (event.target && (event.target.id.includes("name") || event.target.id.includes("username") || event.target.id.includes("email") || event.target.id.includes("password") || event.target.id.includes("confirmPassword"))) {
    inputLocal.validate();
  }
});
document.addEventListener("paste", (event) => {
  if (event.target && (event.target.id.includes("name") || event.target.id.includes("username") || event.target.id.includes("email") || event.target.id.includes("password") || event.target.id.includes("confirmPassword"))) {
    inputLocal.validate();
  }
});