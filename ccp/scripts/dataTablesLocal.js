"use script";
import "../vendor/components/jquery/jquery.js";
import "../vendor/datatables.net/datatables.net/js/dataTables.js";
import "../vendor/datatables.net/datatables.net-rowgroup/js/dataTables.rowGroup.js";
import { display } from "./display.js";
export const dataTable = {
  displayHighlightRow : function({tableId, value} = {}) {
    document.querySelectorAll(tableId + " tbody tr td").forEach(cell => {
      if (cell.innerText == value) {
        cell.parentElement.classList.add("highlightRow");
      }
    });
  },
  getSelectedRows : function() {
    return $("tr.selected");
  },
  getSelectedRowsData : function({jQueryTableApi} = {}) {
    return jQueryTableApi.rows(".selected").data();
  },
  initialize : function({tableId, aryColumns = null, aryOrder = [], aryRowGroup = false, autoWidth = false, paging = false, scrollCollapse = true, scrollResize = true, scrollY = "", searching = true} = {}) {
    dataTable.initializeCommon({tableSelector: "#" + tableId, aryColumns: aryColumns, aryOrder: aryOrder, aryRowGroup: aryRowGroup, autoWidth: autoWidth, paging: paging, scrollCollapse: scrollCollapse, scrollResize: scrollResize, scrollY: scrollY, searching: searching});
  },
  initializeCommon : function({tableSelector, aryColumns = null, aryOrder = [], aryRowGroup = false, autoWidth = false, paging = false, scrollCollapse = true, scrollResize = true, scrollY = "", searching = true} = {}) {
    $(tableSelector).DataTable({ "autoWidth": autoWidth, "columns": aryColumns, "destroy": true, "language": {"info": "Showing _START_ to _END_ of _TOTAL_", "infoEmpty": ""}, "layout": { topStart: null, topEnd: null, bottomStart: "info", bottomEnd: null }, "order": aryOrder, "paging": paging, "responsive": true, "rowGroup": aryRowGroup, "rowReorder": { selector: 'td:nth-child(2)' }, "scrollCollapse": scrollCollapse, "scrollResize": scrollResize, "scrollY": scrollY, "searching": searching });
  },
  initializeBySelector : function({tableSelector, aryColumns = null, aryOrder = [], aryRowGroup = false, autoWidth = false, paging = false, scrollCollapse = true, scrollResize = true, scrollY = "", searching = true} = {}) {
    dataTable.initializeCommon({tableSelector: tableSelector, aryColumns: aryColumns, aryOrder: aryOrder, aryRowGroup: aryRowGroup, autoWidth: autoWidth, paging: paging, scrollCollapse: scrollCollapse, scrollResize: scrollResize, scrollY: scrollY, searching: searching});
  }
};
if ($.fn.dataTableExt !== undefined) {
  jQuery.fn.dataTableExt.oSort["date-asc"]  = function(val1, val2) {
    return display.sortDate({value1: val1, value2: val2, direction: "asc"});
  };
  jQuery.fn.dataTableExt.oSort["date-desc"] = function(val1, val2) {
    return display.sortDate({value1: val1, value2: val2, direction: "desc"});
  };
  jQuery.fn.dataTableExt.oSort["host-asc"] = function(val1, val2) {
    const pos1 = val1.indexOf(">");
    const value1 = val1.substring(pos1 + 1, val1.indexOf("<", pos1));
    const pos2 = val2.indexOf(">");
    const value2 = val2.substring(pos2 + 1, val2.indexOf("<", pos2));
    return display.sort({value1: value1, value2: value2, delimiter: " ", direction: "asc"});
  };
  jQuery.fn.dataTableExt.oSort["host-desc"] = function(val1, val2) {
    const pos1 = val1.indexOf(">");
    const value1 = val1.substring(pos1 + 1, val1.indexOf("<", pos1));
    const pos2 = val2.indexOf(">");
    const value2 = val2.substring(pos2 + 1, val2.indexOf("<", pos2));
    return display.sort({value1: value1, value2: value2, delimiter: " ", direction: "desc"});
  };
  jQuery.fn.dataTableExt.oSort["name-asc"] = function(val1, val2) {
    return display.sort({value1: val1, value2: val2, delimiter: " ", direction: "asc"});
  };
  jQuery.fn.dataTableExt.oSort["name-desc"] = function(val1, val2) {
    return display.sort({value1: val1, value2: val2, delimiter: " ", direction: "desc"});
  };
  const valueNotApplicable = "N/A";
  const valueReplace = "999999";
  jQuery.fn.dataTableExt.oSort["register-asc"] = function(val1, val2) {
    return display.sortNumber({value1: valueNotApplicable == val1 ? valueReplace : val1, value2: valueNotApplicable == val2 ? valueReplace : val2, delimiter: " ", direction: "asc"});
  };
  jQuery.fn.dataTableExt.oSort["register-desc"] = function(val1, val2) {
    return display.sortNumber({value1: valueNotApplicable == val1 ? valueReplace : val1, value2: valueNotApplicable == val2 ? valueReplace : val2, delimiter: " ", direction: "desc"});
  };
}