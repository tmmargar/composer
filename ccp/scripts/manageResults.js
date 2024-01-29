"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  addRow : function(objId) {
    let newId;
    const rowLast = document.querySelector("#" + objId + " tbody tr:last-of-type");
    if (rowLast.querySelector("[id^='tournamentPlace_']").value > 1) {
      // clone last row and adjust index by 1
      const newRow = rowLast.cloneNode(true);
      input.classToggle({element: newRow, aryArguments: ["odd", "even"]});
      const aryInputs = ["input", "textarea", "select", "button"];
      aryInputs.forEach(input => {
        newRow.querySelectorAll(input).forEach(obj => {
          obj.disabled = (obj.id.indexOf("tournamentRebuyCount_") != -1) ? true : obj.disabled;
          const idVal = obj.id.split("_");
          newId = parseInt(idVal[1]) + 1;
          obj.id = idVal[0] + "_" + newId;
          const nameVal = obj.name.split("_");
          obj.name = nameVal[0] + "_" + (parseInt(nameVal[1]) + 1);
          obj.checked = false;
          obj.value = (obj.id.indexOf("tournamentPlace_") != -1) ? obj.value - 1 : (obj.value == 0 || obj.id.indexOf("tournamentRebuyCount_") != -1) ? 0 : "";
          obj.dataset.previousValueValidation = null;
        });
      });
      rowLast.parentElement.append(newRow);
      // need this in order for selected value to display properly on new row
      newRow.querySelectorAll("select").forEach(obj => {
        obj.classList.add("errors");
        obj.querySelector("option:first-of-type").selected = true;
      });
      // disable previous row player
      const index = document.querySelector("#tournamentPlayerId_" + (newId - 1)).selectedIndex;
      // if not first item which is None
      if (0 < index) {
        document.querySelector("#tournamentPlayerId_" + newId + " option:nth-of-type(" + (index + 1) + ")").disabled = true;
        document.querySelector("#tournamentKnockoutBy_" + newId + " option:nth-of-type(" + (index + 1) + ")").disabled = true;
      }
      // disable all players if last row (1st place)
      const obj = rowLast.querySelectorAll("[id^='tournamentKnockoutBy_']");
      // should only be 1
      const id = obj[0].id;
      const idValues = id.split("_");
      if (1 == document.querySelector("#tournamentPlace_" + idValues[1]).value) {
        obj.querySelector("option").forEach(obj2 => {
          if (index > 0) {
            obj2.disabled = true;
          }
        });
        obj.removeClass("errors");
      }
      inputLocal.buildRowEvents(parseInt(idValues[1]) + 1);
    }
  },
  buildRowEvents : function(id) {
    if (undefined == id) {
      id = "";
    }
    // TODO: move all these back to jquery otherwise duplicate events
    //document.querySelectorAll("[id^='tournamentPlayerId_']")?.forEach(e => e.addEventListener("change", function(event) { inputLocal.validatePlayer(e); }));
    document.querySelectorAll("[id^='tournamentRebuy_" + id + "']")?.forEach(e => e.addEventListener("click", function(event) {
      const idLocal = e.id.split("_");
      document.querySelectorAll("[id^='tournamentRebuyCount_" + idLocal[1] + "']")?.forEach(e2 => {
        e2.dataset.previousValueValidation = e2.value;
        e2.disabled = !e.checked;
        e2.value = (e.checked ? 1 : 0);
      });
    }));
  },
  customValidation : function(objId, delim, index) {
    // player value is id, rebuyPaid, rebuyCount, addonPaid (100::N::0::N)
    // id::rebuy count::addon amount (100:1:0)
    const values = document.querySelector("#" + objId).value.split(delim);
    const objRebuy = document.querySelector("#" + "tournamentRebuy_" + index);
    const objRebuyCount = document.querySelector("#" + "tournamentRebuyCount_" + index);
    const objAddon = document.querySelector("#" + "tournamentAddon_" + index);
    if (values[1] == "Y") {
      objRebuy.checked = true;
      objRebuyCount.disabled = false;
      objRebuyCount.value = values[2];
      objRebuyCount.dataset.previousValueValidation = values[2];
    } else {
      objRebuy.checked = false;
      objRebuyCount.value = 0;
      objRebuyCount.dataset.previousValueValidation = 0;
      if (document.querySelector("#tournamentId option:checked").value.split("::")[1] == 0) {
        objRebuy.disabled = true;
        objRebuyCount.disabled = true;
      }
    }
    if (values[3] == "Y") {
      objAddon.checked = true;
    } else {
      objAddon.checked = false;
      if (document.querySelector("#tournamentId option:checked").value.split("::")[2] == 0) {
        objAddon.disabled = true;
      }
    }
  },
  delete : function(event) {
    inputLocal.setIds();
    return false;
  },
  enableButtons : function() {
    // if no tournament selected disable view, modify and delete buttons otherwise enable them
    const result = document.querySelector("#tournamentId").value == "0";
    if (document.querySelectorAll("#view").length > 0) {
      document.querySelector("#view").disabled = result;
    }
    document.querySelectorAll("[id^='delete']").forEach(row => { row.disabled = result; });
    if (document.querySelectorAll("#go").length > 0) {
      document.querySelector("#go").disabled = result;
    }
    if (result) {
      document.querySelectorAll("[id^='update']").forEach(row => { row.disabled = result; });
    } else {
      const text = document.querySelector("#tournamentId option:checked").innerText;
      const position = text.lastIndexOf("/") + 1;
      document.querySelectorAll("[id^='update']").forEach(obj => { obj.disabled = text.substring(position, position + 1) == 0; });
    }
    // if last row place is 1 then disable add row otherwise enable it
    document.querySelectorAll("[id^='addRow']").forEach(obj => { obj.disabled = document.querySelector("#inputs tbody tr:last-of-type").querySelector("[id^='tournamentPlace_']").value == 1; });
    // if only 2 rows (header + 1 data) then disable remove row otherwise enable it
    document.querySelectorAll("[id^='removeRow']").forEach(obj => { obj.disabled = document.querySelectorAll("#inputs tr").length == 2; });
    let disabled = false;
    document.querySelectorAll("[id^='tournamentPlayerId_']").forEach(obj => {
      if ("" == obj.value) {
        disabled = true;
      }
    });
    if (!disabled) {
      document.querySelectorAll("[id^='tournamentKnockoutBy_']").forEach(obj => {
        const id = obj.id.split("_");
        if (1 < document.querySelector("#tournamentPlace_" + id[1]).value) {
          if ("" == obj.value) {
            disabled = true;
          }
        }
      });
    }
    document.querySelectorAll("[id^='save']").forEach(obj => { obj.disabled = disabled; });
    return disabled;
  },
  enableSave : function() {
    return inputLocal.enableButtons();
  },
  initializeDataTable : function() {
      if (document.querySelector("#mode").value == "view" || document.querySelector("#mode").value == "delete") {
        dataTable.initialize({tableId: "dataTbl", aryColumns: [{ "orderable": false, "type" : "name", "width" : "30%" }, { "orderable": false, "width" : "10%" }, { "orderable": false, "width" : "10%" }, { "orderable": false, "width" : "10%" }, { "orderable": false, "width" : "10%" }, { "orderable": false, "type" : "name", "width" : "30%" }, { "searchable": false, "visible": false }], aryOrder: [[4, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "400px", searching: false });
      } else if (document.querySelector("#mode").value == "create" || document.querySelector("#mode").value == "modify") {
        dataTable.initialize({tableId: "inputs", aryColumns: null, aryOrder: [], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "", searching: false });
      }
  },
  postProcessing : function() {
    document.querySelectorAll("[id^='tournamentRebuy_']").forEach(obj => {
      obj.disabled = document.querySelector("#maxRebuys").value == 0;
      if (document.querySelector("#mode").value == "create") {
        obj.checked = false;
      }
    });
    document.querySelectorAll("[id^='tournamentRebuyCount_']").forEach(obj => {
      obj.disabled = document.querySelector("#maxRebuys").value == 0;
      if (document.querySelector("#mode").value == "create") {
        obj.value = "0";
      }
      obj.min = 0;
      obj.max = document.querySelector("#maxRebuys").value;
    });
    document.querySelectorAll("[id^='tournamentAddon_']").forEach(obj => {
      obj.disabled = document.querySelector("#addonAmount").value == 0;
      if (document.querySelector("#mode").value == "create") {
        obj.checked = false;
      }
    });
    document.querySelectorAll("[id^='tournamentPlayerId_']").forEach(obj => {
      const aryPlayerId = obj.id.split("_");
      const playerValue = obj.value;
      if ("" != playerValue) {
        // cannot knockout yourself
        document.querySelectorAll("#tournamentKnockoutBy_" + aryPlayerId[1] + " option").forEach(obj2 => {
          if (playerValue == obj2.value) {
            obj2.disabled = true;
          }
        });
      }
      // can only select player once
      document.querySelectorAll("[id^='tournamentPlayerId_']").forEach(obj3 => {
        const aryPlayerId2 = obj3.id.split("_");
        if (aryPlayerId[1] != aryPlayerId2[1]) {
          document.querySelectorAll("#" + obj3.id + " option").forEach(obj4 => {
            if (playerValue == obj4.value) {
              obj4.disabled = true;
            }
          });
        }
      });
      // can only select knockout once
      document.querySelectorAll("[id^='tournamentKnockoutBy_']").forEach(obj5 => {
        const aryKnockoutId = obj5.id.split("_");
        if (parseInt(aryPlayerId[1]) < parseInt(aryKnockoutId[1])) {
          document.querySelectorAll("#" + obj5.id + " option").forEach(obj6 => {
            if (playerValue == obj6.value) {
              obj6.disabled = true;
            }
          });
        }
      });
    });
    document.querySelectorAll("[id^='tournamentPlayerId_']").forEach(obj => {
      obj.dataset.previousValueValidation = obj.selectedIndex;
    });
    document.querySelectorAll("[id^='tournamentRebuy_']").forEach(obj => {
      const id = obj.id;
      const values = id.split("_");
      document.querySelector("#tournamentRebuyCount_" + values[1]).disabled = !obj.checked;
    });
    document.querySelectorAll("[id^='tournamentRebuyCount_']").forEach(obj => {
      obj.dataset.previousValueValidation = obj.value;
    });
  },
  removeRow : function(objId) {
    document.querySelector("#" + objId + " tbody tr:last-of-type").remove();
  },
  save : function() {
    inputLocal.setIds();
    document.querySelectorAll("[id^='tournamentRebuy_']").forEach(obj => { obj.disabled = false; });
    document.querySelectorAll("[id^='tournamentRebuyCount_']").forEach(obj => { obj.disabled = false; });
    document.querySelectorAll("[id^='tournamentAddon_']").forEach(obj => { obj.disabled = false; });
    document.querySelectorAll("[id^='tournamentPlace_']").forEach(obj => { obj.disabled = false; });
  },
  setDefaults : function() {
    inputLocal.setTournamentDetails(document.querySelector("#tournamentId").value);
    input.insertSelectedBefore({objIdSelected: "tournamentId", objIdAfter: "mode", width: "88%"});
  },
  setId : function(selectedRow) {
    return selectedRow.children[0].innerHTML;
  },
  setIds : function() {
    let ids = "";
    document.querySelectorAll("[id^='tournamentPlayerId_']").forEach(obj => { ids += obj.value + ", "; });
    ids = ids.substring(0, ids.length - 2);
    document.querySelector("#ids").value = ids;
  },
  setTournamentDetails : function(value) {
    // id::rebuy count::addon amount (100:1:0)
    if (value != undefined) {
      const aryId = value.split("::");
      if (aryId.length > 1) {
        document.querySelector("#maxRebuys").value = aryId[1];
        document.querySelector("#addonAmount").value = aryId[2];
      }
    }
  },
  tableRowClick : function(obj) {
    obj.classList.remove("selected");
  },
  update : function(event) {
    inputLocal.setIds();
    return false;
  },
  validate : function() {
    input.validateLength({obj: document.querySelector("#tournamentId"), length: 1, focus: false});
    input.validateLength({obj: document.querySelector("#tournamentPlayerId_1"), length: 1, focus: false});
    input.validateLength({obj: document.querySelector("#tournamentKnockoutBy_1"), length: 1, focus: false});
    inputLocal.enableButtons();
  },
  validateField : function(obj, event) {
    input.validateNumberOnlyLessThanEqualToValue({obj: event.target, event: event, value: document.querySelector("#maxRebuys").value});
    const id = obj.id.split("_");
    if (obj.value == "") {
      obj.value = obj.dataset.previousValueValidation;
    } else {
      obj.disabled = (obj.value == 0);
      document.querySelector("#tournamentRebuy_" + id[1]).checked = !(obj.value == 0);
      obj.dataset.previousValueValidation = obj.value;
    }
  },
  validateField2 : function(obj) {
    const id = obj.id.split("_");
    if (1 < document.querySelector("#tournamentPlace_" + id[1]).value) {
      input.validateLength({obj: obj, length: 1, focus: false});
    }
    inputLocal.enableButtons();
  },
  validatePlayer(objIn) {
    const aryPlayerId = objIn.id.split("_");
    inputLocal.customValidation(objIn.id, '::', aryPlayerId[1]);
    input.validateLength({obj: objIn, length: 1, focus: false});
    const playerValue = objIn.value;
    const previousValue = objIn.dataset.previousValueValidation;
    // can only select player once
    document.querySelectorAll("[id^='tournamentPlayerId_']").forEach(obj => {
      const aryPlayerId2 = obj.id.split("_");
      if (aryPlayerId[1] < aryPlayerId2[1]) {
        document.querySelectorAll("#" + obj.id + " option").forEach(obj2 => {
          if ("" != playerValue) {
            if (playerValue == obj2.value) {
              obj2.disabled = true;
            }
          } else {
            // none selected so clear ko
            document.querySelector("#tournamentKnockoutBy_" + aryPlayerId[1]).selectedIndex = -1;
            document.querySelector("#tournamentKnockoutBy_" + aryPlayerId[1]).value = "";
          }
          // have to enable previous disabled player
          if (obj2.index == previousValue) {
            obj2.disabled = false;
          }
        });
      }
    });
    document.querySelectorAll("[id^='tournamentKnockoutBy_']").forEach(obj => {
      const aryPlayerId2 = obj.id.split("_");
      if (parseInt(aryPlayerId[1]) <= parseInt(aryPlayerId2[1])) {
        const value = obj.value;
        document.querySelectorAll("#" + obj.id + " option").forEach(obj2 => {
          if (playerValue == obj2.value) {
            obj2.disabled = true;
            if (value == obj2.value) {
              obj.value = "";
              obj.dispatchEvent(new Event("change"));
            }
          }
          // have to enable previous disabled player
          if (obj2.index == previousValue) {
            obj2.disabled = false;
          }
        });
      }
    });
    objIn.dataset.previousValueValidation = objIn.selectedIndex;
    input.validateLength({obj: objIn, length: 1, focus: false});
    inputLocal.enableButtons();
  }
};
let documentReadyCallback = () => {
    if (document.querySelector("#mode")) {
        inputLocal.initializeDataTable();
        inputLocal.setDefaults();
        inputLocal.validate();
        input.enable({objId: "save", functionName: inputLocal.enableSave});
        inputLocal.postProcessing();
        inputLocal.buildRowEvents();
    }
};
if (document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll)) {
  documentReadyCallback();
} else {
  document.addEventListener("DOMContentLoaded", documentReadyCallback);
}
document.querySelectorAll("#dataTbl tbody tr")?.forEach(row => row.addEventListener("click", (event) => { inputLocal.tableRowClick(row); }));
document.addEventListener("change", (event) => {
  if (event.target && event.target.id.includes("tournamentId")) {
    inputLocal.setTournamentDetails(event.target.value);
    input.validateLength({obj: event.target, length: 1, focus: true});
    inputLocal.enableButtons();
  } else if (event.target && event.target.id.includes("tournamentPlayerId")) {
    inputLocal.validatePlayer(event.target);
  } else if (event.target && event.target.id.includes("tournamentKnockoutBy")) {
    inputLocal.validateField2(event.target);
  }
});
document.addEventListener("click", (event) => {
  if (event.target && event.target.id.includes("create")) {
    document.querySelector("#tournamentId").value = 0;
  } else if (event.target && event.target.id.includes("addRow")) {
    inputLocal.addRow("inputs");
    inputLocal.enableButtons();
  } else if (event.target && event.target.id.includes("removeRow")) {
    inputLocal.removeRow("inputs");
    inputLocal.enableButtons();
  } else if (event.target && event.target.id.includes("delete")) {
    document.querySelector("#mode").value = event.target.value.toLowerCase();
  } else if (event.target && event.target.id.includes("save")) {
    inputLocal.save();
  } else if (event.target && event.target.id.includes("reset")) {
    document.querySelector("#go").click();
  } else if (event.target && event.target.id.includes("update")) {
    inputLocal.update(event.target);
    document.querySelector("#mode").value = "modify";
  }
});
document.addEventListener("keyup", (event) => {
  if (event.target && event.target.id.includes("tournamentRebuyCount")) {
    inputLocal.validateField(event.target, event);
  }
});
document.addEventListener("paste", (event) => {
  if (event.target && event.target.id.includes("tournamentRebuyCount")) {
    inputLocal.validateField(event.target, event);
  }
});