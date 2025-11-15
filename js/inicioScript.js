// Animación de entrada para las tarjetas
document.addEventListener("DOMContentLoaded", () => {
  // Animar las tarjetas al cargar
  const cards = document.querySelectorAll(".feature-card, .stat-card")
  cards.forEach((card, index) => {
    card.style.opacity = "0"
    card.style.transform = "translateY(20px)"

    setTimeout(() => {
      card.style.transition = "all 0.5s ease"
      card.style.opacity = "1"
      card.style.transform = "translateY(0)"
    }, index * 100)
  })

  // Funcionalidad del botón de notificaciones
  const notificationBtn = document.querySelector(".notification-btn")
  notificationBtn.addEventListener("click", function () {
    this.style.transform = "scale(0.9)"
    setTimeout(() => {
      this.style.transform = "scale(1)"
    }, 150)
  })

  // Funcionalidad del botón de misiones
  const missionsBtn = document.querySelector(".missions-icon-btn")
  missionsBtn.addEventListener("click", function () {
    this.style.transform = "rotate(45deg)"
    setTimeout(() => {
      this.style.transform = "rotate(0deg)"
    }, 300)
  })

  // Funcionalidad del botón "Hasi jolasa"
  const startGameBtn = document.querySelector(".start-game-btn")
  startGameBtn.addEventListener("click", function () {
    this.textContent = "Kargatzen..."
    setTimeout(() => {
      this.textContent = "Hasi jolasa"
    }, 4000)
  })

  // Hacer las tarjetas de características clicables
  const featureCards = document.querySelectorAll(".feature-card")
  featureCards.forEach((card) => {
    card.addEventListener("click", function () {
      const title = this.querySelector(".feature-title").textContent
      console.log(`Navegando a: ${title}`)
    })
  })

  // Actualizar el progreso con animación
  const progressBar = document.querySelector(".progress-bar")
  progressBar.style.transition = "width 1.5s ease-in-out"
})
