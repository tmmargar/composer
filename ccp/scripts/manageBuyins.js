"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  buildData : function({objTableId, mode} = {}) {
    const objPlayers = document.querySelector("#ids");
    const objFeePaid = document.querySelector("#feePaid");
    const objAllPaid = document.querySelector("#buyinPaid");
    const objAllRebuy = document.querySelector("#rebuyPaid");
    const objAllRebuyCount = document.querySelector("#rebuyCount");
    const objAllAddon = document.querySelector("#addonPaid");
    objPlayers.value = "";
    objFeePaid.value = "";
    objAllPaid.value = "";
    objAllRebuy.value = "";
    objAllRebuyCount.value = "";
    objAllAddon.value = "";
    // if mode is create or modify then build list of player ids for fee, paid, rebuy, addon
    if (("create" == mode) || ("modify" == mode)) {
      // for each table row except header
      Array.from(document.querySelectorAll("#" + objTableId + " tr")).slice("1").forEach(row => {
        const aryInput = $("#dataTbl").DataTable().row(row).data();
        const placeholder = document.createElement("div");
        placeholder.innerHTML = aryInput[aryInput.length - 1];
        const playerId = placeholder.firstElementChild.value;
        objFeePaid.value = objFeePaid.value + (0 < objFeePaid.value.length ? ", " : "") + (document.querySelector("#feePaid_" + playerId).disabled || (parseInt(document.querySelector("#feePaid_" + playerId).dataset.originalValue) == parseInt(document.querySelector("#feePaid_" + playerId).value)) ? " " : document.querySelector("#feePaid_" + playerId).value);
        objAllPaid.value = objAllPaid.value + (0 < objAllPaid.value.length ? ", " : "") + document.querySelector("#buyin_" + playerId).checked;
        objAllRebuy.value = objAllRebuy.value + (0 < objAllRebuy.value.length ? ", " : "") + document.querySelector("#rebuy_" + playerId).checked;
        objAllRebuyCount.value = objAllRebuyCount.value + (0 < objAllRebuyCount.value.length ? ", " : "") + document.querySelector("#rebuyCount_" + playerId).value;
        objAllAddon.value = objAllAddon.value + (0 < objAllAddon.value.length ? ", " : "") + document.querySelector("#addon_" + playerId).checked;
        objPlayers.value = objPlayers.value + (0 < objPlayers.value.length ? ", " : "") + playerId;
      });
    }
  },
  calculateFeePaid : function() {
    // fee paid amount
    let feePaidTotal = 0;
    let feePaidTotalCalculation = "(";
    document.querySelectorAll("[id^='feePaid_']")?.forEach(obj => {
      // only check enabled
      if (!obj.disabled) {
        //feePaidTotal += obj.value - obj.dataset.originalValue;
        feePaidTotal += parseInt(obj.value);
        feePaidTotalCalculation += (feePaidTotalCalculation != "(" ? " + " : "") + "$" + obj.value;
      }
    });
    feePaidTotalCalculation += ")";
    if (document.querySelector("#totalSeasonFee")) {
      document.querySelector("#totalSeasonFee").value = parseInt(document.querySelector("#totalSeasonFee").value) + feePaidTotal;
      document.querySelector("#feePaidTotal").innerHTML = "$" + feePaidTotal;
      document.querySelector("#feePaidTotalCalculation").innerHTML = feePaidTotalCalculation;
    }
  },
  disableCheckboxAll : function({hasFlag, name, countNotCheckedPaid} = {}) {
    // if need to check flag and flag is set or no need to check flag (0 for rebuy and "" for addon)
    if ((hasFlag && document.querySelector("#" + name + "Flag").value != "0" && document.querySelector("#" + name + "Flag").value != "") || !hasFlag) {
      // if checkbox count is same as count passed in then disable check all checkbox
      document.querySelector("#" + name + "CheckAll").disabled = document.querySelectorAll('[id^="' + name + '_"]').length == countNotCheckedPaid;
    }
  },
  disableCheckboxes : function({hasFlag, obj, name, id} = {}) {
    // if need to check flag and flag is set or no need to check flag then enable/disable appropriately (rebuy flag is 0 for no rebuy, addon is blank for no addon) 
    if ((hasFlag && document.querySelector("#" + name + "Flag").value != "0" && document.querySelector("#" + name + "Flag").value != "") || !hasFlag) {
      document.querySelector("#" + name + "_" + id).disabled = !obj.checked;
    }
  },
  initializeDataTable : function() {
    dataTable.initialize({tableId: "dataTbl", aryColumns: [{ "type" : "name", "width" : "30%" }, {"width": "16%"}, { "orderable": false, "searchable": false, "width" : "12%" }, { "orderable": false, "searchable": false, "width" : "12%" }, { "orderable": false, "searchable": false, "width" : "18%" }, { "orderable": false, "searchable": false, "width" : "12%" }, { "searchable": false, "visible": false }], aryOrder: [[0, "asc" ]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "400px", searching: false });
  },
  markCheckboxes : function({hasFlag, obj, name, id} = {}) {
    // if need to check flag and flag is set or no need to check flag then mark checkbox and check check all checkbox appropriately
    if ((hasFlag && document.querySelector("#" + name + "Flag").value != "0") || !hasFlag) {
      document.querySelector("#" + name + "CheckAll").checked = obj.checked;
      document.querySelector("#" + name + "_" + id).checked = obj.checked;
    }
  },
  postProcessing : function() {
    let countNotCheckedPaid = 0;
    // fee paid amount
    document.querySelectorAll("[id^='feePaid_']")?.forEach(obj => {
      obj.disabled = document.querySelector("#seasonFee").value == obj.value ? true : false;
      obj.style.width = "35px";
      obj.min = 0;
      obj.max = 30;
      obj.dataset.originalValue = obj.value;
      inputLocal.validate(obj);
    });
    // for each paid checkbox
    document.querySelectorAll("[id^='buyin_']")?.forEach(obj => {
      // parse out number from id to use for other objects 
      const id = obj.id;
      const values = id.split("_");
      // if paid checkbox is not checked
      if (!obj.checked) {
        inputLocal.markCheckboxes({hasFlag: true, obj: obj, name: "rebuy", id: values[1]});
        inputLocal.markCheckboxes({hasFlag: true, obj: obj, name: "addon", id: values[1]});
      }
      inputLocal.disableCheckboxes({hasFlag: true, obj: obj, name: "rebuy", id: values[1]});
      inputLocal.disableCheckboxes({hasFlag: true, obj: obj, name: "addon", id: values[1]});
      // count how many are not checked
      if (!obj.checked) {
        countNotCheckedPaid++;
      }
    });
    inputLocal.processAllCheckAll({countNotCheckedPaid: countNotCheckedPaid});
    input.enableView();
    document.querySelectorAll("[id^='rebuy_']")?.forEach(obj => {
      const id = obj.id;
      const values = id.split("_");
      document.querySelector("#rebuyCount_" + values[1]).disabled = !obj.checked;
      if (document.querySelector("#rebuyCount_" + values[1]).disabled) {
        document.querySelector("#rebuyCount_" + values[1]).value = 0;
      }
    });
    input.countUpdate({prefix: "buyin"});
    input.countUpdate({prefix: "rebuy", prefixAdditional: "rebuyCount"});
    input.countUpdate({prefix: "addon"});
    if (document.querySelector("#mode")) {
      document.querySelector("#mode").value = "modify";
    }
    inputLocal.calculateFeePaid();
  },
  processAllCheckAll : function({countNotCheckedPaid} = {}) {
    input.toggleCheckAll({id: "buyin", idAll: "buyin"});
    input.toggleCheckAll({id: "rebuy", idAll: "rebuy"});
    input.toggleCheckAll({id: "addon", idAll: "addon"});
    inputLocal.disableCheckboxAll({hasFlag: true, name: "rebuy", countNotCheckedPaid: countNotCheckedPaid});
    inputLocal.disableCheckboxAll({hasFlag: true, name: "addon", countNotCheckedPaid: countNotCheckedPaid});
  },
  setDefaults : function() {
    input.insertSelectedBefore({objIdSelected: "tournamentId", objIdAfter: "mode", width: "90%"});
  },
  setIds : function() {
    let ids = "";
    document.querySelectorAll("[id^='buyin_']")?.forEach(obj => {
      const id = obj.id.split("_");
      ids += id[1] + ", ";
    });
    ids = ids.substring(0, ids.length - 2);
    document.querySelector("#ids").value = ids;
  },
  setMinMax : function() {
    if (document.querySelector("[id^='rebuyCount_']")) {
      document.querySelectorAll("[id^='rebuyCount_']")?.forEach(obj => {
        obj.min = 0;
        obj.max = document.querySelector("#rebuyFlag").value;
      });
    }
  },
  tableRowClick : function({obj} = {}) {
    obj.classList.remove("selected");
  },
  toggleRebuy : function({checked} = {}) {
    document.querySelectorAll("[id^='rebuy_']")?.forEach(obj => {
      if (!obj.disabled) {
        const id = obj.id
        const values = id.split("_");
        obj.checked = checked;
        document.querySelector("#rebuyCount_" + values[1]).disabled = !checked;
        if (0 == document.querySelector("#rebuyCount_" + values[1]).value) {
          document.querySelector("#rebuyCount_" + values[1]).value = 1;
        }
        if (!checked) {
          document.querySelector("#rebuyCount_" + values[1]).value = 0;
        }
      }
    });
  },
  validate : function(objFeePaid) {
    objFeePaid.setCustomValidity(objFeePaid.validity.valueMissing ? "You must enter an amount " : objFeePaid.validity.rangeUnderflow ? "You must enter an amount >= " + objFeePaid.min : objFeePaid.validity.rangeOverflow ? "You must enter an amount <= " + objFeePaid.max : "");
  },
  validateField : function({obj, event} = {}) {
    input.validateNumberOnly({obj: obj, event: event, storeValue: false});
    if (obj.value == "" || parseInt(obj.value) > parseInt(document.querySelector("#rebuyFlag").value)) {
      obj.value = obj.dataset.previousValueValidation;
    } else {
      const id = obj.id;
      const values = id.split("_");
      obj.disabled = (obj.value == 0);
      document.querySelector("#rebuy_" + values[1]).checked = !(obj.value == 0);
      input.countUpdate({prefix: "rebuy", prefixAdditional: "rebuyCount"});
    }
  }
};
let documentReadyCallback = () => {
  inputLocal.initializeDataTable();
  inputLocal.setMinMax();
  inputLocal.setDefaults();
  inputLocal.postProcessing();
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
  inputLocal.tableRowClick({obj: row});
}));
document.addEventListener("click", (event) => {
  if (event.target && event.target.id.includes("buyinCheckAll")) {
    const id = event.target.id.substring(0, event.target.id.indexOf("CheckAll"));
    input.toggleCheckboxes({id: id, idAll: id});
    input.countUpdate({prefix: id, prefixAdditional: id + "Count"});
    inputLocal.postProcessing();
  } else if (event.target && event.target.id.includes("rebuyCheckAll")) {
    inputLocal.toggleRebuy({checked: event.target.checked});
    const id = event.target.id.substring(0, event.target.id.indexOf("CheckAll"));
    input.toggleCheckboxes({id: id, idAll: id});
    input.countUpdate({prefix: id, prefixAdditional: id + "Count"});
  } else if (event.target && event.target.id.includes("addonCheckAll")) {
    const id = event.target.id.substring(0, event.target.id.indexOf("CheckAll"));
    input.toggleCheckboxes({id: id, idAll: id});
    input.countUpdate({prefix: id, prefixAdditional: id + "Count"});
  } else if (event.target && event.target.id.includes("buyin")) {
    const id = event.target.id.substring(0, event.target.id.indexOf("_"));
    input.toggleCheckAll({id: id, idAll: id});
    inputLocal.postProcessing();
  } else if (event.target && event.target.id.includes("rebuy_")) {
    const id = event.target.id.substring(0, event.target.id.indexOf("_"));
    input.toggleCheckAll({id: id, idAll: id});
    const values = event.target.id.split("_");
    document.querySelector("#rebuyCount_" + values[1]).disabled = !event.target.checked;
    document.querySelector("#rebuyCount_" + values[1]).value = (event.target.checked ? 1 : 0);
    input.countUpdate({prefix: "rebuy", prefixAdditional: "rebuyCount"});
    document.querySelector("#rebuyCount_" + values[1]).dataset.previousValueValidation = document.querySelector("#rebuyCount_" + values[1]).value;
  } else if (event.target && event.target.id.includes("addon")) {
    input.toggleCheckAll({id: "addon", idAll: "addon"});
    input.countUpdate({prefix: "addon"});
  } else if (event.target && event.target.id.includes("reset")) {
    input.restorePreviousValue({selectors: ["[id^='notificationStartDate_']", "[id^='notificationEndDate_']"]});
    inputLocal.validate(event.target);
  } else if (event.target && event.target.id.includes("save")) {
    inputLocal.setIds();
  }
});
document.addEventListener("input", (event) => {
  inputLocal.validate(event.target);
});
document.addEventListener("keyup", (event) => {
  if (event.target && event.target.id.includes("rebuyCount")) {
    inputLocal.validateField({obj: event.target, event: event});
    event.target.dataset.previousValueValidation = event.target.value;
  }
});
document.addEventListener("paste", (event) => {
  if (event.target && event.target.id.includes("rebuyCount")) {
    inputLocal.validateField({obj: event.target, event: event});
    event.target.dataset.previousValueValidation = event.target.value;
  }
});
