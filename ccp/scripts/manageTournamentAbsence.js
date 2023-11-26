"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  initializeDataTable : function() {
    dataTable.initialize({tableId: "dataTbl", aryColumns: [{ "type" : "name" }, null, {"searchable": false, "visible": false }], aryOrder: [[1, "desc"], [0, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "400px", searching: false });
  },
  postProcessing : function() {
    input.enableView();
  },
  setDefaults : function() {
    input.insertSelectedBefore({objIdSelected: "tournamentId", objIdAfter: "mode", width: "85%"});
  },
  setPlayerIds : function() {
    let playerIds = "";
    let statuses = "";
    const selectedRows = dataTable.getSelectedRowsData({jQueryTableApi: $("#dataTbl").DataTable()});
    for (let idx = 0; idx < selectedRows.length; idx++) {
      const selectedRow = selectedRows[idx];
      const inp = document.createElement("div");
      inp.innerHTML = selectedRow[2];
      playerIds += inp.children[0].value + ", ";
      inp.remove();
      statuses += selectedRow[1] + ", ";
    }
    document.querySelector("#ids").value = playerIds.substring(0, playerIds.length - 2);
    document.querySelector("#tournamentPlayerStatus").value = statuses.substring(0, statuses.length - 2);
  },
  tableRowClick : function({row, selected} = {}) {
    if (selected) {
      row.classList.remove("selected");
    } else {
      row.classList.add("selected");
    }
  }
};
let documentReadyCallback = () => {
  inputLocal.initializeDataTable();
  inputLocal.setDefaults();
  inputLocal.postProcessing();
};
if (document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll)) {
  documentReadyCallback();
} else {
  document.addEventListener("DOMContentLoaded", documentReadyCallback);
}
document.querySelectorAll("#dataTbl tbody tr")?.forEach(row => row.addEventListener("click", (event) => {
  inputLocal.tableRowClick({row: row, selected: row.classList.contains("selected")});
}));
document.addEventListener("click", (event) => {
  if (event.target && event.target.id.includes("attend")) {
    const selectedRows = dataTable.getSelectedRows({jQueryTable: $("#dataTbl").dataTable()});
    if (selectedRows.length == 0) {
      display.showErrors({errors: [ "You must select a row to attend / un-attend" ]});
      event.preventDefault();
      event.stopPropagation();
    } else {
      display.clearErrorsAndMessages();
      inputLocal.setPlayerIds();
      document.querySelector("#mode").value = "modify";
    }
  } else {
    display.clearErrorsAndMessages();
  }
});