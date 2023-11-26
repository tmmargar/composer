"use script";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  enableSend : function() {
    if (document.querySelector("#send")) {
      document.querySelector("#send").disabled = document.querySelector("#username").value.length == 0 || !(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.querySelector("#email").value));
    }
  },
  validatePassword : function({event} = {}) {
    if (document.querySelector("#password")) {
      if (document.querySelector("#password").value.length == 0 || document.querySelector("#confirmPassword").value.length == 0 || document.querySelector("#password").value != document.querySelector("#confirmPassword").value) {
        document.querySelector("#resetPassword").disabled = true;
        if (event) {
          display.showErrors({errors: ["Passwords are blank or do not match"]});
          event.preventDefault();
          event.stopPropagation();
        }
      } else {
        document.querySelector("#resetPassword").disabled = false;
        display.clearErrorsAndMessages();
      }
    }
  }
};
let documentReadyCallback = () => {
  inputLocal.enableSend();
};
if (document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll)) {
  documentReadyCallback();
} else {
  document.addEventListener("DOMContentLoaded", documentReadyCallback);
}
document.addEventListener("click", (event) => {
  if (event.target && (event.target.id.includes("username") || event.target.id.includes("email"))) {
    input.validateLength({obj: document.querySelector("#username"), length: 1, focus: true});
    input.validateLength({obj: document.querySelector("#email"), length: 1, focus: true});
    inputLocal.enableSend();
  } else if (event.target && event.target.id.includes("send")) {
    document.querySelector("#mode").value = "resetPasswordRequest";
  } else if (event.target && event.target.id.includes("resetPassword")) {
    inputLocal.validatePassword({event: event});
    inputLocal.enableSend();
    document.querySelector("#mode").value = "resetPasswordConfirm";
  }
});
document.addEventListener("keyup", (event) => {
  if (event.target && (event.target.id.includes("username") || event.target.id.includes("email"))) {
    input.validateLength({obj: document.querySelector("#username"), length: 1, focus: true});
    input.validateLength({obj: document.querySelector("#email"), length: 1, focus: true});
    inputLocal.enableSend();
  } else if (event.target && event.target.id.includes("confirmPassword")) {
    inputLocal.validatePassword({event: event});
    inputLocal.enableSend();
  }
});
document.addEventListener("paste", (event) => {
  if (event.target && (event.target.id.includes("username") || event.target.id.includes("email"))) {
    input.validateLength({obj: document.querySelector("#username"), length: 1, focus: true});
    input.validateLength({obj: document.querySelector("#email"), length: 1, focus: true});
    inputLocal.enableSend();
  } else if (event.target && event.target.id.includes("confirmPassword")) {
    inputLocal.validatePassword({event: event});
    inputLocal.enableSend();
  }
});