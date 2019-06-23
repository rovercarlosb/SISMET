<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>NOTIFICACION DE CITA MEDICA</title>
</head>
<body>
    @php
        $fecha = date_create($appointment->date);
        $hora = date_create($appointment->hour);
    @endphp

    <p>Hola! Este mensaje es para notificarte y recordarte que tu proxima cita esta programada para el dia {{ date_format($fecha,'d-m-Y') }} a las {{ date_format($hora,'g:i A') }}. Te esperamos!!! </p>
    <br>

    <p style="text-align: center;"><b>SISMET | SISTEMA PARA EL CONTROL DE ENFERMEDEDADES TROPICALES ©2019 </b></p>

</body>
</html>