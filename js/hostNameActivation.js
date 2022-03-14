let checker = document.getElementById("hostNameDef");
let field = document.getElementById("hostName");
// when unchecked or checked, run the function
checker.onchange = function () {
  if (this.checked) {
    field.disabled = true;
  } else {
    field.disabled = false;
  }
};