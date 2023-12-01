"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  initializeTomSelect : function({placeholder} = {}) {
    document.querySelectorAll(".tom-select").forEach((el) => {
      const settings = {
      // max items required so isFull() works
        maxItems: el.options.length,
        placeholder: placeholder,
        plugins: {
          "clear_button" : { title: "Remove all selected options" },
          "remove_button" : { title: "Remove this option" }
        },
        render: {
          item: function(data, escape) {
            return "<div title=\"" + escape(data.value) + "\">" + escape(data.text) + "</div>";
          }
        },
        onItemAdd : function() {
          document.querySelector("#to").tomselect.control_input.value = "";
          document.querySelector("#deselectAll").href = "#";
          if (document.querySelector("#to").tomselect.isFull()) { document.querySelector("#selectAll").removeAttribute("href"); }
          if (document.querySelector("#to").tomselect.items.length > 0) { document.querySelector(".ts-control").classList.remove("errors"); }
          inputLocal.validate();
        },
        onItemRemove : function() {
          document.querySelector("#to").tomselect.control_input.value = "";
          document.querySelector("#selectAll").href = "#";
          if (document.querySelector("#to").tomselect.getValue() == "") { document.querySelector("#deselectAll").removeAttribute("href"); }
          if (document.querySelector("#to").tomselect.items.length > 0) {
            document.querySelector(".ts-control").classList.remove("errors");
          } else {
            document.querySelector(".ts-control").classList.add("errors");
          }
          inputLocal.validate();
        }
      };
      new TomSelect(el, settings);
    });
  },
  initalizeEditor : function() {
    tinymce.init({
      /*advlist_bullet_styles: 'square',
      advlist_number_styles: 'lower-alpha,lower-roman,upper-alpha,upper-roman',*/
      link_assume_external_targets: true,
      link_default_protocol: 'http',
      link_default_target: '_blank',
      menubar: '',
      placeholder: 'Body content here...',
      /*plugins: 'a11ychecker advcode advtable advtemplate anchor autocorrect casechange checklist codesample editimage export footnotes formatpainter  inlinecss media mediaembed mentions pageembed permanentpen powerpaste tableofcontents tinymcespellchecker typography visualblocks',*/
      plugins: 'accordion advlist autolink autoresize charmap emoticons image insertdatetime link lists nonbreaking preview quickbars searchreplace table visualchars wordcount ',
      selector: 'textarea#body',
      setup: (editor) => {
        editor.on('init', (e) => {
          document.querySelector('button.tox-statusbar__wordcount').click();
          if (tinymce.activeEditor.getContent() == "") {
            document.querySelector(".tox-edit-area__iframe")?.classList.add("invalid");
          } else {
            document.querySelector(".tox-edit-area__iframe")?.classList.remove("invalid");
          }
        });
        editor.on('selectionChange', (e) => {
          if (tinymce.activeEditor.getContent() == "") {
            document.querySelector(".tox-edit-area__iframe")?.classList.add("invalid");
          } else {
            document.querySelector(".tox-edit-area__iframe")?.classList.remove("invalid");
          }
          inputLocal.validate();
        });
      },
      toolbar: 'undo redo bold italic underline strikethrough checklist numlist bullist indent outdent align lineheight emoticons link image table'
    });
  },
  reset : function() {
    document.querySelector("#to").tomselect.clear();
    tinymce.get("body").setContent("");
    document.querySelector(".tox-edit-area__iframe")?.classList.add("invalid");
  },
  validate : function(event) {
    let result = true;
    const players = document.querySelectorAll(".ts-control");
    const player = document.querySelector("#to-ts-control");
    const subject = document.querySelector("#subject");
    if (players) {
      player.setCustomValidity(players[0].children[0].textContent == "" ? "You must select a player" : "");
      if (players[0].children[0].textContent == "") {
        document.querySelector(".ts-control").classList.add("errors");
      }
      subject.setCustomValidity(subject.validity.valueMissing ? "You must enter a subject" : "");
      const bodyInvalid = document.querySelector(".tox-edit-area__iframe")?.classList.contains("invalid");
      if (players[0].children[0].textContent == "" || subject.validity.valueMissing || bodyInvalid) {
        if (players[0].children[0].textContent != "" && !subject.validity.valueMissing && bodyInvalid) {
          if (event) {
            event.preventDefault();
          }
          tinymce.get("body").getBody().setAttribute('data-mce-placeholder', 'You must enter something for the body...');
        }
        result = false;
      }
    }
    return result;
  },
};
let documentReadyCallback = () => {
  inputLocal.initializeTomSelect({placeholder: "Select player(s)..."});
  inputLocal.initalizeEditor();
  inputLocal.validate();
  input.storePreviousValue({selectors: ["[id^='to']", "[id^='subject']", "[id^='body']"]});
};
if (document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll)) {
  documentReadyCallback();
} else {
  document.addEventListener("DOMContentLoaded", documentReadyCallback);
}
document.addEventListener("click", (event) => {
  const result = inputLocal.validate(event);
  if (event.target && event.target.id == "selectAll") {
    return input.selectAllTomSelect({objId: "to", event: event});
  } else if (event.target && event.target.id.includes("deselectAll")) {
    return input.deselectAllTomSelect({objId: "to", placeholder: "Select player(s)...", event: event});
  } else if (event.target && event.target.id.includes("email")) {
    if (result) {
      document.querySelector("#mode").value = event.target.value.toLowerCase();
    }
  } else if (event.target && event.target.id.includes("reset")) {
    inputLocal.reset();
    input.restorePreviousValue({selectors: ["[id^='to']", "[id^='subject']", "[id^='body']"]});
   }
});
document.addEventListener("input", (event) => {
  inputLocal.validate(event);
});