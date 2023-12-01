"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  enableSave : function() {
    document.querySelectorAll("[id^='save']")?.forEach(obj => {
      obj.disabled = document.querySelectorAll("[id^='approvePlayer_']:checked").length == 0 && document.querySelectorAll("[id^='rejectPlayer_']:checked").length == 0;
    });
  },
  initializeTable : function() {
    dataTable.initialize({tableId: "dataTblSignupApproval", aryColumns: [ { "type" : "name" }, null, null, null, { "type" : "name" }, { "sortable": false }, { "sortable": false } ], aryOrder: [], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "400px", searching: false });
  }
};
let documentReadyCallback = () => {
  inputLocal.initializeTable();
  inputLocal.enableSave();
  input.countUpdate({prefix: "approvePlayer"});
  input.countUpdate({prefix: "rejectPlayer"});
};
if (document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll)) {
  documentReadyCallback();
} else {
  document.addEventListener("DOMContentLoaded", documentReadyCallback);
}
document.addEventListener("click", (event) => {
  if (event.target && event.target.id.includes("approvePlayerCheckAll")) {
    input.toggleCheckboxes({id: "approvePlayer", idAll: "approvePlayer"});
    if (event.target.checked) {
      input.checkboxToggle({inputId: "rejectPlayer", checked: false});
    }
    input.countUpdate({prefix: "approvePlayer"});
    input.countUpdate({prefix: "rejectPlayer"});
    inputLocal.enableSave();
    event.stopImmediatePropagation();
  } else if (event.target && event.target.id.includes("rejectPlayerCheckAll")) {
    input.toggleCheckboxes({id: "rejectPlayer", idAll: "rejectPlayer"});
    if (event.target.checked) {
      input.checkboxToggle({inputId: "approvePlayer", checked: false});
    }
    input.countUpdate({prefix: "approvePlayer"});
    input.countUpdate({prefix: "rejectPlayer"});
    inputLocal.enableSave();
    event.stopImmediatePropagation();
  } else if (event.target && event.target.id.includes("approvePlayer")) {
    if (event.target.checked) {
      const values = event.target.id.split("_");
      input.checkboxToggle({inputId: "rejectPlayer_" + values[1], checked: false});
      input.countUpdate({prefix: "rejectPlayer"});
    }
    input.toggleCheckAll({id: "approvePlayer", idAll: "approvePlayer"});
    input.toggleCheckAll({id: "rejectPlayer", idAll: "rejectPlayer"});
    inputLocal.enableSave();
    input.countUpdate({prefix: "approvePlayer"});
  } else if (event.target && event.target.id.includes("rejectPlayer")) {
    if (event.target.checked) {
      const values = event.target.id.split("_");
      input.checkboxToggle({inputId: "approvePlayer_" + values[1], checked: false});
      input.countUpdate({prefix: "approvePlayer"});
    }
    input.toggleCheckAll({id: "approvePlayer", idAll: "approvePlayer"});
    input.toggleCheckAll({id: "rejectPlayer", idAll: "rejectPlayer"});
    inputLocal.enableSave();
    input.countUpdate({prefix: "rejectPlayer"});
  }
});
