<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{$datos->id}} {{$fecha->toDateTimeString()}}</title>
    <link rel="stylesheet" href="{{ public_path('report/example1/style.css') }}" media="all" />
</head>
<body>
<header class="clearfix">
    <h1>{{ config('app.name') }}</h1>
    <div id="project">
        <div><span>FECHA</span> {{ $fecha->toDateTimeString() }}</div>
        <div><span>DUEÑO</span> {{ auth()->user()->name }}</div>
        <div><span>LISTA</span> {{ $lista->lista->titulo_li }}</div>
        <div><span>PRECIO x LIBRA</span> ${{ number_format($lista->lista->precioxl_li,2) }}</div>
        <div><span>ENVIO LOCAL</span> ${{ number_format($lista->lista->enviolocal_li,2) }}</div>
        <div><span>EMAIL</span> <a href="mailto:{{ auth()->user()->email  }}"> {{ auth()->user()->email }}</a></div>
    </div>
</header>
<main>
    <table>
        <thead>
        <tr>
            <th class="service">FOTO</th>
            <th class="center">CANT.</th>
            <th class="desc">DESCRIPCIÓN</th>
            <th class="righ">PRECIO</th>
            <th class="righ">ENVÍO</th>
            <th class="righ">PESO R.</th>
            <th class="righ">COSTO</th>
            <th class="righ">P.VENTA</th>
        </tr>
        </thead>
        <tbody>
        @php $precio = 0; $envio=0; $costo=0; $peso=0; $venta=0; $enviou=$lista->lista->enviolocal_li/count($lista->productos_lista) @endphp
        @foreach ($lista->productos_lista as $producto)
            <tr>
                <td class="desc">
                    @if($producto->url_pr)
                    <img src="{{ $producto->url_pr  }}"/>
                    @endif
                </td>
                <td class="center">{{ $producto->cant_pr }}</td>
                <td class="desc">{{ $producto->detalle_pr }}</td>
                <td class="unit">${{ number_format($producto->pvpxc_pr,2)  }}</td>
                <td class="unit">${{ number_format(($producto->realxc_pr * $lista->lista->precioxl_li)+$enviou,2) }}</td>
                <td class="qty">{{ number_format($producto->realxc_pr,2) }}lbs</td>
                <td class="qty">${{ number_format(($producto->pvpxc_pr + ($producto->realxc_pr * $lista->lista->precioxl_li)+$enviou),2) }}</td>
                <td class="total">
                    ${{ number_format((($producto->pvpxc_pr + ($producto->realxc_pr * $lista->lista->precioxl_li)+$enviou)*($datos->ganacia)),2) }}
                </td>
                @php
                    $precio+=$producto->pvpxc_pr;
                    $envio+=($producto->realxc_pr * $lista->lista->precioxl_li)+$enviou;
                    $peso+=$producto->realxc_pr;
                    $costo+=($producto->pvpxc_pr + ($producto->realxc_pr * $lista->lista->precioxl_li)+$enviou);
                    $venta+=(($producto->pvpxc_pr + ($producto->realxc_pr * $lista->lista->precioxl_li)+$enviou)*($datos->ganacia));
                @endphp
            </tr>
        @endforeach
        <tr>
            <td colspan="3" class="grand total">TOTAL</td>
            <td class="grand total">${{ number_format($precio,2) }}</td>
            <td class="grand total">${{ number_format($envio,2) }}</td>
            <td class="grand total">{{ number_format($peso,2) }}lbs</td>
            <td class="grand total">${{ number_format($costo,2) }}</td>
            <td class="grand total">${{ number_format($venta,2) }}</td>
        </tr>
        </tbody>
    </table>
    <div id="notices">
        <div>GANANCIA:</div>
        <div class="notice">El valor de la ganacia estimada es de ${{ number_format($venta-$costo,2) }}</div>
    </div>
</main>
</body>
</html>
