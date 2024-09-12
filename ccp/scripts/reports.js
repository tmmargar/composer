"use strict";
import { dataTable, display, input } from "./import.js";
export const reportsInputLocal = {
  initializeDataTable : function() {
    let dataTableId = null;
    document.querySelectorAll(".reportId2").forEach(obj => {
      const reportId = obj.value;
      if (reportId == "results") {
        dataTableId = "dataTblTournamentResults";
        if (document.querySelector("#" + dataTableId)) {
          dataTable.initialize({tableId: dataTableId, aryColumns: [null, { "type" : "name" }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }, { "type" : "name" }, { "searchable": false, "visible": false }], aryOrder: [[0, "asc" ]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
        }
      } else if (reportId == "pointsTotal") {
        dataTableId = "dataTblTotalPoints";
        if (document.querySelector("#" + dataTableId)) {
          dataTable.initialize({tableId: dataTableId, aryColumns: [{ "type" : "name" }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }], aryOrder: [[1, "desc"], [0, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
        }
      } else if (reportId == "earnings") {
        dataTableId = "dataTblEarnings";
        if (document.querySelector("#" + dataTableId)) {
          dataTable.initialize({tableId: dataTableId, aryColumns: [{ "type" : "name" }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }], aryOrder: [[1, "desc"], [0, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
        }
      } else if (reportId == "earningsChampionship") {
        dataTableId = "dataTblEarnings\\(Championship\\)";
        if (document.querySelector("#" + dataTableId)) {
          dataTable.initialize({tableId: dataTableId, aryColumns: [{ "type" : "name" }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }], aryOrder: [[1, "desc"], [0, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
        }
      } else if (reportId == "knockouts") {
        dataTableId = "dataTblKnockouts";
        if (document.querySelector("#" + dataTableId)) {
          dataTable.initialize({tableId: dataTableId, aryColumns: [{ "type" : "name" }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }], aryOrder: [[1, "desc"], [0, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
        }
      } else if (reportId == "summary") {
        dataTableId = "dataTblSummary";
        if (document.querySelector("#" + dataTableId)) {
          dataTable.initialize({tableId: dataTableId, aryColumns: [{ "type" : "name" }, { "orderSequence": [ "desc", "asc" ], "render" : function (data, type, row, meta) { if (type === "display") { return display.formatNeeded({value: data, meta: meta, tableId: "dataTblSummary"}); } else { return data; } } }, { "visible": false }, { "visible": false }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }, {}, {}, {}, {}, {}, {}, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }], aryOrder: [[14, "desc"], [0, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
        }
      } else if (reportId == "winners") {
        dataTableId = "dataTblWinners";
        if (document.querySelector("#" + dataTableId)) {
          dataTable.initialize({tableId: dataTableId, aryColumns: [{ "type" : "name" }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }], aryOrder: [[1, "desc"], [0, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
        }
      } else if (reportId == "finishes") {
        dataTableId = "dataTblFinishes";
        if (document.querySelector("#" + dataTableId)) {
          dataTable.initialize({tableId: dataTableId, aryColumns: [null, { "orderSequence": [ "desc", "asc" ], }, { "orderSequence": [ "desc", "asc" ] }], aryOrder: [], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
        }
      } else if (reportId == "championship") {
        dataTableId = "dataTblChampionship";
        const params = new URLSearchParams(window.location.search);
        // website blocking desc in parameter so passing up and down and replacing here
        const paramsSort = params.get("sort").replaceAll("up", "asc").replaceAll("down", "desc");
        const aryParam = paramsSort.split(",");
        let aryNew = [];
        aryParam.forEach(function(item, index, array) {
          const aryItem = item.split(" ");
          aryNew[index] = params.get("group") ? parseInt(aryItem) - 1 : aryItem;
        });
        let aryCols = [];
        let colIndex = 0;
        if (!params.get("group")) {
          aryCols[colIndex] = null;
          colIndex++;
        }
        aryCols[colIndex] = {"type" : "name"};
        colIndex++;
        aryCols[colIndex] = null;
        const aryRowGroup = params.get("group") ? null : {
	        startRender: null,
	        endRender: function ( rows, group ) {
	          const nf = new Intl.NumberFormat();
	          const earningsSum = rows.data().pluck(2).reduce( function (a, b) { return parseInt(a.toString().replace(/[$,]/g, '')) + parseInt(b.toString().replace(/[$,]/g, '')); }, 0);
	          const earningsSumFormatted = rows.data().pluck(2).reduce( function (a, b) { return "$" + nf.format(parseInt(a.toString().replace(/[$,]/g, '')) + parseInt(b.toString().replace(/[$,]/g, ''))); }, 0);
	          const objRow = document.createElement("tr");
	          const objColumn = document.createElement("td");
	          objColumn.classList.add("bold");
	          objColumn.colSpan = 2;
	          objColumn.innerHTML = "Earnings for " + group;
	          objRow.appendChild(objColumn);
	          const objColumn2 = document.createElement("td");
	          objColumn2.classList.add("number");
	          objColumn2.classList.add("positive");
	          objColumn2.innerHTML = earningsSumFormatted;
	          objColumn2.style.fontWeight = "900";
	          objRow.appendChild(objColumn2);
	          const objColumn3 = document.createElement("td");
	          objColumn3.classList.add("rowGroupSum");
	          objColumn3.style.display = "none";
	          objColumn3.innerHTML = earningsSum;
	          objRow.appendChild(objColumn3);
	          return objRow;
	        },
	        dataSrc: aryNew[0][0]
	      };
	      dataTable.initialize({tableId: dataTableId, aryColumns: aryCols, aryOrder: aryNew, aryRowGroup: aryRowGroup, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
      } else if (reportId == "fees") {
        dataTableId = "dataTblFees";
        if (document.querySelector("#" + dataTableId)) {
          dataTable.initialize({tableId: dataTableId, aryColumns: [null, null, null, null], aryOrder: [[1, "desc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
          //dataTable.initialize({tableId: "dataTblFeeDetail", aryColumns: [{visible: false}, {visible: false}, { "type": "name" }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ] }, { "orderSequence": [ "desc", "asc" ], visible: false }, null], aryOrder: [[5, "desc"], [2, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "600px", searching: true });
          const aryCols = [{visible: false}, {sortable: false}, {type : 'name'}, {sortable: false}, {sortable: false}, {sortable: false}, {sortable: false}];
          const aryNew = [['2', 'asc']];
          //aryCols = [null, type=name, null], aryNew = [[0, desc], [2, down]] 
          const aryRowGroup = {
            startRender: null,
            endRender: function ( rows, group ) {
              const nf = new Intl.NumberFormat();
              const feesPaidSumFormatted = rows.data().pluck(5).reduce( function (a, b) { return "$" + nf.format(parseInt(a.toString().replace(/[$,]/g, '')) + parseInt(b.toString().replace(/[$,]/g, ''))); }, 0);
              const feesTotalSumFormatted = rows.data().pluck(4).reduce( function (a, b) { return b; }, 0);
              const feesBalanceSumFormatted = rows.data().pluck(6).reduce( function (a, b) { return "$" + nf.format(parseInt(a.toString().replace(/[$,]/g, '')) + parseInt(b.toString().replace(/[$,]/g, ''))); }, 0);
              const objRow = document.createElement("tr");
              const objColumn = document.createElement("td");
              objColumn.classList.add("bold");
              objColumn.colSpan = 3;
              objColumn.innerHTML = "Fees for " + group;
              objRow.appendChild(objColumn);
              const objColumn2 = document.createElement("td");
              objColumn2.classList.add("number");
              objColumn2.classList.add("positive");
              objColumn2.innerHTML = feesTotalSumFormatted;
              objColumn2.style.fontWeight = "900";
              objRow.appendChild(objColumn2);
              const objColumn3 = document.createElement("td");
              objColumn3.classList.add("number");
              objColumn3.classList.add("positive");
              objColumn3.innerHTML = feesPaidSumFormatted;
              objColumn3.style.fontWeight = "900";
              objRow.appendChild(objColumn3);
              const objColumn4 = document.createElement("td");
              objColumn4.classList.add("number");
              objColumn4.classList.add("positive");
              objColumn4.innerHTML = feesBalanceSumFormatted;
              objColumn4.style.fontWeight = "900";
              objRow.appendChild(objColumn4);
              /*const objColumn5 = document.createElement("td");
              objColumn5.classList.add("rowGroupSum");
              objColumn5.style.display = "none";
              objColumn5.innerHTML = feesSum;
              objRow.appendChild(objColumn5);*/
              return objRow;
            },
            dataSrc: aryNew[0][0]
          };
          dataTable.initialize({tableId: "dataTblFeeDetail", aryColumns: aryCols, aryOrder: aryNew, aryRowGroup: aryRowGroup, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "500px", searching: true });
          var table = $("#dataTblFeeDetail").DataTable();
          table.on('column-sizing', function () {
            table.draw();
          });
        }
      }
    });
    return dataTableId;
  },
  showFeeDetail : function(seasonId) {
    // search matches entire value
    const regex = '\\b' + seasonId + '\\b';
    $("#dataTblFeeDetail").DataTable().columns(0).search(regex, true, false).draw();
    const rowsData = $("#dataTblFeeDetail").DataTable().rows({search:'applied'}).data();
    document.querySelector("#dialogFeeDetailSpan").innerText = " - " + rowsData[0][0] + " " + rowsData[0][4];
    document.querySelectorAll("#dataTblFeeDetail tr.odd, #dataTblFeeDetail tr.even").forEach(row => {
      row.querySelector("td:nth-child(4)").innerHTML = ""; // remove fee
      row.querySelector("td:nth-child(6)").innerHTML = ""; // remove balance
    });
    input.showDialog({name: "FeeDetail"});
  },
  buildQueryString : function() {
    const queryString = new URLSearchParams(window.location.search);
    let action = [];
    for (let qs of queryString) {
        if ("season" != qs[0] && "championship" != qs[0]) {
            action.push(qs.join("="));
        }
    }
    action.push("season=" + document.querySelector("#season").value);
    if (document.querySelector("#championship") && document.querySelector("#championship").checked) {
        action.push("championship=" + document.querySelector("#championship").value);
    }
    return action;
  },
  championshipCount : function(tableId) {
      if (document.querySelector("#season").value != "ALL") {
        let qualified = 0;
        let eligible = 0;
        document.querySelectorAll("#" + tableId + " tbody tr td:nth-of-type(2)").forEach(cell => {
            if (cell.classList.contains("championshipQualified")) {
                qualified++;
            } else if (cell.classList.contains("championshipEligible")) {
                eligible++;
            }
        });
        document.querySelector(".dataTables_scrollHeadInner thead tr:nth-of-type(1) th:nth-of-type(2)").innerHTML += "<br>" + qualified + "q / " + eligible + "e";
        $("#dataTblSummary").DataTable().draw();
      }
  }
};
let documentReadyCallback = () => {
  reportsInputLocal.initializeDataTable();
  reportsInputLocal.championshipCount("dataTblSummary");
};
if (document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll)) {
  documentReadyCallback();
} else {
  document.addEventListener("DOMContentLoaded", documentReadyCallback);
}
document.addEventListener("change", (event) => {
  if (event.target && event.target.id.includes("season")) {
    let action = reportsInputLocal.buildQueryString();
    document.querySelector("#frmReports").setAttribute("action", document.URL.split('?')[0] + "?" + action.join("&"));
    document.querySelector("#frmReports").submit();
  }
});
document.addEventListener("click", (event) => {
    if (event.target && event.target.id.includes("championship")) {
      let action = reportsInputLocal.buildQueryString();
      document.querySelector("#frmReports").setAttribute("action", document.URL.split('?')[0] + "?" + action.join("&"));
      document.querySelector("#frmReports").submit();
    }
});