"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  enableSave : function() {
    document.querySelectorAll("[id^='save']")?.forEach(obj => {
      obj.disabled = document.querySelectorAll("[id^='approveUser_']:checked").length == 0 && document.querySelectorAll("[id^='rejectUser_']:checked").length == 0;
    });
  },
  initializeTable : function() {
    dataTable.initialize({tableId: "dataTblSignupApproval", aryColumns: [ { "type" : "name" }, null, null, null, { "type" : "name" }, { "sortable": false }, { "sortable": false } ], aryOrder: [], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "400px", searching: false });
  }
};
let documentReadyCallback = () => {
  inputLocal.initializeTable();
  inputLocal.enableSave();
  input.countUpdate({prefix: "approveUser"});
  input.countUpdate({prefix: "rejectUser"});
};
if (document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll)) {
  documentReadyCallback();
} else {
  document.addEventListener("DOMContentLoaded", documentReadyCallback);
}
document.addEventListener("click", (event) => {
  if (event.target && event.target.id.includes("approveUserCheckAll")) {
    input.toggleCheckboxes({id: "approveUser", idAll: "approveUser"});
    if (event.target.checked) {
      input.checkboxToggle({inputId: "rejectUser", checked: false});
    }
    input.countUpdate({prefix: "approveUser"});
    input.countUpdate({prefix: "rejectUser"});
    inputLocal.enableSave();
    event.stopImmediatePropagation();
  } else if (event.target && event.target.id.includes("rejectUserCheckAll")) {
    input.toggleCheckboxes({id: "rejectUser", idAll: "rejectUser"});
    if (event.target.checked) {
      input.checkboxToggle({inputId: "approveUser", checked: false});
    }
    input.countUpdate({prefix: "approveUser"});
    input.countUpdate({prefix: "rejectUser"});
    inputLocal.enableSave();
    event.stopImmediatePropagation();
  } else if (event.target && event.target.id.includes("approveUser")) {
    if (event.target.checked) {
      const values = event.target.id.split("_");
      input.checkboxToggle({inputId: "rejectUser_" + values[1], checked: false});
      input.countUpdate({prefix: "rejectUser"});
    }
    input.toggleCheckAll({id: "approveUser", idAll: "approveUser"});
    input.toggleCheckAll({id: "rejectUser", idAll: "rejectUser"});
    inputLocal.enableSave();
    input.countUpdate({prefix: "approveUser"});
  } else if (event.target && event.target.id.includes("rejectUser")) {
    if (event.target.checked) {
      const values = event.target.id.split("_");
      input.checkboxToggle({inputId: "approveUser_" + values[1], checked: false});
      input.countUpdate({prefix: "approveUser"});
    }
    input.toggleCheckAll({id: "approveUser", idAll: "approveUser"});
    input.toggleCheckAll({id: "rejectUser", idAll: "rejectUser"});
    inputLocal.enableSave();
    input.countUpdate({prefix: "rejectUser"});
  }
});
