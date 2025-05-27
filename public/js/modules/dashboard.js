export function initDashboard() {
  const username = document.querySelector('#user-name');
  if (username) {
    console.log(`Bienvenue ${username.textContent}`);
  }
}
