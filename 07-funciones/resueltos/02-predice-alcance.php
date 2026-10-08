<?php
/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESUELTO 02 — ¿Qué variables "ve" una función?
 *  Tipo: predice la salida
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  CÓMO TRABAJAR ESTE ARCHIVO
 *  1. NO lo ejecutes todavía.
 *  2. Lee cada caso y escribe en tu cuaderno qué crees que imprime.
 *  3. Ejecútalo y compara. Por cada caso que fallaste, lee la explicación
 *     al final del archivo.
 *
 *  Los casos 1 y 2 muestran avisos (Warning) A PROPÓSITO: son parte de la lección.
 */

$iva = 0.19;
$contador = 10;

// ── CASO 1 ────────────────────────────────────────────────────────────────────
function precioConIva($precio)
{
    return $precio + $precio * $iva;
}

echo "Caso 1: " . precioConIva(1000) . "<br>";
// Tu predicción: ____________


// ── CASO 2 ────────────────────────────────────────────────────────────────────
function incrementar()
{
    $contador++;
    return $contador;
}

echo "Caso 2: " . incrementar() . " | contador afuera = $contador<br>";
// Tu predicción: ____________


// ── CASO 3 ────────────────────────────────────────────────────────────────────
function precioConIvaBien($precio, $iva)
{
    return $precio + $precio * $iva;
}

echo "Caso 3: " . precioConIvaBien(1000, $iva) . "<br>";
// Tu predicción: ____________


// ── CASO 4 ────────────────────────────────────────────────────────────────────
function cambiar($valor)
{
    $valor = 99;
    return $valor;
}

$numero = 5;
$resultado = cambiar($numero);
echo "Caso 4: numero = $numero, resultado = $resultado<br>";
// Tu predicción: ____________


// ── CASO 5 ────────────────────────────────────────────────────────────────────
function cambiarDeVerdad(&$valor)
{
    $valor = 99;
}

$numero = 5;
cambiarDeVerdad($numero);
echo "Caso 5: numero = $numero<br>";
// Tu predicción: ____________


// ── CASO 6 ────────────────────────────────────────────────────────────────────
function siguienteTurno()
{
    static $turno = 0;
    $turno++;
    return "T-$turno";
}

echo "Caso 6: " . siguienteTurno() . ", " . siguienteTurno() . ", " . siguienteTurno() . "<br>";
// Tu predicción: ____________


// ── CASO 7 ────────────────────────────────────────────────────────────────────
$descuento = 0.10;
$conDescuento = fn($precio) => $precio * (1 - $descuento);

$descuento = 0.50;   // ¿la función usa 0.10 o 0.50?
echo "Caso 7: " . $conDescuento(1000) . "<br>";
// Tu predicción: ____________


/*
 * ════════════════════════════════════════════════════════════════════════════
 *  RESPUESTAS Y EXPLICACIÓN (lee esto DESPUÉS de ejecutar)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  CASO 1 -> Warning: Undefined variable $iva ... y luego "Caso 1: 1000"
 *    Una función de PHP NO ve las variables de afuera. Dentro de la función
 *    $iva no existe (vale null), y 1000 * null = 0.
 *
 *  CASO 2 -> Warning: Undefined variable $contador ... "Caso 2: 1 | contador afuera = 10"
 *    Adentro, $contador es una variable NUEVA (null + 1 = 1). La de afuera
 *    sigue en 10: son dos variables distintas que se llaman igual.
 *
 *  CASO 3 -> "Caso 3: 1190"
 *    La forma correcta: lo que la función necesita, se lo pasas como parámetro.
 *    Así la función es predecible: sus resultados dependen solo de lo que recibe.
 *
 *  CASO 4 -> "Caso 4: numero = 5, resultado = 99"
 *    $valor recibe una COPIA de $numero. Cambiar la copia no toca el original.
 *
 *  CASO 5 -> "Caso 5: numero = 99"
 *    El & pide la variable original (por referencia). Úsalo poco: es más
 *    claro devolver el valor con return.
 *
 *  CASO 6 -> "Caso 6: T-1, T-2, T-3"
 *    static hace que la variable sobreviva entre llamadas: solo se inicializa
 *    en 0 la primera vez.
 *
 *  CASO 7 -> "Caso 7: 900"
 *    Una arrow function (fn) captura el valor de $descuento EN EL MOMENTO EN
 *    QUE SE CREA (0.10). Cambiarlo después no la afecta.
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  TRAMPA DE PHP (viniendo de Python)
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  En Python una función SÍ puede LEER una variable global sin declararla:
 *      iva = 0.19
 *      def precio_con_iva(p): return p + p * iva     # funciona
 *  En PHP no: por eso el caso 1 falla. Existe la palabra "global $iva;" para
 *  forzarlo, pero es mala práctica: la función queda atada a una variable que
 *  cualquiera puede cambiar. Pasa los datos como parámetros (caso 3).
 *
 * ════════════════════════════════════════════════════════════════════════════
 *  PARA ANALIZAR
 * ════════════════════════════════════════════════════════════════════════════
 *
 *  1. ¿Por qué el caso 1 no produce un error que detenga el programa, sino un
 *     resultado equivocado? ¿Qué es más peligroso?
 *  2. Reescribe incrementar() para que funcione SIN & y SIN global.
 *  3. En el caso 7, cambia fn por function(...) use ($descuento). ¿Cambia el resultado?
 *  4. ¿En qué situación real usarías static? (pista: el caso 6)
 */
