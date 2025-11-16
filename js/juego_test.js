var preguntas = [];
var preguntaActual = 0;
var vidas = 3;
var aciertos = 0;
var fallos = 0;
var tiempoInicio = 0;
var totalPreguntas = 10;

var pantallaLoading = document.getElementById("loadingScreen");
var pantallaJuego = document.getElementById("gameScreen");
var pantallaResultados = document.getElementById("resultsScreen");
var palabraPregunta = document.getElementById("questionWord");
var contenedorOpciones = document.getElementById("optionsContainer");
var barraProgreso = document.getElementById("progressBar");
var textoProgreso = document.getElementById("progressText");

document.addEventListener("DOMContentLoaded", function() {
  iniciarJuego();
});

function iniciarJuego() {
  mostrarPantalla("loading");

  var peticion = new XMLHttpRequest();

  peticion.onreadystatechange = function() {
    if (peticion.readyState === 4 && peticion.status === 200) {
      var respuesta = JSON.parse(peticion.responseText);

      if (respuesta.success && respuesta.preguntas && respuesta.preguntas.length >= 10) {
        preguntas = respuesta.preguntas;
        tiempoInicio = new Date().getTime();

        setTimeout(function() {
          mostrarPantalla("game");
          cargarPregunta();
        }, 1500);
      } else {
        mostrarError("Ez dira nahikoa galdera aurkitu. Saiatu berriro geroago.");
      }
    } else if (peticion.readyState === 4) {
      mostrarError("Errorea gertatu da jokoa hastean. Saiatu berriro.");
    }
  };

  peticion.open("GET", "../controller/juego_test_controller.php?accion=obtener_preguntas", true);
  peticion.send();
}

function mostrarPantalla(pantalla) {
  pantallaLoading.classList.remove("active");
  pantallaJuego.classList.remove("active");
  pantallaResultados.classList.remove("active");

  if (pantalla === "loading") {
    pantallaLoading.classList.add("active");
  } else if (pantalla === "game") {
    pantallaJuego.classList.add("active");
  } else if (pantalla === "results") {
    pantallaResultados.classList.add("active");
  }
}

function cargarPregunta() {
  if (preguntaActual >= totalPreguntas) {
    terminarJuego();
    return;
  }

  var pregunta = preguntas[preguntaActual];
  palabraPregunta.textContent = pregunta.termino_castellano;

  contenedorOpciones.innerHTML = "";

  var opciones = [
    { texto: pregunta.opcion_euskera_1, numero: 1 },
    { texto: pregunta.opcion_euskera_2, numero: 2 },
    { texto: pregunta.opcion_euskera_3, numero: 3 }
  ];

  for (var i = 0; i < opciones.length; i++) {
    var boton = document.createElement("button");
    boton.className = "option-btn";
    boton.textContent = opciones[i].texto;
    boton.setAttribute("data-opcion", opciones[i].numero);
    boton.onclick = function() {
      comprobarRespuesta(this.getAttribute("data-opcion"));
    };
    contenedorOpciones.appendChild(boton);
  }

  actualizarProgreso();
}

function comprobarRespuesta(opcionElegida) {
  var botones = document.querySelectorAll(".option-btn");
  for (var i = 0; i < botones.length; i++) {
    botones[i].disabled = true;
  }

  var pregunta = preguntas[preguntaActual];
  var respuestaCorrecta = pregunta.respuesta_correcta;

  if (parseInt(opcionElegida) === parseInt(respuestaCorrecta)) {
    manejarRespuestaCorrecta(opcionElegida);
  } else {
    manejarRespuestaIncorrecta(opcionElegida, respuestaCorrecta);
  }
}

function manejarRespuestaCorrecta(opcionElegida) {
  aciertos++;

  var botones = document.querySelectorAll(".option-btn");
  for (var i = 0; i < botones.length; i++) {
    if (botones[i].getAttribute("data-opcion") === opcionElegida) {
      botones[i].classList.add("correct");
    }
  }

  setTimeout(function() {
    preguntaActual++;
    cargarPregunta();
  }, 1500);
}

function manejarRespuestaIncorrecta(opcionElegida, respuestaCorrecta) {
  fallos++;
  vidas--;

  var botones = document.querySelectorAll(".option-btn");
  for (var i = 0; i < botones.length; i++) {
    var numeroOpcion = botones[i].getAttribute("data-opcion");
    if (numeroOpcion === opcionElegida) {
      botones[i].classList.add("incorrect");
    }
    if (numeroOpcion === respuestaCorrecta.toString()) {
      botones[i].classList.add("correct");
    }
  }

  actualizarVidas();

  if (vidas <= 0) {
    setTimeout(function() {
      terminarJuego();
    }, 2000);
  } else {
    setTimeout(function() {
      preguntaActual++;
      cargarPregunta();
    }, 2000);
  }
}

function actualizarVidas() {
  var iconosVida = document.querySelectorAll(".life-icon");
  for (var i = 0; i < iconosVida.length; i++) {
    if (i >= vidas) {
      iconosVida[i].classList.add("lost");
    }
  }
}

function actualizarProgreso() {
  var progreso = (preguntaActual / totalPreguntas) * 100;
  barraProgreso.style.width = progreso + "%";
  textoProgreso.textContent = preguntaActual + "/" + totalPreguntas;
}

function terminarJuego() {
  var tiempoFinal = new Date().getTime();
  var tiempoTranscurrido = Math.floor((tiempoFinal - tiempoInicio) / 1000);
  var puntos = aciertos * 100;

  var peticion = new XMLHttpRequest();

  peticion.onreadystatechange = function() {
    if (peticion.readyState === 4 && peticion.status === 200) {
      console.log("Resultado guardado correctamente");
    }
  };

  var datos = "accion=guardar_resultado";
  datos += "&aciertos=" + aciertos;
  datos += "&fallos=" + fallos;
  datos += "&tiempo_empleado=" + tiempoTranscurrido;

  peticion.open("POST", "../controller/juego_test_controller.php", true);
  peticion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  peticion.send(datos);

  mostrarResultados(puntos);
}

function mostrarResultados(puntos) {
  document.getElementById("correctAnswers").textContent = aciertos;
  document.getElementById("wrongAnswers").textContent = fallos;
  document.getElementById("totalPoints").textContent = puntos;

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

  mostrarPantalla("results");
}

function restartGame() {
  preguntaActual = 0;
  vidas = 3;
  aciertos = 0;
  fallos = 0;
  tiempoInicio = 0;

  var iconosVida = document.querySelectorAll(".life-icon");
  for (var i = 0; i < iconosVida.length; i++) {
    iconosVida[i].classList.remove("lost");
  }

  iniciarJuego();
}

function mostrarError(mensaje) {
  alert(mensaje);
  window.location.href = "perfilAlumno.php";
}
