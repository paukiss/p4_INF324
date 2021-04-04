<?php

$con =  mysqli_connect("localhost", 'sergio', 'lapaz76236361');
mysqli_select_db($con, "mibase");

$resPersona = mysqli_query($con, "select * from persona");
$personas = [];
while ($datoPersona =  mysqli_fetch_array($resPersona))
{
    $personas[$datoPersona['ci']] = $datoPersona['departamento'];
}


$resNota = mysqli_query($con, "select * from nota");

$notas = array();
while ($datoNota =  mysqli_fetch_array($resNota))
{
    $dat = array($datoNota['sigla'],$datoNota['ci'],  $datoNota['notaFinal']);
    array_push($notas, $dat);
}

foreach ($personas as $clave => $valor)
{
    for ($i = 0; $i < count($notas); $i++)
    {
        $nuevo = $notas[$i];
        if ($clave == $notas[$i][1])
            array_push($nuevo, $valor);
        $notas[$i] = $nuevo;
    }
}


$promedio = [];
for ($i = 0; $i < count($notas); $i++)
    array_push($promedio, array($notas[$i][0], $notas[$i][3], $notas[$i][2]));


$resFinal = [];
sort($promedio);
for ($i = 0; $i < count($promedio); $i++)
{
    $cont = 1;
    for ($j = $i + 1; $j < count($promedio); $j++)
    {
        if ($promedio[$i][0] == $promedio[$j][0] and $promedio[$i][1] == $promedio[$j][1])
        {
            $promedio[$i][2] *= $cont;
            $promedio[$i][2] += $promedio[$j][2];
            $cont += 1;
            $promedio[$i][2] /= $cont;
            $promedio[$j] = [null, null, null];
        }
    }
    if ($promedio[$i][0] != null)
        array_push($resFinal, $promedio[$i]);
}


$mat = [];
for ($i = 0; $i < count($resFinal); $i++)
{
    $dep = array(0,0,0);
    for ($j = $i; $j < count($resFinal); $j++)
    {
        if ($resFinal[$i][0] == $resFinal[$j][0])
        {
            if ($resFinal[$j][1] == 'LP')
                $dep[0] = $resFinal[$j][2];
            elseif ($resFinal[$j][1] == 'CB')
                $dep[1] = $resFinal[$j][2];
            elseif ($resFinal[$j][1] == 'SC')
                $dep[2] = $resFinal[$j][2];
        }
      
    }
    if (!array_key_exists($resFinal[$i][0], $mat))
        $mat[$resFinal[$i][0]] = $dep;
}

?>


<table class="table" style="width: 40%; text-align: center;  ">
        <h3>ROL DOCENTE</h3>
        <tr>
            <th>Sigla</th>
            <th>LP</th>
            <th>CB</th>
            <th>SC</th>
        </tr>
        <?php
            foreach ($mat as $key => $value)
            {
                echo '<tr>';
                echo '<td>' .$key . '</td>';
                for ($i = 0; $i < count($value); $i++)
                {
                    echo '<td>' .$value[$i] . '</td>';
                }        
                echo '</tr>';
            }    
        ?>
</table>