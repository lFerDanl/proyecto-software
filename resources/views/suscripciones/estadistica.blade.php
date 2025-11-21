@extends('Layouts.admin')

@section('content')

<!-- Styles -->
<style>
    #chartdiv {
        width: 100%;
        height: 500px;
    }
</style>

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<!-- Chart code -->
<script>
am5.ready(function () {
    // Crear elemento raíz
    var root = am5.Root.new("chartdiv");

    // Establecer tema
    root.setThemes([am5themes_Animated.new(root)]);

    // Crear gráfico
    var chart = root.container.children.push(am5xy.XYChart.new(root, {
        panX: true,
        panY: true,
        wheelX: "panX",
        wheelY: "zoomX",
        pinchZoomX: true
    }));

    // Configurar cursor
    var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
    cursor.lineY.set("visible", false);

    // Crear ejes X (categorías: nombres de planes)
    var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
        categoryField: "plan",
        renderer: am5xy.AxisRendererX.new(root, { minGridDistance: 30 })
    }));

    // Crear ejes Y para suscripciones (total)
    var yAxis1 = chart.yAxes.push(am5xy.ValueAxis.new(root, {
        renderer: am5xy.AxisRendererY.new(root, {}),
        title: am5.Label.new(root, { text: "Suscripciones" }) // Etiqueta del eje
    }));

    // Crear ejes Y para ingresos (ingresos)
    var yAxis2 = chart.yAxes.push(am5xy.ValueAxis.new(root, {
        renderer: am5xy.AxisRendererY.new(root, { opposite: true }), // Colocar en el lado derecho
        title: am5.Label.new(root, { text: "Ingresos" }) // Etiqueta del eje
    }));

    // Crear serie para las suscripciones
    var series1 = chart.series.push(am5xy.ColumnSeries.new(root, {
        name: "Suscripciones",
        xAxis: xAxis,
        yAxis: yAxis1, // Vinculado al eje Y de suscripciones
        valueYField: "total", // Campo para el total de suscripciones
        categoryXField: "plan", // Campo para el nombre del plan
        tooltip: am5.Tooltip.new(root, {
            labelText: "{name}: {valueY}"
        })
    }));

    // Estilo para las columnas de la primera serie
    series1.columns.template.setAll({
        cornerRadiusTL: 5,
        cornerRadiusTR: 5,
        strokeOpacity: 0
    });

    // Crear serie para los ingresos
    var series2 = chart.series.push(am5xy.LineSeries.new(root, {
        name: "Ingresos",
        xAxis: xAxis,
        yAxis: yAxis2, // Vinculado al eje Y de ingresos
        valueYField: "ingresos", // Campo para los ingresos
        categoryXField: "plan", // Campo para el nombre del plan
        tooltip: am5.Tooltip.new(root, {
            labelText: "{name}: ${valueY}" // Mostrar como moneda
        })
    }));

    // Estilo para la línea de la segunda serie
    series2.strokes.template.setAll({
        strokeWidth: 2
    });

    // Obtener datos desde Blade
    var data = @json($estadisticas);

    // Asignar datos a los ejes y series
    xAxis.data.setAll(data);
    series1.data.setAll(data);
    series2.data.setAll(data);

    // Animar el gráfico
    series1.appear(1000);
    series2.appear(1000);
    chart.appear(1000, 100);
});
</script>

<!-- HTML -->
<div id="chartdiv"></div>



@endsection 

{{-- -------------------------------------------------------- --}}

{{-- @extends('Layouts.admin')

@section('content') --}}

<!-- Styles -->

{{-- <style>
    #chartdiv {
        width: 100%;
        height: 500px;
    }
</style>

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<!-- Chart code -->
<script>
am5.ready(function() {
    // Crear elemento raíz
    var root = am5.Root.new("chartdiv");

    // Establecer tema
    root.setThemes([
        am5themes_Animated.new(root)
    ]);

    // Crear gráfico
    var chart = root.container.children.push(am5xy.XYChart.new(root, {
        panX: true,
        panY: true,
        wheelX: "panX",
        wheelY: "zoomX",
        pinchZoomX: true
    }));

    // Configurar cursor
    var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
    cursor.lineY.set("visible", false);

    // Crear ejes X (categorías: nombres de planes)
    var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
        categoryField: "plan",
        renderer: am5xy.AxisRendererX.new(root, { minGridDistance: 30 })
    }));

    // Crear ejes Y (valores numéricos)
    var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
        renderer: am5xy.AxisRendererY.new(root, {})
    }));

    // Crear serie para las suscripciones
    var series1 = chart.series.push(am5xy.ColumnSeries.new(root, {
        name: "Suscripciones",
        xAxis: xAxis,
        yAxis: yAxis,
        valueYField: "total", // Campo para el total de suscripciones
        categoryXField: "plan", // Campo para el nombre del plan
        tooltip: am5.Tooltip.new(root, {
            labelText: "{name}: {valueY}"
        })
    }));

    // Estilo para las columnas de la primera serie
    series1.columns.template.setAll({
        cornerRadiusTL: 5,
        cornerRadiusTR: 5,
        strokeOpacity: 0
    });

    // Crear serie para los ingresos
    var series2 = chart.series.push(am5xy.LineSeries.new(root, {
        name: "Ingresos",
        xAxis: xAxis,
        yAxis: yAxis,
        valueYField: "ingresos", // Campo para los ingresos
        categoryXField: "plan", // Campo para el nombre del plan
        tooltip: am5.Tooltip.new(root, {
            labelText: "{name}: {valueY}"
        })
    }));

    // Estilo para la línea de la segunda serie
    series2.strokes.template.setAll({
        strokeWidth: 2
    });

    // Obtener datos desde Blade
    var data = @json($estadisticas);

    // Asignar datos a los ejes y series
    xAxis.data.setAll(data);
    series1.data.setAll(data);
    series2.data.setAll(data);

    // Animar el gráfico
    series1.appear(1000);
    series2.appear(1000);
    chart.appear(1000, 100);
});
</script>

<!-- HTML -->
<div id="chartdiv"></div>  --}}

{{-- @endsection  --}}
