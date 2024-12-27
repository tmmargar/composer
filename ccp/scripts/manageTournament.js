"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  confirmAction : function() {
    let result = true;
    if (document.querySelector("#tournamentResultsExist").value == "1") {
      result = confirm("There are results entered for this tournament. Do you want to modify?");
    }
    return result;
  },
  defaultDescription : function() {
    return "S" + document.querySelector("#seasonMaxId").value + " - T" + (parseInt(document.querySelector("#countTournament").value) + 1);
  },
  initializeDataTable : function() {
    dataTable.initialize({tableId: "dataTbl", aryColumns: [{ "orderSequence": [ "desc", "asc" ], "width": "3%" },
      { "width": "12%",
        className : "textAlignUnset",
        render: function (data, type, row) {
          // row[2] is comment, row[18] is special type
          if (type === 'display') {
            const titleText = "" != row[2] ? row[2] + ("" != row[18] ? " - " + row[18] : "") : row[18];
            const title = " title=\"" + titleText + "\"";
            const specialType = "" != row[18] ? " (" + row[18] + ")" : "";
            return "<span" + title + ">" + data + specialType + "</span>";
          }
          return data;
        }
      },
      { "orderable": false, "visible": false, "width": "11%" }, { className : "textAlignUnset", "width": "11%" }, { "width": "7%" },
      { "width": "5%" }, { "orderable": false, "width": "1%" }, { "type": "date", "width": "6%" }, { "width": "4%" }, { "width": "5%" },
      { "width": "4%" }, { "width": "4%" }, { "width": "4%" }, { "width": "4%" }, { "width": "4%" },
      { "width": "4%" }, { "width": "4%" }, { "width": "4%" }, { "orderable": false, "visible": false, "width": "3%" }, { "orderable": false, "visible": false }], aryOrder: [[7, "desc"], [8, "desc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "400px", searching: true });
  },
  setDefaults : function() {
    if (document.querySelector("#mode").value == "create") {
      document.querySelector("[id^='tournamentDescription_']").value = inputLocal.defaultDescription();
      document.querySelector("[id^='tournamentLimitTypeId_']").value = 3;
      document.querySelector("[id^='tournamentGameTypeId_']").value = 1;
      document.querySelector("[id^='tournamentChipCount_']").value = 10000;
      document.querySelector("[id^='tournamentStartDateTime_']").value = new Date().toISOString().split("T")[0] + " 17:00";
      document.querySelector("[id^='tournamentMaxPlayers_']").value = 36;
      document.querySelector("[id^='tournamentBuyinAmount_']").value = 25;
      document.querySelector("[id^='tournamentRebuys_']").value = 0;
      document.querySelector("[id^='tournamentRebuyAmount_']").value = 0;
      document.querySelector("[id^='tournamentRebuys_']").value = 0;
      document.querySelector("[id^='tournamentAddonAmount_']").value = 10;
      document.querySelector("[id^='tournamentAddonChipCount_']").value = 1500;
      document.querySelector("[id^='tournamentRake_']").value = 20;
      document.querySelector("[id^='tournamentGroupId_']").value = 2;
    }
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
    if (document.querySelector("[id^='tournamentChipCount_']")) {
      document.querySelector("[id^='tournamentChipCount_']").min = document.querySelector("[id^='tournamentSpecialTypeId_']").options[document.querySelector("[id^='tournamentSpecialTypeId_']").selectedIndex].text == input.championshipText() ? 0 : 1;
      document.querySelector("[id^='tournamentChipCount_']").max = 999999;
    }
    if (document.querySelector("[id^='tournamentBuyinAmount_']")) {
      document.querySelector("[id^='tournamentBuyinAmount_']").min = document.querySelector("[id^='tournamentSpecialTypeId_']").options[document.querySelector("[id^='tournamentSpecialTypeId_']").selectedIndex].text == input.championshipText() ? 0 : 1;
      document.querySelector("[id^='tournamentBuyinAmount_']").max = 999;
    }
    if (document.querySelector("[id^='tournamentMaxPlayers_']")) {
      document.querySelector("[id^='tournamentMaxPlayers_']").min = 1;
      document.querySelector("[id^='tournamentMaxPlayers_']").max = 99;
    }
    if (document.querySelector("[id^='tournamentRebuyAmount_']")) {
      document.querySelector("[id^='tournamentRebuyAmount_']").min = 0;
      document.querySelector("[id^='tournamentRebuyAmount_']").max = 999;
    }
    if (document.querySelector("[id^='tournamentRebuys_']")) {
      document.querySelector("[id^='tournamentRebuys_']").min = 0;
      document.querySelector("[id^='tournamentRebuys_']").max = 99;
    }
    if (document.querySelector("[id^='tournamentAddonAmount_']")) {
      document.querySelector("[id^='tournamentAddonAmount_']").min = 0;
      document.querySelector("[id^='tournamentAddonAmount_']").max = 999;
    }
    if (document.querySelector("[id^='tournamentAddonChipCount_']")) {
      document.querySelector("[id^='tournamentAddonChipCount_']").min = 0;
      document.querySelector("[id^='tournamentAddonChipCount_']").max = 999999;
    }
    if (document.querySelector("[id^='tournamentRake_']")) {
      document.querySelector("[id^='tournamentRake_']").min = document.querySelector("[id^='tournamentSpecialTypeId_']").options[document.querySelector("[id^='tournamentSpecialTypeId_']").selectedIndex].text == input.championshipText() ? 0 : 1;
      document.querySelector("[id^='tournamentRake_']").max = 99;
    }
  },
  validate : function() {
    const description = document.querySelectorAll("[id^='tournamentDescription_']");
    const chipCount = document.querySelectorAll("[id^='tournamentChipCount_']");
    const buyinAmount = document.querySelectorAll("[id^='tournamentBuyinAmount_']");
    const maxPlayers = document.querySelectorAll("[id^='tournamentMaxPlayers_']");
    const rebuyAmount = document.querySelectorAll("[id^='tournamentRebuyAmount_']");
    const rebuys = document.querySelectorAll("[id^='tournamentRebuys_']");
    const addonAmount = document.querySelectorAll("[id^='tournamentAddonAmount_']");
    const addonChipCount = document.querySelectorAll("[id^='tournamentAddonChipCount_']");
    const rake = document.querySelectorAll("[id^='tournamentRake_']");
    if (description.length > 0) {
      description[0].setCustomValidity(description[0].validity.valueMissing ? "You must enter a description" : "");
      const limitType = document.querySelectorAll("[id^='tournamentLimitTypeId_']");
      const gameType = document.querySelectorAll("[id^='tournamentGameTypeId_']");
      limitType[0].setCustomValidity(limitType[0].value == "" ? "You must select a limit type" : "");
      gameType[0].setCustomValidity(gameType[0].value == "" ? "You must select a game type" : "");
      chipCount[0].setCustomValidity(chipCount[0].validity.valueMissing ? "You must enter a chip count" : chipCount[0].validity.rangeUnderflow ? "You must enter a chip count >= " + chipCount[0].min : chipCount[0].validity.rangeOverflow ? "You must enter a chip count <= " + chipCount[0].max : "");
      const location = document.querySelectorAll("[id^='tournamentLocationId_']");
      location[0].setCustomValidity(location[0].value == "" ? "You must select a location" : "");
      buyinAmount[0].setCustomValidity(buyinAmount[0].validity.valueMissing ? "You must enter a buyin amount" : buyinAmount[0].validity.rangeUnderflow ? "You must enter a buyin amount >= " + buyinAmount[0].min : buyinAmount[0].validity.rangeOverflow ? "You must enter a buyin amount <= " + buyinAmount[0].max : "");
      maxPlayers[0].setCustomValidity(maxPlayers[0].validity.valueMissing ? "You must enter the # of players" : maxPlayers[0].validity.rangeUnderflow ? "You must enter the # of players >= " + maxPlayers[0].min : maxPlayers[0].validity.rangeOverflow ? "You must enter the # of players <= " + maxPlayers[0].max : "");
      rebuyAmount[0].setCustomValidity(rebuyAmount[0].validity.valueMissing ? "You must enter a rebuy amount" : rebuyAmount[0].validity.rangeUnderflow ? "You must enter a rebuy amount >= " + rebuyAmount[0].min : rebuyAmount[0].validity.rangeOverflow ? "You must enter a rebuy amount <= " + rebuyAmount[0].max : "");
      rebuys[0].setCustomValidity(rebuys[0].validity.valueMissing ? "You must enter the max rebuys" : rebuys[0].validity.rangeUnderflow ? "You must enter the max rebuys >= " + rebuys[0].min : rebuys[0].validity.rangeOverflow ? "You must enter the max rebuys <= " + rebuys[0].max : "");
      if ((rebuyAmount[0].value == 0 && rebuys[0].value != 0) || (rebuyAmount[0].value != 0 && rebuys[0].value == 0)) {
        rebuyAmount[0].setCustomValidity("You must enter both a rebuy amount and the max # of rebuys or neither");
        rebuys[0].setCustomValidity("You must enter both a rebuy amount and the max # of rebuys or neither");
      }
      addonAmount[0].setCustomValidity(addonAmount[0].validity.valueMissing ? "You must enter an addon amount" : addonAmount[0].validity.rangeUnderflow ? "You must enter an addon amount >= " + addonAmount[0].min : addonAmount[0].validity.rangeOverflow ? "You must enter an addon amount <= " + addonAmount[0].max : "");
      addonChipCount[0].setCustomValidity(addonChipCount[0].validity.valueMissing ? "You must enter an addon chip count" : addonChipCount[0].validity.rangeUnderflow ? "You must enter an addon chip count >= " + addonChipCount[0].min : addonChipCount[0].validity.rangeOverflow ? "You must enter an addon chip count <= " + addonChipCount[0].max : "");
      if ((addonAmount[0].value == 0 && addonChipCount[0].value != 0) || (addonAmount[0].value != 0 && addonChipCount[0].value == 0)) {
        addonAmount[0].setCustomValidity("You must enter both a rebuy amount and the max # of rebuys or neither");
        addonChipCount[0].setCustomValidity("You must enter both an addon amount and the addon chip count or neither");
      }
      const group = document.querySelectorAll("[id^='tournamentGroupId_']");
      group[0].setCustomValidity(group[0].value == "" ? "You must select a group" : "");
      rake[0].setCustomValidity(rake[0].validity.valueMissing ? "You must enter a rake" : rake[0].validity.rangeUnderflow ? "You must enter a rake >= " + rake[0].min : rake[0].validity.rangeOverflow ? "You must enter a rake <= " + rake[0].max : "");
    }
  }
};
let documentReadyCallback = () => {
  if (document.querySelector("#mode").value == "create" || document.querySelector("#mode").value == "modify") {
    document.querySelector("body").style.maxWidth = "600px";
  }
  inputLocal.initializeDataTable();
  inputLocal.setMinMax();
  inputLocal.setDefaults();
  inputLocal.validate();
  input.storePreviousValue({selectors: ["[id^='tournamentDescription_']", "[id^='tournamentComment_']", "[id^='tournamentLimitTypeId_']", "[id^='tournamentGameTypeId_']", "[id^='tournamentSpecialTypeId_']", "[id^='tournamentLocationId_']", "[id^='tournamentStartDateTime_']", "[id^='tournamentBuyinAmount_']", "[id^='tournamentMaxPlayers_']", "[id^='tournamentRebuyAmount_']", "[id^='tournamentRebuys_']", "[id^='tournamentAddonAmount_']", "[id^='tournamentAddonChipCount_']", "[id^='tournamentGroupId_']"]});
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
  if (event.target && event.target.id.includes("tournamentSpecialTypeId")) {
    inputLocal.setMinMax();
    inputLocal.validate();
  }
});
document.addEventListener("click", (event) => {
  inputLocal.validate();
  if (event.target && event.target.id.includes("reset")) {
    input.restorePreviousValue({selectors: ["[id^='tournamentDescription_']", "[id^='tournamentComment_']", "[id^='tournamentLimitTypeId_']", "[id^='tournamentGameTypeId_']", "[id^='tournamentSpecialTypeId_']", "[id^='tournamentLocationId_']", "[id^='tournamentStartDateTime_']", "[id^='tournamentBuyinAmount_']", "[id^='tournamentMaxPlayers_']", "[id^='tournamentRebuyAmount_']", "[id^='tournamentRebuys_']", "[id^='tournamentAddonAmount_']", "[id^='tournamentAddonChipCount_']", "[id^='tournamentGroupId_']"]});
  } else if (event.target && (event.target.id.includes("modify") || event.target.id.includes("delete"))) {
    inputLocal.setIds();
  } else if (event.target && (event.target.id.includes("save") || event.target.id.includes("confirmDelete"))) {
    if (!inputLocal.confirmAction()) {
      event.preventDefault();
      event.stopPropagation();
      document.querySelector("#mode").value = document.querySelector("#mode").value.replace("save", "");
    }
  }
});
document.addEventListener("input", (event) => {
  inputLocal.validate();
  if (event.target && event.target.classList.contains("timePicker")) {
    inputLocal.setMinMax();
  }
});