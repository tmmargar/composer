"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  initializeDataTable : function() {
    dataTable.initialize({tableId: "dataTbl", aryColumns: [{"orderSequence": [ "desc", "asc" ], "width" : "10%" }, { "width" : "30%" }, { "width" : "10%" }, { "width" : "10%" }, { "width" : "10%" }, { "width" : "30%" }, { "searchable": false, "visible": false }], aryOrder: [[1, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "400px", searching: true });
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
        document.querySelector("[id^='minAmount_']").style.width = "50px";
    }
    if (document.querySelector("[id^='maxAmount_']")) {
        document.querySelector("[id^='maxAmount_']").style.width = "50px";
    }
    if (document.querySelector("[id^='currentAmount_']")) {
      document.querySelector("[id^='currentAmount_']").min = document.querySelector("[id^='warningAmount_']").value > 0 && document.querySelector("[id^='warningAmount_']").value > document.querySelector("[id^='minAmount_']").value ? document.querySelector("[id^='warningAmount_']").value : document.querySelector("[id^='minAmount_']").value;
      document.querySelector("[id^='currentAmount_']").max = document.querySelector("[id^='maxAmount_']").value;
      document.querySelector("[id^='currentAmount_']").style.width = "50px";
    }
    if (document.querySelector("[id^='warningAmount_']")) {
      document.querySelector("[id^='warningAmount_']").min = document.querySelector("[id^='orderAmount_']").value > 0 && document.querySelector("[id^='orderAmount_']").value > document.querySelector("[id^='minAmount_']").value ? document.querySelector("[id^='orderAmount_']").value : document.querySelector("[id^='minAmount_']").value;
      document.querySelector("[id^='warningAmount_']").max = document.querySelector("[id^='currentAmount_']").value > 0 ? parseInt(document.querySelector("[id^='currentAmount_']").value) - 1 : document.querySelector("[id^='minAmount_']").value;
      document.querySelector("[id^='warningAmount_']").style.width = "50px";
    }
    if (document.querySelector("[id^='orderAmount_']")) {
      document.querySelector("[id^='orderAmount_']").min = document.querySelector("[id^='minAmount_']").value;
      document.querySelector("[id^='orderAmount_']").max = parseInt(document.querySelector("[id^='warningAmount_']").value) - 1;
      document.querySelector("[id^='orderAmount_']").style.width = "50px";
    }
  },
  validate : function() {
    const inventoryType = document.querySelectorAll("[id^='inventoryTypeId_']");
    const currentAmount = document.querySelectorAll("[id^='currentAmount_']");
    const warningAmount = document.querySelectorAll("[id^='warningAmount_']");
    const orderAmount = document.querySelectorAll("[id^='orderAmount_']");
    if (inventoryType.length > 0) {
      inventoryType[0].setCustomValidity(inventoryType[0].value == "" ? "You must select an inventory type" : "");
      currentAmount[0].setCustomValidity(currentAmount[0].validity.valueMissing ? "You must enter a #" : currentAmount[0].validity.rangeUnderflow ? "You must enter a # >= " + currentAmount[0].min : currentAmount[0].validity.rangeOverflow ? "You must enter a # <= " + currentAmount[0].max : "");
      warningAmount[0].setCustomValidity(warningAmount[0].validity.valueMissing ? "You must enter a #" : warningAmount[0].validity.rangeUnderflow ? "You must enter a # >= " + warningAmount[0].min : warningAmount[0].validity.rangeOverflow ? "You must enter a # <= " + warningAmount[0].max : "");
      orderAmount[0].setCustomValidity(orderAmount[0].validity.valueMissing ? "You must enter a #" : orderAmount[0].validity.rangeUnderflow ? "You must enter a # >= " + orderAmount[0].min : orderAmount[0].validity.rangeOverflow ? "You must enter a # <= " + orderAmount[0].max : "");
    }
  }
};
let documentReadyCallback = () => {
  inputLocal.initializeDataTable();
  inputLocal.setMinMax();
  inputLocal.validate();
  input.storePreviousValue({selectors: ["[id^='currentAmount_'], [id^='warningAmount_'], [id^='orderAmount_']"]});
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
document.addEventListener("change", (event) => {
    if (event.target && event.target.id.includes("inventoryTypeId")) {
        document.querySelectorAll("[id^='inventoryTypeId_']")?.forEach(obj => {
            const id = obj.id.split("_");
            const values = obj.options[obj.selectedIndex].value.split("::");
            document.querySelector("#minAmount_" + id[1]).value = values[1];
            document.querySelector("#maxAmount_" + id[1]).value = values[2];
        });
    }
    inputLocal.setMinMax();
});
document.addEventListener("click", (event) => {
  inputLocal.validate();
  if (event.target && event.target.id.includes("reset")) {
    input.restorePreviousValue({selectors: ["[id^='currentAmount_'], [id^='warningAmount_'], [id^='orderAmount_']"]});
  } else if (event.target && (event.target.id.includes("modify") || event.target.id.includes("delete"))) {
    inputLocal.setIds();
  }
});
document.addEventListener("input", (event) => {
  inputLocal.validate();
  if (event.target && (event.target.id.includes("currentAmount") || event.target.id.includes("warningAmount") || event.target.id.includes("orderAmount"))) {
    inputLocal.setMinMax();
  }
});