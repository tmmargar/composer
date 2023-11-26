"use script";
import { dataTable, display, input } from "./import.js";
document.addEventListener("click", (event) => {
  if (event.target && event.target.id.includes("modify")) {
    const selectedRows = dataTable.getSelectedRows({jQueryTable: $("#dataTbl").dataTable()});
    if (selectedRows.length == 0) {
      display.showErrors({errors: [ "You must select a row to modify" ]});
      event.preventDefault();
      event.stopPropagation();
    } else if (selectedRows.length > 1) {
      display.showErrors({errors: [ "You must select only 1 row to modify" ]});
      event.preventDefault();
      event.stopPropagation();
    } else {
      document.querySelector("#mode").value = event.target.value.toLowerCase();
    }
  } else if (event.target && event.target.id.includes("delete")) {
    const selectedRows = dataTable.getSelectedRows({jQueryTable: $("#dataTbl").dataTable()});
    if (selectedRows.length == 0) {
      display.showErrors({errors: [ "You must select a row to delete" ]});
      event.preventDefault();
      event.stopPropagation();
    } else {
      document.querySelector("#mode").value = event.target.value.toLowerCase();
    }
  }
});