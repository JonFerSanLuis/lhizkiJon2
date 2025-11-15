// Estado del juego - variables globales para guardar información
var preguntas = [];
var preguntaActual = 0;
var vidas = 3;
var aciertos = 0;
var fallos = 0;
var tiempoInicio = 0;
var totalPreguntas = 10;

// Elementos del HTML que vamos a usar
var pantallaLoading = document.getElementById("loadingScreen");
var pantallaJuego = document.getElementById("gameScreen");
var pantallaResultados = document.getElementById("resultsScreen");
var palabraPregunta = document.getElementById("questionWord");
var inputRespuesta = document.getElementById("answerInput");
var botonEnviar = document.getElementById("submitBtn");
var mensajeFeedback = document.getElementById("feedbackMessage");
var barraProgreso = document.getElementById("progressBar");
var textoProgreso = document.getElementById("progressText");

// Cuando la página carga, iniciamos el juego
document.addEventListener("DOMContentLoaded", function() {
  iniciarJuego();

  // Cuando hacen click en el botón de enviar
  botonEnviar.addEventListener("click", function() {
    comprobarRespuesta();
  });

  // Cuando presionan Enter en el input
  inputRespuesta.addEventListener("keypress", function(evento) {
    if (evento.key === "Enter" && !botonEnviar.disabled) {
      comprobarRespuesta();
    }
  });

  // Limpiar el feedback cuando escriben
  inputRespuesta.addEventListener("input", function() {
    inputRespuesta.classList.remove("correct", "incorrect");
    mensajeFeedback.classList.remove("show");
  });
});

// Función para iniciar el juego
function iniciarJuego() {
  mostrarPantalla("loading");
  
  // Crear petición para obtener preguntas
  var peticion = new XMLHttpRequest();
  
  // Qué hacer cuando llegue la respuesta
  peticion.onreadystatechange = function() {
    // readyState 4 = petición completada
    // status 200 = respuesta exitosa
    if (peticion.readyState === 4 && peticion.status === 200) {
      // Convertir respuesta de texto a objeto JavaScript
      var respuesta = JSON.parse(peticion.responseText);
      
      if (respuesta.success && respuesta.preguntas && respuesta.preguntas.length >= 10) {
        preguntas = respuesta.preguntas;
        tiempoInicio = new Date().getTime();
        
        // Esperar un poco para mostrar animación
        setTimeout(function() {
          mostrarPantalla("game");
          cargarPregunta();
        }, 1500);
      } else {
        mostrarError("Ez dira nahikoa galdera aurkitu. Saiatu berriro geroago.");
      }
    } else if (peticion.readyState === 4) {
      // Si hay error
      mostrarError("Errorea gertatu da jokoa hastean. Saiatu berriro.");
    }
  };
  
  // Enviar petición al servidor
  peticion.open("GET", "../controller/juego_controller.php?accion=obtener_preguntas", true);
  peticion.send();
}

// Función para mostrar una pantalla específica
function mostrarPantalla(pantalla) {
  // Ocultar todas las pantallas
  pantallaLoading.classList.remove("active");
  pantallaJuego.classList.remove("active");
  pantallaResultados.classList.remove("active");

  // Mostrar la pantalla solicitada
  if (pantalla === "loading") {
    pantallaLoading.classList.add("active");
  } else if (pantalla === "game") {
    pantallaJuego.classList.add("active");
  } else if (pantalla === "results") {
    pantallaResultados.classList.add("active");
  }
}

// Función para cargar la pregunta actual
function cargarPregunta() {
  // Si ya terminamos todas las preguntas
  if (preguntaActual >= totalPreguntas) {
    terminarJuego();
    return;
  }

  var pregunta = preguntas[preguntaActual];
  palabraPregunta.textContent = pregunta.termino_castellano;

  // Limpiar el input
  inputRespuesta.value = "";
  inputRespuesta.classList.remove("correct", "incorrect");
  inputRespuesta.disabled = false;
  inputRespuesta.focus();

  // Activar botón
  botonEnviar.disabled = false;

  // Ocultar feedback
  mensajeFeedback.classList.remove("show");

  // Actualizar barra de progreso
  actualizarProgreso();
}

// Función para comprobar la respuesta del usuario
function comprobarRespuesta() {
  var respuestaUsuario = limpiarTexto(inputRespuesta.value.trim());
  var respuestaCorrecta = limpiarTexto(preguntas[preguntaActual].respuesta_correcta);

  // Deshabilitar input y botón
  inputRespuesta.disabled = true;
  botonEnviar.disabled = true;

  if (respuestaUsuario === respuestaCorrecta) {
    manejarRespuestaCorrecta();
  } else {
    manejarRespuestaIncorrecta(preguntas[preguntaActual].respuesta_correcta);
  }
}

