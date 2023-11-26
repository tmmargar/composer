"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  buildName : function({id} = {}) {
    const objPlayer = document.querySelector("#playerId_" + id);
    document.querySelector("#locationName_" + id).value = document.querySelector("#city_" + id).value + " - " + objPlayer.options[objPlayer.selectedIndex].innerText.split(" ")[1];
    input.validateLength({obj: document.querySelector("#locationName_" + id), length: 1, focus: false});
  },
  initializeDataTable : function() {
    dataTable.initialize({tableId: "dataTbl", aryColumns: [{"orderSequence": [ "desc", "asc" ], "width" : "2%" }, { "width" : "19%" }, { "type" : "host", "width" : "15%" }, { "searchable": false, "width" : "21%" }, { "searchable": false, "width" : "11%" }, { "searchable": false, "width" : "5%" }, { "searchable": false, "width" : "4%" }, { "render" : function (data, type, row, meta) { return display.formatActive({value: data, meta: meta, tableId: "dataTbl"}); },  "width" : "7%" }, { "width" : "7%" }, { "searchable": false, "visible": false }], aryOrder: [[7, "desc"], [2, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "400px", searching: false });
  },
  save : function() {
    document.querySelectorAll("[id^='states_']")?.forEach(obj => { obj.disabled = false; });
    return true;
  },
  setDefaults : function() {
    if (document.querySelector("#mode").value == "create") {
      document.querySelector("#states_").value = "MI";
    }
    document.querySelectorAll("[id^='states_']")?.forEach(obj => { obj.disabled = true; });
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
    if (document.querySelector("[id^='zipCode_']")) {
      document.querySelector("[id^='zipCode_']").min = 10000;
      document.querySelector("[id^='zipCode_']").max = 99999;
    }
  },
  setWidth : function() {
    if (document.querySelector("[id^='zipCode_']")) {
      document.querySelector("[id^='zipCode_']").style.width = "70px";
    }
  },
  tableRowClick : function(row) {
    document.querySelectorAll("[id^='delete']")?.forEach(obj => { obj.disabled = !(row.querySelector("td:nth-of-type(9)").innerText == 0); });
  },
  validate : function() {
    const player = document.querySelectorAll("[id^='playerId_']");
    const address = document.querySelectorAll("[id^='address_']");
    const city = document.querySelectorAll("[id^='city_']");
    const zip = document.querySelectorAll("[id^='zipCode_']");
    if (player.length > 0) {
      player[0].setCustomValidity(player[0].options[player[0].selectedIndex].value == "" ? "You must select a player" : "");
      address[0].setCustomValidity(address[0].validity.valueMissing ? "You must enter an address" : "");
      city[0].setCustomValidity(city[0].validity.valueMissing ? "You must enter a city" : "");
      zip[0].setCustomValidity(zip[0].validity.valueMissing ? "You must enter a zip" : zip[0].validity.rangeUnderflow ? "You must enter a zip <= " + zip[0].max : zip[0].validity.rangeOverflow ? "You must enter a zip >= " + zip[0].min : "");
    }
  }
};
let documentReadyCallback = () => {
  inputLocal.initializeDataTable();
  inputLocal.setMinMax();
  inputLocal.setDefaults();
  inputLocal.setWidth();
  inputLocal.validate();
  document.querySelectorAll("[id^='locationName_']")?.forEach(obj => { obj.tabindex = -1; });
  document.querySelector("[id^='playerId_']")?.focus();
  input.storePreviousValue({selectors: ["[id^='locationName_']", "[id^='playerId_']", "[id^='address_']", "[id^='city_']", "[id^='states_']", "[id^='zipCode']", "[id^='active']"]});
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
  inputLocal.tableRowClick(row, selected);
}));
document.addEventListener("change", (event) => {
  inputLocal.validate();
  if (event.target && event.target.id.includes("playerId")) {
    inputLocal.buildName({id: document.querySelector("#ids").value.split(", ")[0]});
  } else if (event.target && event.target.id.includes("city")) {
    inputLocal.buildName({id: document.querySelector("#ids").value.split(", ")[0]});
  }
});
document.addEventListener("click", (event) => {
  inputLocal.validate();
  if (event.target && event.target.id.includes("reset")) {
    input.restorePreviousValue({selectors: ["[id^='locationName_']", "[id^='playerId_']", "[id^='address_']", "[id^='city_']", "[id^='states_']", "[id^='zipCode']", "[id^='active']"]});
    inputLocal.validate();
  } else if (event.target && (event.target.id.includes("modify") || event.target.id.includes("delete"))) {
    inputLocal.setIds();
  } else if (event.target && event.target.id.includes("save")) {
    inputLocal.save();
  }
});
document.addEventListener("input", (event) => {
  inputLocal.validate();
});