<?php require_once INCLUDES.'inc_header.php'; ?>

<!-- Content Row -->
<div class="row">

						<!-- Earnings (Monthly) Card Example -->
						<div class="col-xl-3 col-md-6 mb-4">
							<a href="materias/asignadas" class="card border-left-primary shadow h-100 py-2" style="text-decoration: none">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Materias</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $d->stats->materias;?></div>
										</div>
										<div class="col-auto">
											<i class="fas fa-book fa-2x text-primary"></i>
										</div>
									</div>
								</div>
							</a>
						</div>

						<!-- Earnings (Monthly) Card Example -->
						<div class="col-xl-3 col-md-6 mb-4">
							<a href="grupos/asignados" class="card border-left-success shadow h-100 py-2" style="text-decoration: none">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-success text-uppercase mb-1">Grupos</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $d->stats->grupos;?></div>
										</div>
										<div class="col-auto">
											<i class="fas fa-users fa-2x text-success"></i>
										</div>
									</div>
								</div>
							</a>
						</div>

						<!-- Earnings (Monthly) Card Example -->
						<div class="col-xl-3 col-md-6 mb-4">
							<a href="alumnos/asignados" class="card border-left-info shadow h-100 py-2" style="text-decoration: none">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Alumnos</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $d->stats->alumnos;?></div>
										</div>
										<div class="col-auto">
											<i class="fas fa-user-friends fa-2x text-info"></i>
										</div>
									</div>
								</div>
							</a>
						</div>

						<!-- Pending Requests Card Example -->
						<div class="col-xl-3 col-md-6 mb-4">
							<a href="lecciones/mislecciones" class="card border-left-warning shadow h-100 py-2" style="text-decoration: none">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Lecciones</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $d->stats->lecciones; ?></div>
										</div>
										<div class="col-auto">
											<i class="fas fa-chalkboard-teacher fa-2x text-warning"></i>
										</div>
									</div>
								</div>
							</a>
						</div>

						<!-- Pending Requests Card Example -->
						<div class="col-xl-3 col-md-6 mb-4">
							<a href="tareas/mistareas" class="card border-left-danger shadow h-100 py-2" style="text-decoration: none">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Tareas</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $d->stats->tareas; ?></div>
										</div>
										<div class="col-auto">
											<i class="fas fa-layer-group fa-2x text-danger"></i>
										</div>
									</div>
								</div>
							</a>
						</div>

</div>


<?php require_once INCLUDES.'inc_footer.php'; ?>