// Función cuando la respuesta es correcta
function manejarRespuestaCorrecta() {
  aciertos++;

  // Mostrar feedback visual
  inputRespuesta.classList.add("correct");
  mostrarFeedback("Ondo! 🎉", "correct");

  // Pasar a la siguiente pregunta después de 1.5 segundos
  setTimeout(function() {
    preguntaActual++;
    cargarPregunta();
  }, 1500);
}

// Función cuando la respuesta es incorrecta
function manejarRespuestaIncorrecta(respuestaCorrecta) {
  fallos++;
  vidas--;

  // Mostrar feedback visual
  inputRespuesta.classList.add("incorrect");
  mostrarFeedback("Oker! Erantzun zuzena: " + respuestaCorrecta, "incorrect");

  // Actualizar corazones de vidas
  actualizarVidas();

  // Si no quedan vidas, terminar juego
  if (vidas <= 0) {
    setTimeout(function() {
      terminarJuego();
    }, 2000);
  } else {
    // Pasar a siguiente pregunta
    setTimeout(function() {
      preguntaActual++;
      cargarPregunta();
    }, 2000);
  }
}

// Función para mostrar mensaje de feedback
function mostrarFeedback(mensaje, tipo) {
  mensajeFeedback.textContent = mensaje;
  mensajeFeedback.className = "feedback-message show " + tipo;
}

// Función para actualizar las vidas visuales
function actualizarVidas() {
  var iconosVida = document.querySelectorAll(".life-icon");
  for (var i = 0; i < iconosVida.length; i++) {
    if (i >= vidas) {
      iconosVida[i].classList.add("lost");
    }
  }
}

// Función para actualizar la barra de progreso
function actualizarProgreso() {
  var progreso = (preguntaActual / totalPreguntas) * 100;
  barraProgreso.style.width = progreso + "%";
  textoProgreso.textContent = preguntaActual + "/" + totalPreguntas;
}

// Función para terminar el juego
function terminarJuego() {
  var tiempoFinal = new Date().getTime();
  var tiempoTranscurrido = Math.floor((tiempoFinal - tiempoInicio) / 1000);
  var puntos = aciertos * 100;

  // Guardar resultado en el servidor
  var peticion = new XMLHttpRequest();
  
  peticion.onreadystatechange = function() {
    if (peticion.readyState === 4 && peticion.status === 200) {
      console.log("Resultado guardado correctamente");
    }
  };

  // Preparar datos para enviar
  var datos = "accion=guardar_resultado";
  datos += "&aciertos=" + aciertos;
  datos += "&fallos=" + fallos;
  datos += "&tiempo_empleado=" + tiempoTranscurrido;

  peticion.open("POST", "../controller/juego_controller.php", true);
  peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  peticion.send(datos);

  // Mostrar resultados
  mostrarResultados(puntos);
}

// Función para mostrar los resultados
function mostrarResultados(puntos) {
  // Actualizar estadísticas
  document.getElementById("correctAnswers").textContent = aciertos;
  document.getElementById("wrongAnswers").textContent = fallos;
  document.getElementById("totalPoints").textContent = puntos;

  // Personalizar mensaje según rendimiento
  var iconoResultado = document.getElementById("resultsIcon");
  var tituloResultado = document.getElementById("resultsTitle");

  if (aciertos >= 9) {
    iconoResultado.textContent = "🏆";
    tituloResultado.textContent = "Bikain!";
  } else if (aciertos >= 7) {
    iconoResultado.textContent = "🌟";
    tituloResultado.textContent = "Oso ondo!";
  } else if (aciertos >= 5) {
    iconoResultado.textContent = "👍";
    tituloResultado.textContent = "Ondo!";
  } else {
    iconoResultado.textContent = "💪";
    tituloResultado.textContent = "Jarraitu praktikatzen!";
  }

  // Mostrar pantalla de resultados
  mostrarPantalla("results");
}

// Función para reiniciar el juego
function restartGame() {
  // Resetear variables
  preguntaActual = 0;
  vidas = 3;
  aciertos = 0;
  fallos = 0;
  tiempoInicio = 0;

  // Resetear vidas visuales
  var iconosVida = document.querySelectorAll(".life-icon");
  for (var i = 0; i < iconosVida.length; i++) {
    iconosVida[i].classList.remove("lost");
  }

  // Reiniciar juego
  iniciarJuego();
}

// Función para mostrar error
function mostrarError(mensaje) {
  alert(mensaje);
  window.location.href = "perfilAlumno.php";
}

// Función para limpiar texto (quitar tildes y mayúsculas)
function limpiarTexto(texto) {
  return texto
    .toLowerCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .trim();
}