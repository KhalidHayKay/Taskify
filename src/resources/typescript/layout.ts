import "../scss/layout.scss";
import { get, post } from "./ajax";

const userMenuDiv = document.getElementById("userMenu");
const userMenu = document.getElementById("userButton");

document.addEventListener("click", (e) => {
  if (userMenu === e.target || userMenu?.contains(e.target as Node)) {
    if (userMenuDiv?.classList.contains("invisible")) {
      userMenuDiv?.classList.remove("invisible");
    } else {
      userMenuDiv?.classList.add("invisible");
    }
  } else {
    userMenuDiv?.classList.add("invisible");
  }
});

document.getElementById("logout-btn")?.addEventListener("click", (e) => {
  if (!confirm("You are about to logout. Click OK to continue")) {
    e.preventDefault();
  } else {
    return;
  }
});

// todo: alert for accepted
get("/user/contact_person/acknowledgement")
  .then((res) => res.json())
  .then((contactPersonIsAcknowledged) => {
    if (!contactPersonIsAcknowledged) {
      alert("Your Contact Person has accepted your request");
      post("/user/contact_person/acknowledgement", {});
    }
  });
