<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Diagnostico de paciente</title>
		<style>
			table {
			  border-collapse: collapse;
			  width: 100%;
			}

			td, th {
			  border: 1px solid #42ABFD;
			  text-align: left;
			  padding: 8px;
			}

			tr:nth-child(even) {
			  background-color: #dddddd;
			}

			a {
				margin-left: 10px;
			}

			b{

				margin-left: 4%;
			}
			
		</style>
	</head>
	<body>

		<div width=100% style="text-align: center; float: left;">
			<h2>SISMET</h2>
			<h3>Sistema medico de enfermedades tropicales</h3>
			<h3>Historia medica  N° {{ $historia->id }}</h3>

		</div>

		<div style="float: right">
			<img src="{{asset('assets/img/zancudo.jpg')}}" width="180px" height="100px">
		</div>
		
		<br style="clear: both;">
		
		<div style="float: left; ">
			<p>Fecha: {{$historia->date}}</p>
		</div>

		<div style="float: right;">
			<p>Médico Especialista: {{$historia->users[0]->name}}</p>
		</div>
		
		<h4 style="clear: both;">Datos del Paciente</h4>
		
		<br>

		<table>
			<thead>
				<tr>
					<th>Nombre</th>
					<th>Apellido</th>
					<th>Direccion</th>
					<th>Ciudad</th>
					<th>Pais</th>
				</tr>
			</thead>
			<tbody>
					<tr>
						<td>{{ $historia->patients[0]->name }}</td>
						<td>{{ $historia->patients[0]->surname }}</td>
						<td>{{ $historia->patients[0]->address }}</td>
						<td>{{ $historia->patients[0]->city }}</td>
						<td>{{ $historia->patients[0]->country }}</td>
					</tr>
			</tbody>
		</table>
		
		<br>
		
		<h4>Datos del diagnostico</h4>
		
		<table>
			<thead>
				<tr>
					<th>Diagnostico</th>
					<th>Probabilidad</th>
					<th>Descripcion</th>
				</tr>
			</thead>
			<tbody>
					<tr>
						<td>{{ $historia->rules[0]->disease_name }}</td>
						<td>{{ $historia->rules[0]->percentage }} %</td>
						<td>{{ $historia->rules[0]->diseases->description }}</td>
					</tr>
			</tbody>
		</table>
		

		<br>
		
		<h4>Sintomas Detectados</h4>
		
		<table>
			<thead>
				<tr>
					<th>Nombre</th>
					<th>Descripcion</th>
					<th>Tipo</th>
				</tr>
			</thead>
			<tbody>
					@foreach($historia->rules[0]->rule_factors as $new)
						@if($new->factors->type == 'S')
							<tr>
								<td>{{ $new->factors->name }}</td>
								<td>{{ $new->factors->descripcion }}</td>
								<td>{{ $new->factors->type }}</td>
							</tr>
						@endif
					@endforeach
			</tbody>
		</table>
		
		<br>
		
		<h4>Antecedentes</h4>
		
		<table>
			<thead>
				<tr>
					<th>Nombre</th>
					<th>Descripcion</th>
					<th>Tipo</th>
				</tr>
			</thead>
			<tbody>
					@foreach($historia->rules[0]->rule_factors as $new)
						@if($new->factors->type == 'A')
							<tr>
								<td>{{ $new->factors->name }}</td>
								<td>{{ $new->factors->descripcion }}</td>
								<td>{{ $new->factors->type }}</td>
							</tr>
						@endif
					@endforeach			
			</tbody>
		</table>

		<br>
		
		<h4>Otros Factores</h4>
		
		<table>
			<thead>
				<tr>
					<th>Nombre</th>
					<th>Descripcion</th>
					<th>Tipo</th>
				</tr>
			</thead>
			<tbody>
					@foreach($historia->rules[0]->rule_factors as $new)
						@if($new->factors->type == 'O')
							<tr>
								<td>{{ $new->factors->name }}</td>
								<td>{{ $new->factors->descripcion }}</td>
								<td>{{ $new->factors->type }}</td>
							</tr>
						@endif
					@endforeach			
			</tbody>
		</table>


	</body>
</html>