"use strict";
import { dataTable, display, input } from "./import.js";
//import IMask from 'imask';
export const inputLocal = {
  initializeDataTable : function() {
    dataTable.initialize({tableId: "dataTbl", aryColumns: [{ "orderSequence": [ "desc", "asc" ], "width" : "4%" }, { "type" : "name", "width" : "11%" }, { "width" : "10%" }, { "width" : "15%" }, { "render" : function (data) { return display.formatPhone({value: data}); }, "width" : "8%" }, { "render" : function (data, type, row, meta) { return display.formatHighlight({value: data, meta: meta, tableId: "dataTbl"}); }, "width" : "4%" }, { "width" : "8%" }, { "width" : "7%" }, { "width" : "8%" }, { "render" : function (data, type, row, meta) { return display.formatActive({value: data, meta: meta, tableId: "dataTbl"}); },  "width" : "3%" }, { "searchable": false, "visible": false }], aryOrder: [[9, "desc"], [1, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "400px", searching: false });
  },
  setId : function({selectedRow} = {}) {
    return selectedRow.children[0].innerHTML;
  },
  setIds : function() {
    const selectedRows = dataTable.getSelectedRows({jQueryTable: $("#dataTbl").dataTable()});
    let ids = "";
    for (let selectedRow of selectedRows) {
      ids += inputLocal.setId({selectedRow: selectedRow}) + ", ";
    }
    ids = ids.substring(0, ids.length - 2);
    document.querySelector("#ids").value = ids;
  },
  validate : function() {
    const firstName = document.querySelectorAll("[id^='firstName_']");
    const lastName = document.querySelectorAll("[id^='lastName_']");
    const username = document.querySelectorAll("[id^='username_']");
    const password = document.querySelectorAll("[id^='password_']");
    const email = document.querySelectorAll("[id^='email_']");
    if (firstName.length > 0) {
      firstName[0].setCustomValidity(firstName[0].validity.valueMissing ? "You must enter a first name" : "");
      lastName[0].setCustomValidity(lastName[0].validity.valueMissing ? "You must enter a last name" : "");
      username[0].setCustomValidity(username[0].validity.valueMissing ? "You must enter a username" : "");
      password[0].setCustomValidity(password[0].validity.valueMissing ? "You must enter a password" : "");
      email[0].setCustomValidity(email[0].validity.valueMissing ? "You must enter an email" : email[0].validity.typeMismatch ? "You must enter a vali email" : "");
    }
  },
  validatePhone : function(event) {
    if (document.querySelector("#mode").value.startsWith("save")) {
      if (document.querySelector("[id^='phone_']").classList.contains("errors")) {
        display.showErrors({errors: ["You must enter a valid phone #"]});
        document.querySelector("[id^='phone_']").focus();
        event.preventDefault();
        event.stopPropagation();
        document.querySelector("#mode").value = document.querySelector("#mode").value.replace("save", "");
      } else {
        display.clearErrorsAndMessages();
      }
    }
  }
};
let documentReadyCallback = () => {
  if (document.querySelector("#mode").value == "create" || document.querySelector("#mode").value == "modify") {
    document.querySelector("body").style.maxWidth = "450px";
  }
  inputLocal.initializeDataTable();
  inputLocal.validate();
  if (document.querySelector("[id^='phone_']")) {
    const patternMaskPhone = IMask(document.querySelector("[id^='phone_']"), { lazy: false, mask: '(000) 000-0000' });
    patternMaskPhone.on('accept', function() {
      if (patternMaskPhone.unmaskedValue == "") {
        document.querySelector("[id^='phone_']").classList.remove("errors");
      } else {
        document.querySelector("[id^='phone_']").classList.add("errors");
      }
    });
    patternMaskPhone.on('complete', function() {
      document.querySelector("[id^='phone_']").classList.remove("errors");
    });
  }
  input.storePreviousValue({selectors: ["[id^='firstName']", "[id^='lastName']", "[id^='username']", "[id^='password']", "[id^='email']", "[id^='phone']", "[id^='administrator']", "[id^='active']"]});
};
if (document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll)) {
  documentReadyCallback();
} else {
  document.addEventListener("DOMContentLoaded", documentReadyCallback);
}
document.querySelectorAll("#dataTbl tbody tr")?.forEach(row => row.addEventListener("click", (event) => {
  const selected = row.classList.contains("selected");
  document.querySelectorAll("[id^='modify']")?.forEach(btn => { btn.disabled = selected; });
  document.querySelectorAll("[id^='delete']")?.forEach(btn => { btn.disabled = selected; });
  // if 1 row is already selected
  if (selected || document.querySelectorAll("#dataTbl tbody tr.selected").length == 1) {
    row.classList.remove("selected");
  } else {
    row.classList.add("selected");
  }
}));
document.addEventListener("click", (event) => {
  inputLocal.validate();
  inputLocal.validatePhone(event);
  if (event.target && event.target.id.includes("reset")) {
    input.restorePreviousValue({selectors: ["[id^='firstName']", "[id^='lastName']", "[id^='username']", "[id^='password']", "[id^='email']", "[id^='phone']", "[id^='administrator']", "[id^='active']"]});
  } else if (event.target && (event.target.id.includes("modify") || event.target.id.includes("delete"))) {
    inputLocal.setIds();
  }
});
document.addEventListener("input", (event) => {
  inputLocal.validate();
  inputLocal.validatePhone(event);
});
document.addEventListener("submit", (event) => {
  if (event.target && event.target.id.includes("frm")) {
    if (document.querySelector("[id^='phone_']")) {
      document.querySelector("[id^='phone_']").unmaskedValue;
    }
  }
});