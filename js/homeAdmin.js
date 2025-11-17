
document.addEventListener('DOMContentLoaded', function() {
	console.log('DOM cargado, iniciando homeAdmin.js');
	cargarEstadisticas();
});

/**
 * Obtiene las estadísticas del servidor y actualiza la interfaz
 */
function cargarEstadisticas() {
	console.log('Iniciando carga de estadísticas...');
	
	fetch('controller/homeAdmin-controller.php')
		.then(response => {
			console.log('Respuesta recibida:', response);
			if (!response.ok) {
				throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
			}
			return response.json();
		})
		.then(data => {
			console.log('Datos recibidos:', data);
			if (data.success) {
				// Actualizar tarjetas superiores con animación
				actualizarContador('totalUsuarios', data.data.totalUsuarios);
				actualizarContador('totalJuegos', data.data.totalJuegos);
				actualizarContador('porcentajeParticipacion', data.data.porcentajeParticipacion, '%');
				actualizarContador('totalIkasle', data.data.totalIkasle);
				
				// Actualizar lista de roles
				mostrarRoles(data.data.usuariosPorRol);
				
				// Actualizar participación por centros
				mostrarCentros(data.data.participacionCentros);
				
				console.log('Datos cargados exitosamente');
			} else {
				console.error('Error al cargar estadísticas:', data.error);
				mostrarError();
			}
		})
		.catch(error => {
			console.error('Error en la petición:', error);
			mostrarError();
		});
}

/**
 * Actualiza un contador con animación
 * @param {string} elementId - ID del elemento a actualizar
 * @param {number} valor - Valor final del contador
 * @param {string} sufijo - Sufijo opcional (ej: '%')
 */
function actualizarContador(elementId, valor, sufijo = '') {
	const elemento = document.getElementById(elementId);
	if (!elemento) return;
	
	elemento.textContent = valor + sufijo;
}

/**
 * Muestra la lista de usuarios por rol
 * @param {Array} roles - Array de objetos con nombre_rol y total
 */
function mostrarRoles(roles) {
	const listaRoles = document.getElementById('listaRoles');
	
	if (!listaRoles) return;
	
	if (roles.length === 0) {
		listaRoles.innerHTML = '<li class="list-group-item text-center text-muted">Ez dago daturik</li>';
		return;
	}
	
	const colores = ['purple', 'green', 'blue', 'yellow'];
	let html = '';
	
	roles.forEach((rol, index) => {
		const color = colores[index % colores.length];
		const clase = index < roles.length - 1 ? 'mb-2' : '';
		html += `
			<li class="list-group-item d-flex justify-content-between align-items-center rounded-pill role-item bg-role-${color} text-${color} ${clase}">
				<span>${rol.nombre_rol}</span>
				<span class="badge bg-transparent border-0 text-${color}">${rol.total}</span>
			</li>
		`;
	});
	
	listaRoles.innerHTML = html;
}

/**
 * Muestra la participación por centros educativos
 * @param {Array} centros - Array de objetos con centro y porcentaje_participacion
 */
function mostrarCentros(centros) {
	const listaCentros = document.getElementById('listaCentros');
	
	if (!listaCentros) return;
	
	if (centros.length === 0) {
		listaCentros.innerHTML = '<div class="text-center text-muted">Ez dago daturik</div>';
		return;
	}
	
	const colores = ['success', 'info', 'primary', 'warning', 'danger'];
	let html = '';
	
	centros.forEach((centro, index) => {
		const color = colores[index % colores.length];
		const clase = index < centros.length - 1 ? 'mb-3' : 'mb-0';
		html += `
			<div class="${clase}">
				<div class="d-flex justify-content-between">
					<small>${centro.centro}</small>
					<small>${centro.porcentaje_participacion}%</small>
				</div>
				<div class="progress" style="height:10px;">
					<div class="progress-bar bg-${color}" role="progressbar" style="width: ${centro.porcentaje_participacion}%" 
						 aria-valuenow="${centro.porcentaje_participacion}" aria-valuemin="0" aria-valuemax="100"
						 title="${centro.total_partidas} partidas (${centro.partidas_completadas} completadas)"></div>
				</div>
			</div>
		`;
	});
	
	listaCentros.innerHTML = html;
}

/**
 * Muestra mensajes de error en caso de fallo en la carga de datos
 */
function mostrarError() {
	const elementos = ['totalUsuarios', 'totalJuegos', 'porcentajeParticipacion', 'totalIkasle'];
	
	elementos.forEach(id => {
		const elemento = document.getElementById(id);
		if (elemento) {
			elemento.textContent = 'Error';
		}
	});
	
	const listaRoles = document.getElementById('listaRoles');
	if (listaRoles) {
		listaRoles.innerHTML = '<li class="list-group-item text-center text-danger"><i class="fa-solid fa-exclamation-triangle"></i> Errorea datuak kargatzean</li>';
	}
	
	const listaCentros = document.getElementById('listaCentros');
	if (listaCentros) {
		listaCentros.innerHTML = '<div class="text-center text-danger"><i class="fa-solid fa-exclamation-triangle"></i> Errorea datuak kargatzean</div>';
	}
}

/**
 * Recarga las estadísticas manualmente
 */
function recargarEstadisticas() {
	// Mostrar indicadores de carga
	document.getElementById('totalUsuarios').innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
	document.getElementById('totalJuegos').innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
	document.getElementById('porcentajeParticipacion').innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
	document.getElementById('totalIkasle').innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
	
	// Recargar datos
	cargarEstadisticas();
}