"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  initializeDataTable : function() {
    dataTable.initialize({tableId: "dataTbl", aryColumns: [{ "orderSequence": [ "desc", "asc" ], "width" : "15%", "visible": false }, { "width" : "50%" }, { "orderSequence": [ "desc", "asc" ], "width" : "15%", "visible": false }, { "width" : "50%" }, { "searchable": false, "visible": false }], aryOrder: [[1, "asc"], [3, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "400px", searching: true });
  },
  setId : function({selectedRow} = {}) {
    const htmlGroup = selectedRow.children[0].innerHTML;
    let positionStart = htmlGroup.indexOf("groupId=");
    const groupId = htmlGroup.substring(positionStart + 8, htmlGroup.indexOf("&", positionStart));
    const htmlPayout = selectedRow.children[1].innerHTML;
    positionStart = htmlPayout.indexOf("payoutId=");
    const payoutId = htmlPayout.substring(positionStart + 9, htmlPayout.indexOf("&", positionStart));
    return groupId + "::" + payoutId;
  },
  setIds : function() {
    const selectedRows = dataTable.getSelectedRows();
    let ids = "";
    for (let selectedRow of selectedRows) {
      ids += inputLocal.setId({selectedRow: selectedRow}) + ", ";
    }
    ids = ids.substring(0, ids.length - 2);
    document.querySelector("#ids").value = ids;
  },
  validate : function() {
    const group = document.querySelectorAll("[id^='groupId_']");
    const payout = document.querySelectorAll("[id^='payoutId_']");
    if (group.length > 0) {
      group[0].setCustomValidity(group[0].value == "" ? "You must select a group" : "");
      payout[0].setCustomValidity(payout[0].value == "" ? "You must select a payout" : "");
    }
  }
};
let documentReadyCallback = () => {
  inputLocal.initializeDataTable();
  inputLocal.validate();
  input.storePreviousValue({selectors: ["[id^='payoutId_'], [id^='groupId']"]});
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
  inputLocal.validate();
});
document.addEventListener("click", (event) => {
  inputLocal.validate();
  if (event.target && event.target.id.includes("reset")) {
    input.restorePreviousValue({selectors: ["[id^='payoutId_'], [id^='groupId_']"]});
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