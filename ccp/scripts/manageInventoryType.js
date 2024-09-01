"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  initializeDataTable : function() {
    dataTable.initialize({tableId: "dataTbl", aryColumns: [{"orderSequence": [ "desc", "asc" ], "width" : "10%" }, { "width" : "30%" }, { "width" : "15%" }, { "width" : "15%" }, { "width" : "30%" }, { "searchable": false, "visible": false }], aryOrder: [[1, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "400px", searching: true });
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
    if (document.querySelector("[id^='minAmount_']")) {
      document.querySelector("[id^='minAmount_']").min = 1;
      document.querySelector("[id^='minAmount_']").style.width = "50px";
    }
    if (document.querySelector("[id^='maxAmount_']")) {
      document.querySelector("[id^='maxAmount_']").min = 1;
      document.querySelector("[id^='maxAmount_']").style.width = "50px";
    }
  },
  validate : function() {
    const name = document.querySelectorAll("[id^='inventoryTypeName_']");
    const minAmount = document.querySelectorAll("[id^='minAmount_']");
    const maxAmount = document.querySelectorAll("[id^='maxAmount_']");
    if (name.length > 0) {
      name[0].setCustomValidity(name[0].validity.valueMissing ? "You must enter a name" : "");
      minAmount[0].setCustomValidity(minAmount[0].validity.valueMissing ? "You must enter a #" : minAmount[0].validity.rangeUnderflow ? "You must enter a # >= " + minAmount[0].min : minAmount[0].validity.rangeOverflow ? "You must enter a # <= " + minAmount[0].max : "");
      maxAmount[0].setCustomValidity(maxAmount[0].validity.valueMissing ? "You must enter a #" : maxAmount[0].validity.rangeUnderflow ? "You must enter a # >= " + maxAmount[0].min : maxAmount[0].validity.rangeOverflow ? "You must enter a # <= " + maxAmount[0].max : "");
    }
  }
};
let documentReadyCallback = () => {
  inputLocal.initializeDataTable();
  inputLocal.setMinMax();
  inputLocal.validate();
  input.storePreviousValue({selectors: ["[id^='inventoryTypeName_'], [id^='minAmount_'], [id^='maxAmount_']"]});
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
    input.restorePreviousValue({selectors: ["[id^='inventoryTypeName_'], [id^='minAmount_'], [id^='maxAmount_']"]});
  } else if (event.target && (event.target.id.includes("modify") || event.target.id.includes("delete"))) {
    inputLocal.setIds();
  }
});
document.addEventListener("input", (event) => {
  inputLocal.validate();
  if (event.target && (event.target.id.includes("minAmount") || event.target.id.includes("maxAmount"))) {
    inputLocal.setMinMax();
  }
});