"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  initializeDataTable : function() {
    dataTable.initialize({tableId: "dataTbl", aryColumns: [{ "orderSequence": [ "desc", "asc" ], "width": "10%" }, { "width": "50%" }, { "type": "date", "width": "20%" }, { "type": "date", "width": "20%" }, { "orderable": false, "visible": false }], aryOrder: [[3, "desc"], [2, "desc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "400px", searching: true });
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
  setMinMax : function() {
    if (document.querySelector("[id^='notificationStartDate_']")) {
      if (document.querySelector("[id^='notificationEndDate_']:valid")) {
        document.querySelector("[id^='notificationStartDate_']").max = input.formatDate({value: document.querySelector("[id^='notificationEndDate_']").value, field: "minutes", operation: "-", amount: 1});
      } else {
        document.querySelector("[id^='notificationStartDate_']").removeAttribute("max");
      }
    }
    if (document.querySelector("[id^='notificationEndDate_']")) {
      if (document.querySelector("[id^='notificationStartDate_']:valid")) {
        document.querySelector("[id^='notificationEndDate_']").min = input.formatDate({value: document.querySelector("[id^='notificationStartDate_']").value, field: "minutes", operation: "+", amount: 1});
      } else {
        document.querySelector("[id^='notificationEndDate_']").removeAttribute("min");
      }
    }
  },
  validate : function() {
    const description = document.querySelectorAll("[id^='notificationDescription_']");
    const startDate = document.querySelectorAll("[id^='notificationStartDate_']");
    const endDate = document.querySelectorAll("[id^='notificationEndDate_']");
    if (description.length > 0) {
      description[0].setCustomValidity(description[0].validity.valueMissing ? "You must enter a description" : "");
      startDate[0].setCustomValidity(startDate[0].validity.valueMissing ? "You must enter a valid start date" : startDate[0].validity.rangeOverflow ? "You must enter a start date before the end date" : "");
      endDate[0].setCustomValidity(endDate[0].validity.valueMissing ? "You must enter a valid end date" : endDate[0].validity.rangeUnderflow ? "You must enter an end date after the start date" : "");
    }
  }
};
let documentReadyCallback = () => {
  inputLocal.initializeDataTable();
  inputLocal.setMinMax();
  input.storePreviousValue({selectors: ["[id^='notificationDescription_']", "[id^='notificationStartDate_']", "[id^='notificationEndDate_']"]});
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
  if (event.target && event.target.id.includes("reset")) {
    input.restorePreviousValue({selectors: ["[id^='notificationDescription_']", "[id^='notificationStartDate_']", "[id^='notificationEndDate_']"]});
  } else if (event.target && (event.target.id.includes("modify") || event.target.id.includes("delete"))) {
    inputLocal.setIds();
  }
});
document.addEventListener("input", (event) => {
  inputLocal.validate();
  if (event.target && event.target.classList.contains("timePicker")) {
    inputLocal.setMinMax();
  }
});