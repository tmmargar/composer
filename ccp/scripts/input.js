"use script";
import { display } from "./display.js";
export const input = {
  championshipText : function() {
    return "Championship";
  },
  changeState : function({checkId, aryChangeId} = {}) {
    const objCheck = document.querySelector("#" + checkId);
    for (let changeId of aryChangeId) {
      if ("" != changeId) {
        const objChange = document.querySelector("#" + changeId);
        objChange.disabled = !objCheck.checked;
        if (!objCheck.checked) {
          objChange.checked = false;
        }
      }
    }
  },
  checkboxToggle : function({inputId, checked} = {}) {
    document.querySelectorAll('[id^="' + inputId + '"]')?.forEach(obj => { obj.checked = checked; });
  },
  classToggle : function({element, aryArguments} = {}) {
    aryArguments.map(e => element.classList.toggle(e))
  },
  compareDate : function({date1, date2} = {}) {
    return new Date(date1) < new Date(date2);
  },
  countChecked : function({prefix, prefixAdditional} = {}) {
    let count = 0;
    document.querySelectorAll("[id^='" + prefix + "_']")?.forEach(obj => {
      if (obj.checked) {
        count++;
      }
    });
    if (prefixAdditional) {
      document.querySelectorAll("[id^='" + prefixAdditional + "_']")?.forEach(obj => {
        if (obj.value > 1) {
          count += obj.value - 1;
        }
      });
    }
    return count;
  },
  countUpdate : function({prefix, prefixAdditional} = {}) {
    let count = input.countChecked({prefix: prefix, prefixAdditional: prefixAdditional});
    if (document.querySelectorAll("#" + prefix + "CheckAllCount").length == 0) {
      const objSpan = document.createElement("span");
      objSpan.id = prefix + "CheckAllCount";
      objSpan.innerText = " (" + count + ")";
      document.querySelector("#" + prefix + "CheckAll")?.after(objSpan);
    } else {
      document.querySelector("#" + prefix + "CheckAllCount").innerText = " (" + count + ")";
    }
  },
  deselectAllTomSelect : function({objId, placeholder, event} = {}) {
    const selectPlayer = document.querySelector("#" + objId);
    const tomSelect = selectPlayer.tomselect;
    tomSelect.clear();
    tomSelect.settings.placeholder = placeholder;
    tomSelect.inputState();
    event.target.removeAttribute("href");
    document.querySelector("#selectAll").href = "#";
    event.preventDefault();
    return false;
  },
  enable : function({objId, functionName} = {}) {
    if (document.querySelectorAll("[id^=" + objId + "]").length > 0 && document.querySelectorAll("#ids").length > 0) {
      const aryId = document.querySelector("#ids").value.split(", ");
      for (let id of aryId) {
        document.querySelectorAll("[id^=" + objId + "]")?.forEach(e => { e.disabled = functionName.call(e, id); });
      }
    }
  },
  enableView : function() {
    if (document.querySelectorAll("#view").length > 0) {
      document.querySelector("#view").disabled = (document.querySelector("#tournamentId").value == -1);
    }
  },
  firstYear : function() {
    return 2006;
  },
  formatDate : function({value, field, operation, amount} = {}) {
    let dt = new Date(value);
    if (field == "minutes") {
      dt.setMinutes(operation == "+" ? dt.getMinutes() + amount : dt.getMinutes() - amount);
    }
    return dt.getFullYear() + "-" + String(dt.getMonth() + 1).padStart(2, "0") + "-" + String(dt.getDate()).padStart(2, "0") + "T" + String(dt.getHours()).padStart(2, "0") + ":" + String(dt.getMinutes()).padStart(2, "0");
  },
  insertSelectedBefore : function({objIdSelected, objIdAfter, width} = {}) {
    const obj = document.querySelector("#selectedTournamentText");
    const objSelected = document.querySelector("#" + objIdSelected);
    const objAfter = document.querySelector("#" + objIdAfter);
    if (obj == undefined || obj.length == 0) {
      const objDiv = document.createElement("div");
      objDiv.classList.add("responsive-cell");
      objDiv.classList.add("responsive-cell-label");
      objDiv.classList.add("responsive-cell--head");
      const objDiv2 = document.createElement("div");
      objDiv2.classList.add("responsive-cell");
      objDiv2.classList.add("responsive-cell-value");
      objDiv2.style.width = width;
      const objP = document.createElement("p");
      objP.id = "selectedTournamentText";
      objP.innerText = objSelected.options[objSelected.selectedIndex].innerText;
      objDiv2.appendChild(objP);
      objAfter.insertAdjacentElement('beforebegin', objDiv);
      objAfter.insertAdjacentElement('beforebegin', objDiv2);
    }
  },
  invalid : function({selector} = {}) {
    document.querySelector(selector).classList.add("errors");
  },
  maskDate : function() {
    return "__/__/____";
  },
  maskDateTime : function() {
    return "__/__/____ __:__";
  },
  restorePreviousValue : function({selectors} = {}) {
    selectors.forEach(selector => { document.querySelectorAll(selector)?.forEach(obj => { obj.value = obj.dataset.previousValue; }); });
  },
  selectAllTomSelect: function({objId, event} = {}) {
    const selectPlayer = document.querySelector("#" + objId);
    const tomSelect = selectPlayer.tomselect;
    selectPlayer.querySelectorAll("option").forEach((opt) => { tomSelect.addItem(opt.value); });
    tomSelect.settings.placeholder = "";
    tomSelect.inputState();
    event.target.removeAttribute("href");
    document.querySelector("#deselectAll").href = "#";
    event.preventDefault();
    return false;
  },
  showDialog : function({name} = {}) {
    document.querySelector("#dialog" + name).showModal();
    $("table[id^='dataTbl']").DataTable().columns.adjust();
  },
  showDialogWithWidth : function({name} = {}) {
    document.querySelector("#dialog" + name).showModal();
    $("table[id^='dataTblRank']").DataTable().columns.adjust();
  },
  showHideToggle : function({aryId, idFocus} = {}) {
    aryId.forEach(element => { document.querySelector("#" + element).toggle(); });
    document.querySelector("#" + idFocus).focus();
  },
  storePreviousValue : function({selectors} = {}) {
    selectors.forEach(selector => { document.querySelectorAll(selector)?.forEach(obj => { obj.dataset.previousValue = obj.value; }); });
  },
  toggleCheckAll : function({id, idAll} = {}) {
    // if all checkboxes are checked then mark check all checkbox
    if (document.querySelectorAll("#" + idAll + "CheckAll").length > 0) {
      document.querySelector("#" + idAll + "CheckAll").checked = document.querySelectorAll("[id^='" + id + "_']:checked").length == document.querySelectorAll("[id^='" + id + "_']").length;
    }
  },
  toggleCheckboxes : function({id, idAll} = {}) {
    let disabled = false;
    // for each checkbox
    document.querySelectorAll('[id^="' + id + '_"]')?.forEach(obj => {
      // if enabled then set checked state to same as check all checkbox
      if (!obj.disabled) {
        obj.checked = document.querySelector("#" + idAll + "CheckAll").checked;
      } else {
        disabled = true;
      }
    });
    // if at least 1 disabled checkbox then uncheck check all checkbox
    if (disabled) {
      document.querySelector("#" + idAll + "CheckAll").checked = false;
    }
  },
  ucwords : function({value} = {}) {
    return (value + '').replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function ($1) { return $1.toUpperCase(); });
  },
  validateCommon : function({obj, event, pattern, condition, storeValue} = {}) {
    if ((obj.value != "") && (!(pattern.test(obj.value)) || !(condition))) {
      obj.value = obj.dataset.previousValueValidation;
      event.preventDefault();
      event.stopPropagation();
    } else {
      if (storeValue) {
        obj.dataset.previousValueValidation = obj.value;
      }
    }
  },
  validateLength : function({obj, length, focus, msg} = {}) {
    let result = "";
    if (obj) { 
      if (null == obj.value || obj.value.trim().length < length || input.maskDate() == obj.value.trim() || input.maskDateTime() == obj.value.trim()) {
        if (msg) {
          display.showErrors({errors: [msg]});
          result = msg;
        }
        obj.classList.add("errors");
        if (focus) {
          obj.focus();
        }
      } else {
        obj.classList.remove("errors");
      }
    }
    return result;
  },
  validateLetterOnly : function({obj, event} = {}) {
    input.validateCommon({obj: obj, event: event, pattern: /^[a-zA-Z ]+$/g, condition: true, storeValue: true});
  },
  validateNumberOnly : function({obj, event, storeValue} = {}) {
    input.validateCommon({obj: obj, event: event, pattern: /^-?\d+$/g, condition: true, storeValue: storeValue});
  },
  validateNumberOnlyGreaterZero : function({obj, event, condition, storeValue} = {}) {
    input.validateCommon({obj: obj, event: event, pattern: /^[1-9]\d*$/g, condition: condition, storeValue: storeValue});
  },
  validateNumberOnlyGreaterThanEqualToValue : function({obj, event, value, storeValue} = {}) {
    input.validateCommon({obj: obj, event: event, pattern: /^\d+$/g, condition: parseInt(obj.value) >= parseInt(value), storeValue: storeValue});
  },
  validateNumberOnlyLessThanEqualToValue : function({obj, value, event, storeValue} = {}) {
    input.validateCommon({obj: obj, event: event, pattern: /^\d+$/g, condition: parseInt(obj.value) <= parseInt(value), storeValue: storeValue});
  }
};
HTMLElement.prototype.show = function() {
  this.style.display = '';
}
HTMLElement.prototype.hide = function() {
  this.style.display = 'none';
}
HTMLElement.prototype.toggle = function() {
  if (this.style.display == 'none') {
    this.show();
  } else {
    this.hide();
  }
}
document.addEventListener("change", (event) => {
  if (event.target && event.target.id.includes("tournamentId")) {
    input.enableView();
  }
});
document.addEventListener("click", (event) => {
  if (event.target && event.target.id.includes("create")) {
    document.querySelector("#mode").value = event.target.value.toLowerCase();
    document.querySelector("#ids").value = "";
  } else if (event.target && event.target.id.includes("confirmDelete")) {
    document.querySelector("#mode").value = "confirm";
  } else if (event.target && (event.target.id.includes("view") || event.target.id.includes("cancel"))) {
    document.querySelector("#mode").value = "view";
    if (document.querySelector("#ids")) {
        document.querySelector("#ids").value = "";
    }
  } else if (event.target && event.target.id.includes("save") && document.forms[0].reportValidity()) {
    document.querySelector("#mode").value = "save" + document.querySelector("#mode").value;
  } else if (event.target && event.target.id.includes("reset")) {
    document.querySelectorAll(".errors")?.forEach(obj => { obj.classList.remove("errors"); });
    document.querySelectorAll("select option:disabled")?.forEach(option => { option.disabled = false; });
    document.querySelector("#errors").innerText = "";
    document.querySelector("#messages").innerText = "";
  }
});
document.addEventListener("focus", (event) => {
  if (event.target && event.target.id.includes("form")) {
    if ("" != event.target.id) {
      document.querySelector("#" + event.target.id).value = "";
      document.querySelector("#" + event.target.id).value = document.querySelector("#" + event.target.id).value;
    }
  }
});