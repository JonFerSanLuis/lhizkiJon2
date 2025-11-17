<div class="container-fluid">
	<!-- Top stat cards -->
	<div class="row g-3 mb-4">
		<div class="col-6 col-md-3">
			<div class="card stat-card bg-gradient-purple text-purple h-100">
				<div class="card-body d-flex flex-column justify-content-between">
					<div class="d-flex justify-content-between align-items-start">
						<div>
							<i class="fa-solid fa-user fa-2x"></i>
						</div>
						<div class="text-end">
							<h5 class="mb-0" id="totalUsuarios">-</h5>
							<small>Erab.</small>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-6 col-md-3">
			<div class="card stat-card bg-gradient-blue text-blue h-100">
				<div class="card-body d-flex flex-column justify-content-between">
					<div class="d-flex justify-content-between align-items-start">
						<div>
							<i class="fa-solid fa-database fa-2x"></i>
						</div>
						<div class="text-end">
							<h5 class="mb-0" id="totalJuegos">-</h5>
							<small>Jokoak</small>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-6 col-md-3">
			<div class="card stat-card bg-gradient-green text-green h-100">
				<div class="card-body d-flex flex-column justify-content-between">
					<div class="d-flex justify-content-between align-items-start">
						<div>
							<i class="fa-solid fa-chart-line fa-2x"></i>
						</div>
						<div class="text-end">
							<h5 class="mb-0" id="porcentajeParticipacion">-</h5>
							<small>Partaid.</small>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-6 col-md-3">
			<div class="card stat-card bg-gradient-yellow text-yellow h-100">
				<div class="card-body d-flex flex-column justify-content-between">
					<div class="d-flex justify-content-between align-items-start">
						<div>
							<i class="fa-solid fa-shield-halved fa-2x"></i>
						</div>
						<div class="text-end">
							<h5 class="mb-0" id="totalIkasle">-</h5>
							<small>Ikasle</small>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-4">
		<!-- Left column: Roles by count -->
		<div class="col-lg-6">
			<div class="card h-100">
				<div class="card-body">
					<h6 class="card-title"><i class="fa-regular fa-user"></i>  Erabiltzaileak rolaren arabera</h6>

					<ul class="list-group list-group-flush mt-3" id="listaRoles">
						<li class="list-group-item text-center text-muted">
							<i class="fa-solid fa-spinner fa-spin"></i> Kargatzen...
						</li>
					</ul>
				</div>
			</div>
		</div>

		<!-- Right column: Participation by school -->
		<div class="col-lg-6">
			<div class="card h-100">
				<div class="card-body">
					<h6 class="card-title"><i class="fa-solid fa-database"></i>  Partaidetza ikastetxez</h6>

					<div class="mt-3" id="listaCentros">
						<div class="text-center text-muted">
							<i class="fa-solid fa-spinner fa-spin"></i> Kargatzen...
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>