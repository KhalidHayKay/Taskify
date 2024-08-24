const form = document.querySelector("form");
const delBtn = document.querySelector("form button#delete-btn") as HTMLElement;
const editBtn = document.querySelector("form button#edit-btn") as HTMLElement;
// const saveBtn = document.querySelector("form button#save-btn") as HTMLElement;
// const formInputs = document.querySelectorAll("form input[class]");

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

form?.addEventListener("click", (e) => {
  const dispatcher = e.target as HTMLElement;

  if (dispatcher === editBtn) {
    appendMethodRewrite("put");
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
