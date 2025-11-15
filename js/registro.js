// Funcionalidad para filtrar ciclos por centro educativo
document.addEventListener("DOMContentLoaded", () => {
  const centroSelect = document.getElementById("reg-centro")
  const cicloSelect = document.getElementById("reg-ciclo")

  centroSelect.addEventListener("change", function () {
    const centroSeleccionado = this.value

    // Limpiar opciones actuales (excepto la primera)
    cicloSelect.innerHTML = '<option value="">Aukeratu zikloa...</option>'

    if (centroSeleccionado) {
      // Mostrar indicador de carga
      cicloSelect.innerHTML =
        '<option value="" disabled class="loading-option">⏳ Zikloak kargatzen...</option>'
      cicloSelect.disabled = true
      cicloSelect.classList.add("loading")

      // Hacer petición AJAX para obtener ciclos del centro seleccionado
      fetch(`./controller/obtener-ciclos.php?id_centro=${centroSeleccionado}`)
        .then((response) => {
          if (!response.ok) {
            throw new Error("Error en la respuesta del servidor")
          }
          return response.json()
        })
        .then((ciclos) => {
          // Limpiar el indicador de carga
          cicloSelect.innerHTML = '<option value="">Aukeratu zikloa...</option>'
          cicloSelect.disabled = false
          cicloSelect.classList.remove("loading")

          if (ciclos.length === 0) {
            const option = document.createElement("option")
            option.value = ""
            option.textContent = "Ez dago ziklorik eskuragarri ikastetxe honetan"
            option.disabled = true
            cicloSelect.appendChild(option)
          } else {
            ciclos.forEach((ciclo) => {
              const option = document.createElement("option")
              option.value = ciclo.id_ciclo
              option.textContent = ciclo.nombre_ciclo
              if (ciclo.familia_profesional) {
                option.textContent += ` (${ciclo.familia_profesional})`
              }
              cicloSelect.appendChild(option)
            })
          }
        })
        .catch((error) => {
          console.error("Error al cargar ciclos:", error)

          // Rehabilitar el select y mostrar mensaje de error
          cicloSelect.disabled = false
          cicloSelect.classList.remove("loading")
          cicloSelect.innerHTML = '<option value="">Aukeratu zikloa...</option>'

          const option = document.createElement("option")
          option.value = ""
          option.textContent = "Errorea zikloak kargatzean"
          option.disabled = true
          cicloSelect.appendChild(option)
        })
    } else {
      // Si no hay centro seleccionado, mostrar mensaje inicial
      cicloSelect.innerHTML = '<option value="">Lehenengo ikastetxea aukeratu...</option>'
    }
  })


  //aqui empieza el manejo y la parte de la animación al hacer el registro o inicio de sesión
  const loginForm = document.querySelector("#login-section form")
  const registerForm = document.querySelector("#register-section form")
  const loadingOverlay = document.getElementById("loading-overlay")

  //manejo el login
  if (loginForm) {
    loginForm.addEventListener("submit", (evento) => {
      //se muestra la animación
      if (loadingOverlay) {
        loadingOverlay.classList.add("active")
      }
      //y se envía el formulario
    })
  }

  //manejo el registro
  if (registerForm) {
    registerForm.addEventListener("submit", (evento) => {
      //valido que las contraseñas coincidan
      const password = document.getElementById("reg-password").value
      const confirmPassword = document.getElementById("reg-confirm-password").value

      if (password !== confirmPassword) {
        evento.preventDefault()
        alert("Pasahitzak ez datoz bat!")
        return false
      }

      //valido lo seleccionado (centro y ciclo)
      const centro = document.getElementById("reg-centro").value
      const ciclo = document.getElementById("reg-ciclo").value

      if (!centro || !ciclo) {
        evento.preventDefault()
        alert("Mesedez, aukeratu ikastetxea eta ziklo formatibua")
        return false
      }

      //muestro la animación
      if (loadingOverlay) {
        loadingOverlay.classList.add("active")
      }

      //se envía el formulario
    })
  }
})


window.addEventListener("pageshow", (event) => {
  //si el usuario recarga la página (para que no haya bucle de carga)
  if (event.persisted) {
    const loadingOverlay = document.getElementById("loading-overlay")
    if (loadingOverlay) {
      //ocultar la animación de carga inmediatamente
      loadingOverlay.classList.remove("active")
    }
  }
})