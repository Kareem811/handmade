const header = document.querySelector("header");
const changeHeaderStyles = (status) => {
  if (status) {
    return (header.style.background = `rgba(0,0,0,0.2)`);
  }
  return (header.style.background = `#2b2b2b`);
};
const checkWindowUrl = (url) => {
  if (url === "/handmade/" || url === "/handmade/home.php" || url === "/handmade/index.php") {
    return true;
  }
  return false;
};
changeHeaderStyles(checkWindowUrl(window.location.pathname));
