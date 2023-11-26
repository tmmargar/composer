export const display = {
  clearErrorsAndMessages : function() {
    // clear and hide errors and messages
    document.querySelector("#errors").innerHTML = "";
    document.querySelector("#messages").innerHTML = "";
    document.querySelector("#errors").style.display = "none";
    document.querySelector("#messages").style.display = "none";
    document.querySelector("#info").style.display = "none";
  },
  formatActive : function({value, meta, tableId} = {}) {
    if (value != "1") { document.querySelector("#" + tableId + " tbody tr:nth-of-type(" + (meta.row + 1) + ")").classList.add("inactive"); }
    return value == "1" ? "Yes" : "No";
  },
  formatHighlight : function({value, meta, tableId} = {}) {
    if (value == "1") { document.querySelector("#" + tableId + " tbody tr:nth-of-type(" + (meta.row + 1) + ") td:nth-of-type(" + (meta.col + 1) + ")").classList.add("highlight"); }
    return value == "1" ? "Yes" : "No";
  },
  formatPhone : function({value} = {}) {
    //Filter only numbers from the input
    const cleaned = ('' + value).replace(/\D/g, '');
    //Check if the input is of correct
    const match = cleaned.match(/^(1|)?(\d{3})(\d{3})(\d{4})$/);
    if (match) {
      //Remove the matched extension code
      //Change this to format for any country code.
      const intlCode = (match[1] != undefined ? '+1 ' : '')
      return [intlCode, '(', match[2], ') ', match[3], '-', match[4]].join('')
    }
    return null;
  },
  showErrors : function({errors} = {}) {
    let output = "<span class=\"boldHeader\">Errors:</span>";
    for (let err of errors) {
      if (output.length > 13) {
        output += "<br />";
      }
      output += err;
    }
    document.querySelector("#info").style.display = "block";
    document.querySelector("#errors").innerHTML = output;
    document.querySelector("#errors").style.display = "block";
  },
  showMessages : function({messages} = {}) {
    let output = "<span class=\"boldHeader\">Messages:</span>";
    for (let msg of messages) {
      if (output.length > 15) {
        output += "<br />";
      }
      output += msg;
    }
    document.querySelector("#info").style.display = "block";
    const obj = document.querySelector("#messages");
    obj.innerHTML = output;
    obj.style.display = "block";
  },
  sort : function({value1, value2, delimiter, direction} = {}) {
    const aryVal1 = value1.split(delimiter);
    const aryVal2 = value2.split(delimiter);
    let result = 0;
    if (("asc" == direction) || ("ascending" == direction)) {
      result = (aryVal1[1] < aryVal2[1]) ? -1 : ((aryVal1[1] > aryVal2[1]) ? 1 : 0);
      if (0 == result) {
        result = (aryVal1[0] < aryVal2[0]) ? -1 : ((aryVal1[0] > aryVal2[0]) ? 1 : 0);
      }
    } else {
      result = (aryVal1[1] < aryVal2[1]) ? 1 : ((aryVal1[1] > aryVal2[1]) ? -1 : 0);
      if (0 == result) {
        result = (aryVal1[0] < aryVal2[0]) ? 1 : ((aryVal1[0] > aryVal2[0]) ? -1 : 0);
      }
    }
    return result;
  },
  sortDate : function({value1, value2, direction} = {}) {
    let result = 0;
    if (("asc" == direction) || ("ascending" == direction)) {
      result = new Date(value1) - new Date(value2);
    } else {
      result = new Date(value2) - new Date(value1);
    }
    return result;
  },
  sortNumber : function({value1, value2, delimiter, direction} = {}) {
    const val1Temp = value1.replace(",", "");
    const val2Temp = value2.replace(",", "");
    const aryVal1 = val1Temp.split(delimiter);
    const aryVal2 = val2Temp.split(delimiter);
    let result = 0;
    if (("asc" == direction) || ("ascending" == direction)) {
      result = (parseFloat(aryVal1[0]) < parseFloat(aryVal2[0])) ? -1 : ((parseFloat(aryVal1[0]) > parseFloat(aryVal2[0])) ? 1 : 0);
    } else {
      result = (parseFloat(aryVal1[0]) < parseFloat(aryVal2[0])) ? 1 : ((parseFloat(aryVal1[0]) > parseFloat(aryVal2[0])) ? -1 : 0);
    }
    return result;
  }
};