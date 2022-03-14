let checker = document.getElementById("agree");
let sendbtn = document.getElementById("submit");
// when unchecked or checked, run the function
checker.onchange = function () {
  if (this.checked) {
    sendbtn.disabled = false;
  } else {
    sendbtn.disabled = true;
  }
};
