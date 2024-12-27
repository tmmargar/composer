"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  addRow : function({objId} = {}) {
    let newId;
    // clone last row and adjust index by 1
    const rowLast = document.querySelector("#rowTotal").previousElementSibling;
    const newRow = rowLast.cloneNode(true);
    newRow.id = "";
    const aryInputs = ["input", "textarea", "select", "button"];
    aryInputs.forEach(input => {
      newRow.querySelectorAll(input).forEach(obj => {
        obj.disabled = (obj.id.indexOf("place_") == -1) ? false : true;
        const idVal = obj.id.split("_");
        newId = parseInt(idVal[2]) + 1;
        obj.id = idVal[0] + "_" + idVal[1] + "_" + newId;
        const nameVal = obj.name.split("_");
        obj.name = nameVal[0] + "_" + nameVal[1] + "_" + (parseInt(nameVal[2]) + 1);
        obj.checked = false;
        obj.value = (obj.id.indexOf("place_") == -1) ? 0 : parseInt(obj.value) + 1;
        obj.dataset.previousValueValidation = obj.value;
      });
    });
    const rowEnd = document.querySelector("#rowTotal");
    rowEnd.parentNode.insertBefore(newRow, rowEnd);
  },
  calculateTotal : function(objId) {
    let total = 0;
    document.querySelectorAll("[id^='percentage_']").forEach(obj => {
      if (obj.value == "") {
        obj.value = 0;
      }
      total += parseInt(obj.value);
    });
    if (100 < total) {
      if (undefined != objId) {
        document.querySelector("#" + objId).value = document.querySelector("#" + objId).dataset.previousValueValidation;
      }
    } else {
      document.querySelector("#percentageTotal").value = total;
      if (undefined != objId) {
        document.querySelector("#" + objId).dataset.previousValueValidation = document.querySelector("#" + objId).value;
      }
    }
  },
  enableButtons : function() {
    if ("create" == document.querySelector("#mode").value || "modify" == document.querySelector("#mode").value) {
      document.querySelectorAll('[id^="addRow"]').forEach(obj => {obj.disabled = !document.querySelectorAll("#inputs tbody tr").length == 2; });
      document.querySelectorAll('[id^="removeRow"]').forEach(obj => {obj.disabled = document.querySelectorAll("#inputs tbody tr").length == 2; });
    }
  },
  initializeDataTable : function() {
    const aryRowGroup = {
      startRender: null,
      endRender: function ( rows, group, level ) {
        const minPlayers = undefined == rows.data().pluck(2)[0] ? 0 : rows.data().pluck(2)[0];
        const maxPlayers = undefined == rows.data().pluck(3)[0] ? 0 : rows.data().pluck(3)[0];
        const objRow = document.createElement("tr");
        const objColumn = document.createElement("td");
        objColumn.colSpan = 4;
        objColumn.innerHTML = "Payout for " + group + " has min players " + minPlayers + " and max players " + maxPlayers;
        objRow.appendChild(objColumn);
        return objRow;
      },
      dataSrc: 1
    };
    dataTable.initialize({tableId: "dataTbl", aryColumns: [{"orderSequence": [ "desc", "asc" ], "width" : "9%" }, { "width" : "26%" }, { "width" : "14%", "visible": false }, { "width" : "14%", "visible": false }, { "width" : "11%" }, { "type" : "percentage", "width" : "12%" }, { "searchable": false, "visible": false }], aryOrder: [[1, "asc"], [4, "asc"]], aryRowGroup: aryRowGroup, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "400px", searching: true });
  },
  postProcessing : function() {
    if ("create" == document.querySelector("#mode").value || "modify" == document.querySelector("#mode").value) {
      document.querySelector("[id^='payoutName_']").dataset.previousValueValidation = document.querySelector("[id^='payoutName_']").value;
      document.querySelector("[id^='minPlayers_']").dataset.previousValueValidation = document.querySelector("[id^='minPlayers_']").value;
      document.querySelector("[id^='maxPlayers_']").dataset.previousValueValidation = document.querySelector("[id^='maxPlayers_']").value;
      document.querySelectorAll("#inputs tbody tr").forEach(obj => {
        obj.querySelectorAll("[id^='percentage']").forEach(obj2 => { obj2.dataset.previousValueValidation = obj2.value });
      });
      document.querySelector("#inputs tbody tr:nth-last-child(2)").id = "rowLast";
    }
  },
  removeRow : function(objId) {
    document.querySelector("#" + objId + " tr:nth-last-child(2)").remove();
    inputLocal.calculateTotal();
  },
  reset : function() {
    const aryId = document.querySelector("#rowLast").querySelector("[id^='percentage_']").id.split("_");
    document.querySelectorAll("#inputs tbody tr").forEach(obj => {
      const aryIdCurrent = obj.querySelector("[id^='percentage_']")?.id.split("_");
      if (undefined != aryIdCurrent && aryIdCurrent[2] > aryId[2]) {
        obj.remove();
      }
    });
  },
  save : function() {
    document.querySelectorAll("[id^='place_']").forEach(obj => {
      obj.disabled = false;
    });
  },
  setId : function({selectedRow} = {}) {
    return selectedRow.children[0].innerHTML;
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
  setMinMax : function() {
    if (document.querySelector("[id^='minPlayers_']")) {
      document.querySelector("[id^='minPlayers_']").min = 1;
      if (document.querySelector("[id^='maxPlayers_']:valid")) {
        document.querySelector("[id^='minPlayers_']").max = parseInt(document.querySelector("[id^='maxPlayers_']").value) - 1;
      } else {
        document.querySelector("[id^='minPlayers_']").removeAttribute("max");
      }
    }
    if (document.querySelector("[id^='maxPlayers_']")) {
      document.querySelector("[id^='maxPlayers_']").max = 999;
      if (document.querySelector("[id^='minPlayers_']:valid")) {
        document.querySelector("[id^='maxPlayers_']").min = parseInt(document.querySelector("[id^='minPlayers_']").value) + 1;
      } else {
        document.querySelector("[id^='maxPlayers_']").removeAttribute("min");
      }
    }
    if (document.querySelector("[id^='percentage_']")) {
      document.querySelectorAll("[id^='percentage_']").forEach(obj => {
        obj.min = 1;
        obj.max = 100;
      });
    }
  },
  setWidth : function() {
    if (document.querySelector("[id^='minPlayers_']")) {
      document.querySelector("[id^='minPlayers_']").style.width = "50px";
    }
    if (document.querySelector("[id^='maxPlayers_']")) {
      document.querySelector("[id^='maxPlayers_']").style.width = "50px";
    }
    document.querySelectorAll("[id^='place_']").forEach(obj => {
      obj.style.width = "50px";
    });
    document.querySelectorAll("[id^='percentage_']").forEach(obj => {
      obj.style.width = "50px";
    });
  },
  validate : function() {
    const name = document.querySelectorAll("[id^='payoutName_']");
    const minPlayers = document.querySelectorAll("[id^='minPlayers_']");
    const maxPlayers = document.querySelectorAll("[id^='maxPlayers_']");
    if (name.length > 0) {
      name[0].setCustomValidity(name[0].validity.valueMissing ? "You must enter a name" : "");
      minPlayers[0].setCustomValidity(minPlayers[0].validity.valueMissing ? "You must enter a #" : minPlayers[0].validity.rangeUnderflow ? "You must enter a # >= " + minPlayers[0].min : minPlayers[0].validity.rangeOverflow ? "You must enter a # <= " + minPlayers[0].max : "");
      maxPlayers[0].setCustomValidity(maxPlayers[0].validity.valueMissing ? "You must enter a #" : maxPlayers[0].validity.rangeUnderflow ? "You must enter a # >= " + maxPlayers[0].min : maxPlayers[0].validity.rangeOverflow ? "You must enter a # <= " + maxPlayers[0].max : "");
    }
  },
  validateTotal : function(event) {
    if (document.querySelector("#mode").value.startsWith("save")) {
      if (document.querySelector("#percentageTotal").value != 100) {
        display.showErrors({errors: ["You must enter percentages that add up to 100"]});
        event.preventDefault();
        event.stopPropagation();
        document.querySelector("#mode").value = document.querySelector("#mode").value.replace("save", "");
      } else {
        display.clearErrorsAndMessages();
      }
    }
  }
};
let documentReadyCallback = () => {
  if (document.querySelector("#mode").value != "create" || document.querySelector("#mode").value != "modify") {
    document.querySelector("body").style.maxWidth = "500px";
  }
  inputLocal.initializeDataTable();
  inputLocal.setMinMax();
  inputLocal.validate();
  inputLocal.postProcessing();
  inputLocal.setWidth();
  input.storePreviousValue({selectors: ["[id^='payoutName_'], [id^='minPlayers_'], [id^='maxPlayers_']"]});
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
  inputLocal.validateTotal(event);
  if (event.target && event.target.id.includes("addRow")) {
    inputLocal.addRow({objId: "inputs"});
  } else if (event.target && event.target.id.includes("removeRow")) {
    inputLocal.removeRow("inputs");
  } else if (event.target && event.target.id.includes("save")) {
    inputLocal.save();
  } else if (event.target && event.target.id.includes("reset")) {
    inputLocal.reset();
    input.restorePreviousValue({selectors: ["[id^='payoutName_'], [id^='minPlayers_'], [id^='maxPlayers_']"]});
  } else if (event.target && (event.target.id.includes("modify") || event.target.id.includes("delete"))) {
    inputLocal.setIds();
  }
});
document.addEventListener("input", (event) => {
  inputLocal.validate();
  inputLocal.validateTotal(event);
  if (event.target && (event.target.id.includes("minPlayers") || event.target.id.includes("maxPlayers"))) {
    inputLocal.setMinMax();
  } else if (event.target && event.target.id.includes("percentage")) {
    inputLocal.calculateTotal(event.target.id);
  }
});