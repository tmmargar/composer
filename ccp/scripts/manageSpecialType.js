"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  initializeDataTable : function() {
    dataTable.initialize({tableId: "dataTbl", aryColumns: [{ "orderSequence": [ "desc", "asc" ], "width": "10%" }, { "width": "70%" }, { "width": "20%" }, { "orderable": false, "visible": false }], aryOrder: [[ 1, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "400px", searching: false });
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
    if (document.querySelector("[id^='specialTypeMultiplier_']")) {
      document.querySelector("[id^='specialTypeMultiplier_']").min = 1;
      document.querySelector("[id^='specialTypeMultiplier_']").max = 5;
    }
  },
  validate : function() {
    const description = document.querySelectorAll("[id^='specialTypeDescription_']");
    if (description.length > 0) {
      description[0].setCustomValidity(description[0].validity.valueMissing ? "You must enter a description" : "");
    }
    const multiplier = document.querySelectorAll("[id^='specialspecialTypeMultiplier_']");
    if (multiplier.length > 0) {
      multiplier[0].setCustomValidity(multiplier[0].validity.valueMissing ? "You must enter a multiplier" : "");
    }
  }
};
let documentReadyCallback = () => {
  inputLocal.initializeDataTable();
  inputLocal.setMinMax();
  inputLocal.validate();
  input.storePreviousValue({selectors: ["[id^='specialTypeDescription_']", "[id^='specialTypeMultiplier_']"]});
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
    input.restorePreviousValue({selectors: ["[id^='specialTypeDescription_']", "[id^='specialTypeMultiplier_']"]});
  } else if (event.target && (event.target.id.includes("modify") || event.target.id.includes("delete"))) {
    inputLocal.setIds();
  }
});
document.addEventListener("input", (event) => {
  inputLocal.validate();
});