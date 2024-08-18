import "../scss/layout.scss";

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
