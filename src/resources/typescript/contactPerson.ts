const form = document.querySelector("form");
const delBtn = document.querySelector("form button#delete-btn") as HTMLElement;
const editBtn = document.querySelector("form button#edit-btn") as HTMLElement;
const saveBtn = document.querySelector("form button#save-btn") as HTMLElement;
const formInputs = document.querySelectorAll("form input[class]");

const appendMethodRewrite = (method: string) => {
  const methods = new Set(["put", "delete", "patch"]);

  const rewriteInput = document.createElement("input") as HTMLInputElement;
  rewriteInput.hidden = true;
  rewriteInput.name = "_METHOD";
  rewriteInput.value = method.toUpperCase();

  if (methods.has(method)) {
    form?.appendChild(rewriteInput);
  }
};

const setInputDisability = (input: Element, value: boolean) => {
  (input as HTMLInputElement).disabled = value;
};

form?.addEventListener("click", (e) => {
  const dispatcher = e.target as HTMLElement;

  if (dispatcher === editBtn) {
    appendMethodRewrite("put");
    formInputs.forEach((input) => {
      setInputDisability(input, false);
    });
    saveBtn.removeAttribute("disabled");
    (formInputs[0] as HTMLInputElement).focus();
  } else if (dispatcher === delBtn) {
    if (
      !confirm(
        "Deleting your contact person means all your priority tasks will be reverted. Click OK if you want to proceed"
      )
    ) {
      e.preventDefault();
    } else {
      appendMethodRewrite("delete");
    }
  }
});

formInputs.forEach((input) => {
  if ((input as HTMLInputElement).value !== "") {
    setInputDisability(input, true);
    saveBtn.setAttribute("disabled", "true");

    if (
      !(
        (formInputs[0] as HTMLInputElement).value !== "" &&
        (formInputs[1] as HTMLInputElement).value !== ""
      )
    ) {
      console.log("true");
      setInputDisability(input, false);
      saveBtn.removeAttribute("disabled");
    }
  }

  if ((input as HTMLInputElement).value === "") {
    delBtn.setAttribute("disabled", "true");
    editBtn.setAttribute("disabled", "true");
  }
});
