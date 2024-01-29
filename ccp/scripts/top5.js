"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  initializeDataTable : function() {
    document.querySelectorAll(".reportId").forEach(obj => { 
      const reportId = obj.value;
      if ("pointsTotalForSeason" == reportId || "pointsAverageForSeason" == reportId) {
        //tableId, aryColumns = null, aryOrder = [], searching = true, aryRowGroup = false, scrollY = "", autoWidth = false, paging = false, scrollResize = true, scrollCollapse = true) {
        dataTable.initialize({tableId: "dataTbl" + input.ucwords({value: reportId}), aryColumns: [{ "type" : "name", "width": "70%" }, { "orderSequence": [ "desc", "asc" ], "width": "30%" }], aryOrder: [[1, "desc"], [0, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
      } else if ("earningsTotalForSeason" == reportId || "earningsAverageForSeason" == reportId) {
        dataTable.initialize({tableId: "dataTbl" + input.ucwords({value: reportId}), aryColumns: [{ "type" : "name", "width": "70%" }, { "orderSequence": [ "desc", "asc" ], "width": "30%" }], aryOrder: [[1, "desc"], [0, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
      } else if ("knockoutsTotalForSeason" == reportId || "knockoutsAverageForSeason" == reportId) {
        dataTable.initialize({tableId: "dataTbl" + input.ucwords({value: reportId}), aryColumns: [{ "type" : "name", "width": "75%" }, { "orderSequence": [ "desc", "asc" ], "width": "25%" }], aryOrder: [[1, "desc"], [0, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
      } else if ("winnersForSeason" == reportId) {
        dataTable.initialize({tableId: "dataTbl" + input.ucwords({value: reportId}), aryColumns: [{ "type" : "name", "width": "70%" }, { "orderSequence": [ "desc", "asc" ], "width": "30%" }], aryOrder: [[1, "desc"], [0, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
      } else if ("finishesForPlayer" == reportId) {
        dataTable.initialize({tableId: "dataTbl" + input.ucwords({value: reportId}), aryColumns: [{ "width": "30%" }, { "orderSequence": [ "desc", "asc" ], "width": "40%" }, { "orderSequence": [ "desc", "asc" ], "width": "30%" }], aryOrder: [[1, "desc"], [0, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
      } else if ("tournamentsPlayedByTypeForPlayer" == reportId) {
        dataTable.initialize({tableId: "dataTbl" + input.ucwords({value: reportId}), aryColumns: [{ "width": "25%" }, { "width": "25%" }, { "orderSequence": [ "desc", "asc" ], "width": "17%" }, { "orderSequence": [ "desc", "asc" ], "width": "18%" }, { "orderSequence": [ "desc", "asc" ], "width": "15%" }], aryOrder: [[4, "desc"], [1, "asc"], [0, "asc"], [2, "desc"], [3, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
      } else if ("nemesisForPlayer" == reportId) {
        //tableSelector, aryColumns = null, aryOrder = [], searching = true, aryRowGroup = false, scrollY = "", autoWidth = false, paging = false, scrollResize = true, scrollCollapse = true) {
        dataTable.initializeBySelector({tableSelector: "table[id*='Nemesis']", aryColumns: [{ "type" : "name", }, { "orderSequence": [ "desc", "asc" ] }], aryOrder: [[1, "desc"], [0, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
      } else if ("bullyForPlayer" == reportId) {
        dataTable.initializeBySelector({tableSelector: "table[id*='Bully']", aryColumns: [{ "type" : "name" }, { "orderSequence": [ "desc", "asc" ] }], aryOrder: [[1, "desc"], [0, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
      }
    });
    document.querySelectorAll("table[id^='dataTblRank']").forEach(obj => {
      const tableId = obj.id;
      if ("dataTblRankLifetimeTourneys" == tableId) {
        dataTable.initialize({tableId: tableId, aryColumns: [null, { "type" : "name" }, { "orderSequence": [ "desc", "asc" ] }], aryOrder: [[0, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
      } else {
        const dialogId = tableId.replace("dataTblRank", "dialogRankAll");
        let reportId = document.querySelector("#" + dialogId)?.nextElementSibling?.value;
        if (undefined == reportId) {
          reportId = document.querySelector("#" + dialogId)?.parentElement?.nextElementSibling?.value;
        }
        dataTable.initialize({tableId: tableId, aryColumns: [{ }, { "type" : "name" }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ],  }], aryOrder: [[0, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
      }
    });
  },
  rebuildTableForDialog : function({dialogName, tableName} = {}) {
    // if dialog exists
    if (document.querySelector("#dialogRankAll" + dialogName)) {
      // 5 data rows
      if (document.querySelectorAll("#dataTbl" + tableName + " tbody tr").length > 5) {
        // remove all rows except first 5
        document.querySelectorAll("#dataTbl" + tableName + " tbody tr").forEach((row, index) => { if (index > 4) row.remove(); });
      }
    }
  },
  showFullList : function({title, userFullName} = {}) {
    const re = /\s/gi;
    dataTable.displayHighlightRow({tableId: "#dataTblRank" + title.replace(re, ""), value: userFullName});
    input.showDialogWithWidth({name: "RankAll" + title.replace(re, "")});
  },
  showFullListBullies : function() {
    input.showDialog({name: "RankAllBullies"});
  },
  showFullListLocationsHosted : function() {
    input.showDialogWithWidth({name: "LocationsHosted"});
  },
  showFullListNemesis : function() {
    input.showDialog({name: "RankAllNemesis"});
  }
};
let documentReadyCallback = () => {
  inputLocal.rebuildTableForDialog({dialogName: "Nemesis", tableName: "Nemesis"});
  inputLocal.rebuildTableForDialog({dialogName: "Bullies", tableName: "Bully"});
  inputLocal.rebuildTableForDialog({dialogName: "LocationsHosted"});
  inputLocal.initializeDataTable();
};
if (document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll)) {
  documentReadyCallback();
} else {
  document.addEventListener("DOMContentLoaded", documentReadyCallback);
}